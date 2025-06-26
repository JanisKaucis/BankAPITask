<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/clients/{id}/accounts', [ClientController::class, 'accounts']);
Route::get('/accounts/{id}/transactions', [AccountController::class, 'transactions']);
Route::post('/transfer', [TransferController::class, 'transfer']);
