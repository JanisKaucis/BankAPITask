<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Client::factory(2)->create()->each(function ($client) {
            $currencies = ['USD', 'EUR', 'GBP'];
            foreach ($currencies as $currency) {
                $account = $client->accounts()->create([
                    'currency' => $currency,
                    'balance' => rand(100000, 500000),
                ]);

                $account->transactions()->create([
                    'amount' => 10000,
                    'type' => 'credit',
                    'description' => 'Initial deposit',
                ]);
            }
        });
    }
}
