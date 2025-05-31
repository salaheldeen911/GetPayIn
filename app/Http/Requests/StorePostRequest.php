<?php

namespace App\Http\Requests;

use App\Enums\PostStatus;
use App\Models\Platform;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
            'image' => ['nullable', 'image', 'max:2048'],
            'platforms' => ['required', 'array', 'min:1'],
            'platforms.*' => ['exists:platforms,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'status' => ['required', Rule::in([
                PostStatus::DRAFT->value,
                PostStatus::SCHEDULED->value,
            ])],
            'platform_specific_data' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'platforms.required' => 'Please select at least one platform.',
            'platforms.min' => 'Please select at least one platform.',
            'scheduled_at.after' => 'The scheduled time must be in the future.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Move platform validation to withValidator as it needs the validator instance
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validatePlatformRequirements($validator);
        });
    }

    protected function validatePlatformRequirements($validator)
    {
        if (! $this->has('platforms')) {
            return;
        }

        $content = $this->input('content');
        $hasImage = $this->hasFile('image');
        $selectedPlatforms = Platform::whereIn('id', $this->input('platforms'))->get();

        foreach ($selectedPlatforms as $platform) {
            switch ($platform->name) {
                case 'twitter':
                    if (strlen($content) > 280) {
                        $validator->errors()->add(
                            'content',
                            'Twitter has a maximum limit of 280 characters. Your content is '.strlen($content).' characters long.'
                        );
                    }
                    break;

                case 'instagram':
                    if (! $hasImage) {
                        $validator->errors()->add(
                            'image',
                            'Instagram posts require at least one image.'
                        );
                    }
                    break;

                case 'facebook':
                    if (strlen($content) > 63206) {
                        $validator->errors()->add(
                            'content',
                            'Facebook has a maximum limit of 63,206 characters. Your content is '.strlen($content).' characters long.'
                        );
                    }
                    break;

                case 'linkedin':
                    if (strlen($content) > 3000) {
                        $validator->errors()->add(
                            'content',
                            'LinkedIn has a maximum limit of 3,000 characters. Your content is '.strlen($content).' characters long.'
                        );
                    }
                    break;
            }
        }
    }
}
