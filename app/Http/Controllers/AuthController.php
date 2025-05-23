<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|confirmed',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $response = $this->service->register($data);

        return response()->json($response);
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $response = $this->service->login($data);

        return response()->json($response);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $this->service->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
