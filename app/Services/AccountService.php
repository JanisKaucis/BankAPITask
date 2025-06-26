<?php
namespace App\Services;

use App\Http\Resources\TransactionResource;
use App\Models\Account;

class AccountService
{
    public function getTransactions($id, $request) {

        $account = Account::find($id);
        if (!$account) return response()->json(['error' => 'Account not found'], 404);

        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 10);

        $transactions = $account->transactions()
            ->orderByDesc('created_at')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return TransactionResource::collection($transactions);
    }
}
