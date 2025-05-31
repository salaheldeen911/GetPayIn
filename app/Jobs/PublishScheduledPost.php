<?php

namespace App\Jobs;

use App\Enums\PlatformStatus;
use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishScheduledPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        try {
            // Mock publishing to different platforms
            foreach ($this->post->platforms as $platform) {
                $this->mockPublishToPlatform($platform);
            }

            $this->post->update([
                'status' => PostStatus::PUBLISHED,
                'publish_error' => null,
            ]);

            Log::info('Post published successfully', ['post_id' => $this->post->id]);
        } catch (\Exception $e) {
            $this->post->update([
                'status' => PostStatus::FAILED,
                'publish_error' => $e->getMessage(),
            ]);

            Log::error('Failed to publish post', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function mockPublishToPlatform($platform)
    {
        // Validate platform requirements
        $this->validatePlatformRequirements($platform);

        // Simulate random success/failure
        if (rand(0, 10) > 2) { // 80% success rate
            $platform->pivot->update([
                'platform_status' => PlatformStatus::PUBLISHED,
                'platform_post_id' => 'mock_'.uniqid(),
                'error_message' => null,
            ]);
        } else {
            $platform->pivot->update([
                'platform_status' => PlatformStatus::FAILED,
                'error_message' => 'Mock publishing failure',
            ]);
            throw new \Exception("Failed to publish to {$platform->name}");
        }
    }

    protected function validatePlatformRequirements($platform)
    {
        $errors = [];

        // Check content length
        $maxLength = (int) $platform->getRequirement('max_length');
        if (strlen($this->post->content) > $maxLength) {
            $errors[] = "Content exceeds maximum length for {$platform->name}";
        }

        // Check image requirement for Instagram
        if ($platform->type->value === 'instagram' && ! $this->post->hasMedia()) {
            $errors[] = "{$platform->name} requires at least one image";
        }

        // Check max images
        if ($maxImages = $platform->getRequirement('max_images')) {
            // In a real app, you would check the actual number of images here
            // For now, we'll just check if there's an image when max is 0
            if ($maxImages === 0 && $this->post->hasMedia()) {
                $errors[] = "{$platform->name} does not support images";
            }
        }

        if (! empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
    }
}
