<?php

namespace App\Services\Publishing;

use App\Contracts\PublishingStrategy;
use App\Models\Platform;
use App\Models\Post;

abstract class AbstractPublishingStrategy implements PublishingStrategy
{
    protected Platform $platform;

    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    public function validateContent(Post $post): bool
    {
        if (mb_strlen($post->content) > $this->getMaxContentLength()) {
            return false;
        }

        if ($post->hasMedia() && ! $this->isMediaSupported($post)) {
            return false;
        }

        return true;
    }

    public function getMaxContentLength(): int
    {
        return (int) $this->platform->getRequirement('max_length');
    }

    public function getSupportedMediaTypes(): array
    {
        return $this->platform->getRequirement('image_formats') ?? ['jpg', 'jpeg', 'png'];
    }

    protected function isMediaSupported(Post $post): bool
    {
        if (! $post->image_url) {
            return true;
        }

        $extension = strtolower(pathinfo($post->image_url, PATHINFO_EXTENSION));

        return in_array($extension, $this->getSupportedMediaTypes());
    }

    protected function generateMockResponse(): array
    {
        return [
            'post_id' => uniqid(static::class.'_'),
            'url' => $this->getMockUrl(),
        ];
    }

    abstract protected function getMockUrl(): string;
}
