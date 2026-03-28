<?php

namespace App\service;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($data)
    {
        $user = $this->userRepository->create($data);
        if (!$user) {
            return response()->json(['message' => 'User registration failed'], 500);
        }else {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
        }

    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

         if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
             return response()->json(['message' => 'Invalid credentials'], 401);
         }

         $user = $this->userRepository->findByEmail($data['email']);

         if (!$user || !Hash::check($data['password'], $user->password)) {
             return response()->json(['message' => 'User not found'], 404);
         }else {
             $token = $user->createToken('auth_token')->plainTextToken;
             return response()->json(['message' => 'Login successful', 'user' => $user, 'token' => $token], 200);
         }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logout successful']);
    }
}