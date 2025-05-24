<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Root User',
            'email' => 'root@qi.iq',
            'email_verified_at' => now(),
        ]);

        // Create 100 regular users
        User::factory()->count(100)->create();
    }
} 