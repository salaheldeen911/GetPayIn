<?php

namespace App\Rules;

use App\Models\Platform;
use App\Services\Publishing\PublishingStrategyFactory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlatformContentRule implements ValidationRule
{
    protected array $platforms;

    protected PublishingStrategyFactory $strategyFactory;

    public function __construct(array $platforms)
    {
        $this->platforms = $platforms;
        $this->strategyFactory = app(PublishingStrategyFactory::class);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $content = (string) $value;

        foreach ($this->platforms as $platformId) {
            $platform = Platform::find($platformId);

            if (! $platform) {
                continue;
            }

            try {
                $maxLength = (int) $platform->getRequirement('max_length');
                if (strlen($content) > $maxLength) {
                    $fail("Content exceeds {$platform->name}'s {$maxLength} character limit.");
                }

                // Additional platform-specific validations can be added here
                // For example, checking image requirements, etc.
            } catch (\Exception $e) {
                $fail($e->getMessage());
            }
        }
    }
}
