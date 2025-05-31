<?php

namespace App\Services\Publishing;

use App\Models\Post;

class InstagramPublishingStrategy extends AbstractPublishingStrategy
{
    public function publish(Post $post): array
    {
        if (! $this->validateContent($post)) {
            throw new \Exception('Content validation failed for Instagram');
        }

        if (! $post->hasMedia()) {
            throw new \Exception('Instagram posts require an image');
        }

        // Mock API call
        sleep(1);

        return $this->generateMockResponse();
    }

    public function getMaxContentLength(): int
    {
        return 2200;
    }

    protected function getMockUrl(): string
    {
        return 'https://instagram.com/p/'.uniqid();
    }
}
