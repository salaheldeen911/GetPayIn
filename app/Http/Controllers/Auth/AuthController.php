<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken($request->device_name)->plainTextToken;

            if ($request->wantsJson()) {
                return $this->success('Registration successful', [
                    'token' => $token,
                    'user' => $user,
                ], 201);
            }

            return redirect()->route('login')
                ->with('status', 'Registration successful! Please login.');
        } catch (Exception $e) {
            $this->errorLog($e, 'Registration failed');

            if ($request->wantsJson()) {
                return $this->failed('An error occurred during registration');
            }

            return back()
                ->withInput()
                ->withErrors(['email' => 'An error occurred during registration. Please try again later.']);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (! Auth::attempt($credentials, $request->boolean('remember'))) {
                if ($request->wantsJson()) {
                    return $this->failed('Invalid credentials', [], 401);
                }

                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken($request->device_name)->plainTextToken;

            if ($request->wantsJson()) {
                return $this->success('Login successful', [
                    'token' => $token,
                    'user' => $user,
                ]);
            }

            return redirect()->intended(route('dashboard'));
        } catch (Exception $e) {
            $this->errorLog($e, 'Login failed');

            if ($request->wantsJson()) {
                return $this->failed('An error occurred during login');
            }

            return back()
                ->withInput()
                ->withErrors(['email' => 'An error occurred during login. Please try again later.']);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            if ($user = Auth::user()) {
                $user->tokens()->delete();
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return $this->success('Logged out successfully');
            }

            return redirect()->route('login')
                ->with('status', 'You have been logged out.');
        } catch (Exception $e) {
            $this->errorLog($e, 'Logout failed');

            if ($request->wantsJson()) {
                return $this->failed('An error occurred during logout');
            }

            return redirect()->route('login')
                ->with('error', 'An error occurred during logout.');
        }
    }
}
