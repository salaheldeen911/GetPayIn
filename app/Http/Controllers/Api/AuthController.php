<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(ApiLoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->first();

            if (! $user || ! Hash::check($validated['password'], $user->password)) {
                return $this->failed('Invalid credentials', [], 401);
            }

            $token = $user->createToken($validated['device_name'])->plainTextToken;

            return $this->success('Login successful', [
                'token' => $token,
                'user' => new UserResource($user),
            ]);
        } catch (Exception $e) {
            $this->errorLog($e, 'Login failed');

            return $this->failed('An error occurred during login');
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            $token = $user->createToken($validated['device_name'])->plainTextToken;

            return $this->success('Registration successful', [
                'token' => $token,
                'user' => new UserResource($user),
            ], 201);
        } catch (Exception $e) {
            $this->errorLog($e, 'Registration failed');

            return $this->failed('An error occurred during registration');
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->success('Logged out successfully');
        } catch (Exception $e) {
            $this->errorLog($e, 'Logout failed');

            return $this->failed('An error occurred during logout');
        }
    }

    public function user(Request $request)
    {
        try {
            return $this->success('User details retrieved successfully', [
                'user' => new UserResource($request->user()),
            ]);
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to retrieve user details');

            return $this->failed('An error occurred while retrieving user details');
        }
    }
}
