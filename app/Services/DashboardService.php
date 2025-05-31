<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    public function getStats(User $user): array
    {
        return [
            'totalPosts' => $user->posts()->count(),
            'scheduledPosts' => $user->posts()->where('scheduled_at', '>', now())->count(),
            'platformsConnected' => $user->platforms()->count(),
        ];
    }

    public function getDefaultStats(): array
    {
        return [
            'totalPosts' => 0,
            'scheduledPosts' => 0,
            'platformsConnected' => 0,
        ];
    }
}
