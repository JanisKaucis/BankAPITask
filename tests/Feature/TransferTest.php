<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_fund_transfer_same_currency()
    {
        $client = Client::factory()->create();
        $from = $client->accounts()->create(['currency' => 'USD', 'balance' => 100]);
        $to = $client->accounts()->create(['currency' => 'USD', 'balance' => 50]);

        $payload = [
            'from_account_id' => $from->id,
            'to_account_id' => $to->id,
            'amount' => 40,
        ];

        $response = $this->postJson('/api/transfer', $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Transfer successful']);

        $this->assertEquals(60, $from->fresh()->balance);
        $this->assertEquals(90, $to->fresh()->balance);
    }

    public function test_fund_transfer_different_currency()
    {
        Http::fake([
            'https://api.exchangerate.host/*' => Http::response(['result' => 80], 200),
        ]);

        $client = Client::factory()->create();
        $from = $client->accounts()->create(['currency' => 'USD', 'balance' => 100]);
        $to = $client->accounts()->create(['currency' => 'EUR', 'balance' => 50]);

        $payload = [
            'from_account_id' => $from->id,
            'to_account_id' => $to->id,
            'amount' => 20,
        ];

        $response = $this->postJson('/api/transfer', $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Transfer successful']);

        $this->assertEquals(80, $from->fresh()->balance);
        $this->assertEquals(130, $to->fresh()->balance);
    }

    public function test_fund_transfer_fails_with_insufficient_balance()
    {
        $client = Client::factory()->create();
        $from = $client->accounts()->create(['currency' => 'USD', 'balance' => 10]);
        $to = $client->accounts()->create(['currency' => 'USD', 'balance' => 50]);

        $response = $this->postJson('/api/transfer', [
            'from_account_id' => $from->id,
            'to_account_id' => $to->id,
            'amount' => 100,
        ]);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Insufficient funds']);
    }
}
