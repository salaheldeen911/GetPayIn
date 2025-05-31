<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\PlatformPublisher;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class PublishPostToPlatforms implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Post $post
    ) {}

    public function handle(PlatformPublisher $publisher): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        foreach ($this->post->platforms as $platform) {
            try {
                $result = $publisher->publish($this->post, $platform);

                $this->post->platforms()->updateExistingPivot($platform->id, [
                    'platform_status' => 'published',
                    'platform_post_id' => $result['post_id'] ?? null,
                    'error_message' => null,
                ]);
            } catch (Throwable $e) {
                report($e);

                $this->post->platforms()->updateExistingPivot($platform->id, [
                    'platform_status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        // Update post status if all platforms are processed
        $allPublished = $this->post->platforms()
            ->wherePivot('platform_status', 'published')
            ->count() === $this->post->platforms()->count();

        if ($allPublished) {
            $this->post->markAsPublished();
        } else {
            $this->post->markAsFailed('Failed to publish to one or more platforms');
        }
    }
}
