<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ProcessScheduledPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $posts = Post::query()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->with('platforms')
            ->get();

        $jobs = $posts->map(function ($post) {
            return new PublishPostToPlatforms($post);
        })->toArray();

        if (empty($jobs)) {
            return;
        }

        try {
            Bus::batch($jobs)
                ->allowFailures()
                ->onQueue('posts')
                ->name('Process Scheduled Posts - '.now()->toDateTimeString())
                ->dispatch();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
