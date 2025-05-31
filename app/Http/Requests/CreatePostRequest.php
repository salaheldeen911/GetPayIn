<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // 5MB max
            'platforms' => ['required', 'array', 'min:1'],
            'platforms.*' => ['exists:platforms,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'status' => ['nullable', 'string', 'in:draft,scheduled'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'A title is required.',
            'title.max' => 'The title cannot be longer than 255 characters.',
            'content.required' => 'Content is required.',
            'platforms.required' => 'Please select at least one platform.',
            'platforms.exists' => 'One or more selected platforms are invalid.',
            'status.required' => 'Please select a post status.',
            'status.in' => 'Invalid post status selected.',
            'scheduled_at.required' => 'Please select a date and time for scheduled posts.',
            'scheduled_at.after' => 'The scheduled time must be in the future.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image size cannot exceed 5MB.',
        ];
    }
}
