<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Services\TransferService;

class TransferController extends Controller
{
    protected $transferService;
    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function transfer(TransferRequest $request)
    {
        $validated = $request->validated();

        return $this->transferService->makeTransfer($validated);
    }
}
