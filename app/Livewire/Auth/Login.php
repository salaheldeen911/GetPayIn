<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    public $device_name = 'browser';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'password.required' => 'Password is required',
    ];

    public function login()
    {
        $this->validate();

        try {
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                // Regenerate session first
                session()->regenerate();

                $user = Auth::user();

                // Revoke any existing tokens for this device
                $user->tokens()->where('name', $this->device_name)->delete();

                // Create new token
                $token = $user->createToken($this->device_name)->plainTextToken;

                // Store token in session for potential API usage
                session(['api_token' => $token]);

                return redirect()->intended(route('dashboard'));
            }

            $this->addError('email', 'The provided credentials do not match our records.');

            return;
        } catch (\Exception $e) {
            report($e); // Log the error
            // For debugging, show the actual error
            $this->addError('email', 'Debug error: '.$e->getMessage());

            return;
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
