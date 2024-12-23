<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        // validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        // generate token
        $token = $user->createToken('web')->plainTextToken;
        // return token
        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request): JsonResponse
    {
        // validate
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);
        // attempt login
        if (!auth()->attempt($validated)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // generate token
        $token = $request->user()->createToken('web')->plainTextToken;
        // return token
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json([], 204);
    }
}
