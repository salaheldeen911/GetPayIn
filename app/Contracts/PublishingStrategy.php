<?php

namespace App\Contracts;

use App\Models\Post;

interface PublishingStrategy
{
    /**
     * Publish a post to a specific platform
     *
     * @return array Returns an array containing post_id and url
     *
     * @throws \Exception If publishing fails
     */
    public function publish(Post $post): array;

    /**
     * Check if the content is valid for the platform
     */
    public function validateContent(Post $post): bool;

    /**
     * Get the maximum content length for the platform
     */
    public function getMaxContentLength(): int;

    /**
     * Get supported media types
     */
    public function getSupportedMediaTypes(): array;
}
