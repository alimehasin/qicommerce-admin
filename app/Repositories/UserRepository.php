<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function __construct(protected User $model)
    {
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function createUser(array $data): User
    {
        return $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function createAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function deleteUserAccessToken(User $user): void
    {
        $user->tokens()->delete();
    }
}
