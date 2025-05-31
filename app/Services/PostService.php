<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Helpers\ImageHelper;
use App\Models\Post;
use App\Models\User;
use App\Services\Publishing\PublishingStrategyFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function __construct(
        protected PlatformPublisher $publisher,
        protected PublishingStrategyFactory $strategyFactory
    ) {}

    public function getPaginatedPosts(User $user, array $filters = [])
    {
        return Post::query()
            ->where('user_id', $user->id)
            ->with('platforms')
            ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(
                isset($filters['date']),
                fn ($q) => $q->whereDate('scheduled_at', $filters['date'])
            )
            ->latest()
            ->paginate(10);
    }

    public function createPost(User $user, array $data, ?UploadedFile $image = null): Post
    {
        return DB::transaction(function () use ($user, $data, $image) {
            try {
                $post = $user->posts()->create($data);

                if ($image) {
                    Log::info('Attempting to store image', [
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                    ]);

                    $path = ImageHelper::store($image, 'posts');
                    Log::info('Image stored successfully', ['path' => $path]);

                    // Store the path without 'storage/' prefix as it will be added by asset() helper
                    $post->update(['image_url' => $path]);
                }

                $post->platforms()->attach($data['platforms'], [
                    'platform_status' => 'pending',
                ]);

                return $post;
            } catch (\Exception $e) {
                Log::error('Failed to create post with image', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }
        });
    }

    public function updatePost(Post $post, array $data, ?UploadedFile $image = null, bool $removeImage = false): Post
    {
        return DB::transaction(function () use ($post, $data, $image, $removeImage) {
            try {
                // Handle image removal
                if ($removeImage && $post->image_url) {
                    Log::info('Removing existing image', ['path' => $post->image_url]);
                    ImageHelper::delete($post->image_url);
                    $post->update(['image_url' => null]);
                }

                // Handle new image upload
                if ($image) {
                    Log::info('Attempting to update image', [
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                    ]);

                    // Delete old image if exists
                    if ($post->image_url) {
                        Log::info('Deleting old image', ['path' => $post->image_url]);
                        ImageHelper::delete($post->image_url);
                    }

                    // Store new image and get path with storage/ prefix
                    $path = ImageHelper::store($image, 'posts');
                    Log::info('New image stored successfully', ['path' => $path]);

                    // The path from ImageHelper::store now includes the storage/ prefix
                    $data['image_url'] = $path;
                }

                // Remove these fields from the data as they're handled separately
                unset($data['image']);
                unset($data['remove_image']);

                $post->update($data);

                // Sync platforms
                if (isset($data['platforms'])) {
                    $post->platforms()->sync(array_fill_keys($data['platforms'], [
                        'platform_status' => 'pending',
                    ]));
                }

                return $post;
            } catch (\Exception $e) {
                Log::error('Failed to update post with image', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'post_id' => $post->id,
                ]);
                throw $e;
            }
        });
    }

    public function schedulePost(Post $post): Post
    {
        if ($post->status === PostStatus::PUBLISHED->value) {
            throw new \Exception('Cannot schedule a published post.');
        }

        return DB::transaction(function () use ($post) {
            $post->update([
                'status' => PostStatus::SCHEDULED->value,
                'scheduled_at' => now()->addSeconds(1),
            ]);

            // Reset platform statuses to pending
            $post->platforms()->updateExistingPivot($post->platforms->pluck('id'), [
                'platform_status' => 'pending',
                'platform_post_id' => null,
                'error_message' => null,
            ]);

            return $post;
        });
    }

    public function deletePost(Post $post): void
    {
        if ($post->status === PostStatus::PUBLISHED->value) {
            throw new \Exception('Cannot delete a published post');
        }

        if ($post->image_url) {
            ImageHelper::delete($post->image_url);
        }

        $post->delete();
    }
}
