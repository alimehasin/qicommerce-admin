<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  public function __construct(protected UserRepository $repository)
  {
  }

  public function register(array $data): array
  {
    $user = $this->repository->createUser($data);
    $token = $this->repository->createAccessToken($user);

    return [
      'user' => $user,
      'token' => $token,
    ];
  }

  public function login(array $data): array
  {
    $user = $this->repository->getUserByEmail($data['email']);

    if (!$user || !Hash::check($data['password'], $user->password)) {
      throw new Exception('Invalid credentials');
    }

    $token = $this->repository->createAccessToken($user);

    return [
      'user' => $user,
      'token' => $token,
    ];
  }

  public function logout(User $user): void
  {
    $this->repository->deleteUserAccessToken($user);
  }
}