<?php

namespace App\Services;

use App\Models\Platform;
use App\Models\Post;
use App\Services\Publishing\PublishingStrategyFactory;
use Illuminate\Support\Facades\Cache;

class PlatformPublisher
{
    public function __construct(
        protected PublishingStrategyFactory $strategyFactory
    ) {}

    public function publish(Post $post, Platform $platform): array
    {
        $key = "post_{$post->id}_platform_{$platform->id}";

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $strategy = $this->strategyFactory->createStrategy($platform);

        $result = $strategy->publish($post);

        Cache::put($key, $result, now()->addHours(24));

        return $result;
    }
}
