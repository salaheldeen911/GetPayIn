<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $device_name = 'browser';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'name.max' => 'Name cannot be longer than 255 characters',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 8 characters',
        'password.confirmed' => 'Password confirmation does not match',
    ];

    public function register()
    {
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Create API token
            $token = $user->createToken($this->device_name)->plainTextToken;

            // Store token in session for potential API usage
            session(['api_token' => $token]);

            session()->flash('status', 'Registration successful! Please login.');

            return redirect()->route('login');
        } catch (\Exception $e) {
            report($e); // Log the error
            // For debugging, show the actual error
            session()->flash('error', 'Debug error: '.$e->getMessage());

            return;
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
