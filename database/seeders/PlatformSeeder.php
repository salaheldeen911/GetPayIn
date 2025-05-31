<?php

namespace Database\Seeders;

use App\Enums\PlatformType;
use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            [
                'name' => 'Twitter',
                'type' => PlatformType::TWITTER->value,
                'requirements' => [
                    'max_length' => 280,
                    'image_formats' => ['jpg', 'jpeg', 'png', 'gif'],
                    'max_images' => 4,
                ],
            ],
            [
                'name' => 'Instagram',
                'type' => PlatformType::INSTAGRAM->value,
                'requirements' => [
                    'max_length' => 2200,
                    'image_formats' => ['jpg', 'jpeg', 'png'],
                    'aspect_ratios' => ['1:1', '4:5', '16:9'],
                    'requires_image' => true,
                ],
            ],
            [
                'name' => 'LinkedIn',
                'type' => PlatformType::LINKEDIN->value,
                'requirements' => [
                    'max_length' => 3000,
                    'image_formats' => ['jpg', 'jpeg', 'png'],
                    'max_images' => 9,
                ],
            ],
            [
                'name' => 'Facebook',
                'type' => PlatformType::FACEBOOK->value,
                'requirements' => [
                    'max_length' => 63206,
                    'image_formats' => ['jpg', 'jpeg', 'png', 'gif'],
                    'max_images' => 10,
                ],
            ],
        ];

        foreach ($platforms as $platformData) {
            Platform::create($platformData);
        }
    }
}
