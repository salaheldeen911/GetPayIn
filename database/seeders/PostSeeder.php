<?php

namespace Database\Seeders;

use App\Enums\PlatformStatus;
use App\Enums\PostStatus;
use App\Models\Platform;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $platforms = Platform::all();

        foreach ($users as $user) {
            // Create some sample posts for each user
            $posts = [
                [
                    'title' => 'Welcome Post',
                    'content' => 'Welcome to our new social media platform! #FirstPost #Excited',
                    'status' => PostStatus::PUBLISHED->value,
                    'scheduled_at' => now(),
                ],
                [
                    'title' => 'Upcoming Feature Announcement',
                    'content' => 'Stay tuned for exciting new features coming next week! #Innovation #SocialMedia',
                    'status' => PostStatus::SCHEDULED->value,
                    'scheduled_at' => now()->addDays(2),
                ],
                [
                    'title' => 'Draft Campaign',
                    'content' => 'This is a draft post for our upcoming marketing campaign. #Marketing #Draft',
                    'status' => PostStatus::DRAFT->value,
                    'scheduled_at' => now()->addWeek(),
                ],
            ];

            foreach ($posts as $postData) {
                $post = Post::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'title' => $postData['title'],
                    ],
                    [
                        'content' => $postData['content'],
                        'status' => $postData['status'],
                        'scheduled_at' => $postData['scheduled_at'],
                        'image_url' => null, // You could add sample image URLs here if needed
                    ]
                );

                // Randomly associate 1-3 platforms with each post
                $randomPlatforms = $platforms->random(rand(1, 3));
                $platformSync = [];
                foreach ($randomPlatforms as $platform) {
                    $platformSync[$platform->id] = ['platform_status' => PlatformStatus::PENDING->value];
                }
                $post->platforms()->sync($platformSync);
            }
        }

        // Create some additional random posts
        Post::factory()
            ->count(10)
            ->create()
            ->each(function ($post) use ($platforms) {
                $randomPlatforms = $platforms->random(rand(1, 3));
                $platformSync = [];
                foreach ($randomPlatforms as $platform) {
                    $platformSync[$platform->id] = ['platform_status' => 'pending'];
                }
                $post->platforms()->sync($platformSync);
            });
    }
}
