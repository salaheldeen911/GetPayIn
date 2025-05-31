<?php

namespace App\Enums;

enum PlatformType: string
{
    case TWITTER = 'twitter';
    case INSTAGRAM = 'instagram';
    case LINKEDIN = 'linkedin';
    case FACEBOOK = 'facebook';

    public function getMaxLength(): int
    {
        return match ($this) {
            self::TWITTER => 280,
            self::INSTAGRAM => 2200,
            self::LINKEDIN => 3000,
            self::FACEBOOK => 63206,
        };
    }

    public function getRequirements(): array
    {
        $requirements = [
            'max_length' => $this->getMaxLength(),
        ];

        // Add platform-specific requirements
        return match ($this) {
            self::INSTAGRAM => array_merge($requirements, [
                'requires_image' => true,
                'aspect_ratios' => ['1:1', '4:5', '16:9'],
                'max_image_size' => 8192, // 8MB
            ]),
            self::TWITTER => array_merge($requirements, [
                'max_images' => 4,
                'max_image_size' => 5120, // 5MB per image
            ]),
            self::LINKEDIN => array_merge($requirements, [
                'max_image_size' => 10240, // 10MB
            ]),
            self::FACEBOOK => array_merge($requirements, [
                'max_image_size' => 10240, // 10MB
            ]),
        };
    }

    public function getDisplayName(): string
    {
        return match ($this) {
            self::TWITTER => 'Twitter',
            self::INSTAGRAM => 'Instagram',
            self::LINKEDIN => 'LinkedIn',
            self::FACEBOOK => 'Facebook',
        };
    }
}
