<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'total_amount' => fake()->numberBetween(10, 1000),
            'shipping_address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'note' => fake()->optional(0.3)->sentence(),
        ];
    }
} 