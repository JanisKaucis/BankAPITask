<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => fake()->numberBetween(1, 10),
            'amount' => fake()->numberBetween(10000, 90000),
            'type' => fake()->randomElement(Transaction::TYPES),
            'description' => fake()->sentence(),
        ];
    }
}
