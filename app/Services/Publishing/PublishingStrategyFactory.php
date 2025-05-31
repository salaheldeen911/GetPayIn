<?php

namespace App\Services\Publishing;

use App\Models\Platform;
use InvalidArgumentException;

class PublishingStrategyFactory
{
    public function createStrategy(Platform $platform): AbstractPublishingStrategy
    {
        return match ($platform->type->value) {
            'twitter' => new TwitterPublishingStrategy($platform),
            'facebook' => new FacebookPublishingStrategy($platform),
            'instagram' => new InstagramPublishingStrategy($platform),
            'linkedin' => new LinkedInPublishingStrategy($platform),
            default => throw new InvalidArgumentException("Unsupported platform type: {$platform->type}"),
        };
    }
}
