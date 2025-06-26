<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransferService
{
    public function makeTransfer($validated): \Illuminate\Http\JsonResponse
    {
        $from = Account::find($validated['from_account_id']);
        $to = Account::find($validated['to_account_id']);
        $amount = $validated['amount'];

        if (!$from || !$to) return response()->json(['error' => 'Invalid accounts'], 404);

        DB::beginTransaction();
        try {
            $convertedAmount = $amount;
            if ($from->currency !== $to->currency) {
                $convertedAmount = $this->convert($from->currency, $to->currency, $amount);
                if ($convertedAmount === null) {
                    return response()->json(['error' => 'Conversion failed'], 400);
                }
            }

            if ($from->balance < $amount) {
                return response()->json(['error' => 'Insufficient funds'], 400);
            }

            $from->balance -= $amount;
            $to->balance += $convertedAmount;
            if ($from->balance < 0) {
                return response()->json(['error' => 'Negative balance'], 400);
            }

            $from->save();
            $to->save();

            Transaction::create([
                'account_id' => $from->id,
                'amount' => -$amount,
                'type' => 'debit',
                'description' => "Transfer to #{$to->id}",
            ]);
            Transaction::create([
                'account_id' => $to->id,
                'amount' => $convertedAmount,
                'type' => 'credit',
                'description' => "Transfer from #{$from->id}",
            ]);

            DB::commit();
            return response()->json(['message' => 'Transfer successful']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transfer failed: ' . $e->getMessage()], 500);
        }
    }

    public function convert($from, $to, $amount) {
        $response = Http::get(env('EXCHANGE_RATE_API_URL'), [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'access_key' => env('EXCHANGE_RATE_API_KEY'),
        ]);

        return $response->successful() ? $response->json('result') : null;
    }
}
