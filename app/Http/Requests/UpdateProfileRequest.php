<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // 5MB max
            'remove_avatar' => ['nullable', 'boolean'],
            'timezone' => ['sometimes', 'required', 'string', 'timezone'],
            'current_password' => ['required_with:password', 'nullable', 'string'],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'avatar.image' => 'The file must be an image.',
            'avatar.max' => 'The image size cannot exceed 5MB.',
            'timezone.required' => 'Please select your timezone.',
            'timezone.timezone' => 'Please select a valid timezone.',
            'current_password.required_with' => 'Please enter your current password to set a new password.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
