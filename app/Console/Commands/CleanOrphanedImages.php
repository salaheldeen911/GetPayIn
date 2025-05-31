<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOrphanedImages extends Command
{
    protected $signature = 'images:clean';

    protected $description = 'Clean up orphaned images from storage';

    public function handle()
    {
        $this->info('Starting cleanup of orphaned images...');

        // Get all images in storage
        $files = Storage::disk('public')->files('posts');
        $storedImages = collect($files);

        // Get all image URLs from posts
        $usedImages = Post::whereNotNull('image_url')->pluck('image_url')
            ->map(function ($url) {
                return str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            });

        // Find orphaned images
        $orphanedImages = $storedImages->filter(function ($file) use ($usedImages) {
            return ! $usedImages->contains($file);
        });

        // Delete orphaned images
        $count = 0;
        foreach ($orphanedImages as $image) {
            Storage::disk('public')->delete($image);
            $this->info("Deleted: {$image}");
            $count++;
        }

        $this->info("Cleanup complete. Deleted {$count} orphaned images.");
    }
}
