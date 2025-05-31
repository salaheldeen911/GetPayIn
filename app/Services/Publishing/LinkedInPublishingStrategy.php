<?php

namespace App\Services\Publishing;

use App\Models\Post;

class LinkedInPublishingStrategy extends AbstractPublishingStrategy
{
    public function publish(Post $post): array
    {
        if (! $this->validateContent($post)) {
            throw new \Exception('Content validation failed for LinkedIn');
        }

        // Mock API call
        sleep(1);

        return $this->generateMockResponse();
    }

    public function getMaxContentLength(): int
    {
        return 3000;
    }

    protected function getMockUrl(): string
    {
        return 'https://linkedin.com/posts/'.uniqid();
    }
}
