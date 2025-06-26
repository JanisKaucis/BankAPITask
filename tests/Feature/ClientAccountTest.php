<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_accounts_are_listed()
    {
        $client = Client::factory()->create();
        $client->accounts()->createMany([
            ['currency' => 'USD', 'balance' => 1000],
            ['currency' => 'EUR', 'balance' => 2000],
        ]);

        $response = $this->getJson("/api/clients/{$client->id}/accounts");
        $response->assertStatus(200)
            ->assertJsonCount(2,  'data');
    }
}
