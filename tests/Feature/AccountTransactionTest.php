<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AccountTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_transactions_are_paginated()
    {
        $client = Client::factory()->create();
        $account = $client->accounts()->create(['currency' => 'USD', 'balance' => 1000]);
        Transaction::factory()->count(15)->create(['account_id' => $account->id]);

        $response = $this->getJson("/api/accounts/{$account->id}/transactions?offset=0&limit=10");
        $response->assertStatus(200)
            ->assertJsonCount(10,  'data');
    }
}
