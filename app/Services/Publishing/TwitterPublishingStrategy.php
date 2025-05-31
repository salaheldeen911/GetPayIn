<?php

namespace App\Services\Publishing;

use App\Models\Post;

class TwitterPublishingStrategy extends AbstractPublishingStrategy
{
    public function publish(Post $post): array
    {
        if (! $this->validateContent($post)) {
            throw new \Exception('Content validation failed for Twitter');
        }

        // Mock API call
        sleep(1);

        return $this->generateMockResponse();
    }

    public function getMaxContentLength(): int
    {
        return 280;
    }

    protected function getMockUrl(): string
    {
        return 'https://twitter.com/user/status/'.uniqid();
    }
}
