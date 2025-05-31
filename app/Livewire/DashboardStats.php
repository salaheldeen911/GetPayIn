<?php

namespace App\Livewire;

use App\Enums\PostStatus;
use App\Models\Platform;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Get general stats
        $stats = [
            'totalPosts' => Post::where('user_id', $user->id)->count(),
            'scheduledPosts' => Post::where('user_id', $user->id)
                ->where('status', PostStatus::SCHEDULED)
                ->count(),
            'publishedPosts' => Post::where('user_id', $user->id)
                ->where('status', PostStatus::PUBLISHED)
                ->count(),
        ];

        // Calculate success rate
        if ($stats['publishedPosts'] > 0) {
            $successfulPosts = Post::where('user_id', $user->id)
                ->where('status', PostStatus::PUBLISHED)
                ->whereNull('publish_error')
                ->count();
            $stats['successRate'] = round(($successfulPosts / $stats['publishedPosts']) * 100);
        } else {
            $stats['successRate'] = 0;
        }

        // Get per-platform stats with pivot data
        $platformStats = Platform::query()
            ->join('user_platform', 'platforms.id', '=', 'user_platform.platform_id')
            ->where('user_platform.user_id', $user->id)
            ->select([
                'platforms.*',
                'user_platform.is_active',
                DB::raw('(select count(*) from posts inner join post_platform on posts.id = post_platform.post_id where platforms.id = post_platform.platform_id and user_id = '.$user->id.') as posts_count'),
                DB::raw('(select count(*) from posts inner join post_platform on posts.id = post_platform.post_id where platforms.id = post_platform.platform_id and user_id = '.$user->id.' and status = "published") as published_count'),
                DB::raw('(select count(*) from posts inner join post_platform on posts.id = post_platform.post_id where platforms.id = post_platform.platform_id and user_id = '.$user->id.' and status = "scheduled") as scheduled_count'),
                DB::raw('(select count(*) from posts inner join post_platform on posts.id = post_platform.post_id where platforms.id = post_platform.platform_id and user_id = '.$user->id.' and status = "published" and publish_error is null) as success_count'),
            ])
            ->get();

        $stats['platformsConnected'] = $platformStats->count();

        return view('livewire.dashboard-stats', [
            'stats' => $stats,
            'platformStats' => $platformStats,
        ]);
    }
}
