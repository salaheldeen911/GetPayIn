<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use App\Models\Platform;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('post'));
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'scheduled_at' => [
                'required',
                'date',
                Rule::when(
                    $post->status !== PostStatus::PUBLISHED,
                    ['after:now']
                ),
            ],
            'status' => [
                'required',
                Rule::in([PostStatus::DRAFT->value, PostStatus::SCHEDULED->value]),
                Rule::when(
                    $post->status === PostStatus::PUBLISHED,
                    ['not_in:'.PostStatus::PUBLISHED->value]
                ),
            ],
            'platforms' => ['required', 'array', 'min:1'],
            'platforms.*' => ['required', 'exists:platforms,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'platforms.required' => 'Please select at least one platform.',
            'platforms.min' => 'Please select at least one platform.',
            'scheduled_at.after' => 'The scheduled time must be in the future.',
            'status.not_in' => 'Cannot change the status of a published post.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('platforms') && $this->has('content')) {
            $content = $this->input('content');
            $platformIds = $this->input('platforms', []);

            foreach ($platformIds as $platformId) {
                $platform = Platform::find($platformId);
                if ($platform && ! $platform->validateContent($content)) {
                    $maxLength = $platform->getMaxLength();
                    $this->validator->errors()->add(
                        'content',
                        "Content exceeds maximum length of {$maxLength} characters for {$platform->name}."
                    );
                }
            }
        }
    }
}
