<?php

namespace App\Console\Commands;

use App\Enums\PostStatus;
use App\Jobs\PublishScheduledPost;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledPosts extends Command
{
    protected $signature = 'posts:process-scheduled';

    protected $description = 'Process all scheduled posts that are due for publication';

    public function handle()
    {
        $this->info('Processing scheduled posts...');

        $posts = Post::where('status', PostStatus::SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->get();

        $count = $posts->count();
        $this->info("Found {$count} posts to process");

        foreach ($posts as $post) {
            try {
                PublishScheduledPost::dispatch($post);
                $this->info("Dispatched job for post ID: {$post->id}");
            } catch (\Exception $e) {
                $this->error("Failed to process post ID: {$post->id}");
                Log::error('Failed to process scheduled post', [
                    'post_id' => $post->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info('Finished processing scheduled posts');
    }
}
