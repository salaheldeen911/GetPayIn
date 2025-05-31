<?php

namespace App\Services;

use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PlatformService
{
    public function getPlatformsWithStatus(User $user): Collection
    {
        return Platform::query()
            ->leftJoin('user_platform', function ($join) use ($user) {
                $join->on('platforms.id', '=', 'user_platform.platform_id')
                    ->where('user_platform.user_id', '=', $user->id);
            })
            ->select([
                'platforms.*',
                'user_platform.is_active',
                'user_platform.user_id as is_connected',
            ])
            ->get();
    }

    public function connect(User $user, Platform $platform): void
    {
        if ($user->platforms()->where('platform_id', $platform->id)->exists()) {
            throw new \Exception('Platform already connected.');
        }

        $user->platforms()->attach($platform->id, [
            'access_token' => 'mock_token',
            'refresh_token' => 'mock_refresh_token',
            'token_expires_at' => now()->addDays(60),
            'is_active' => true,
        ]);
    }

    public function disconnect(User $user, Platform $platform): void
    {
        if (! $user->platforms()->where('platform_id', $platform->id)->exists()) {
            throw new \Exception('Platform not connected.');
        }

        $user->platforms()->detach($platform->id);
    }

    public function toggleActive(User $user, Platform $platform): bool
    {
        $userPlatform = $user->platforms()
            ->where('platform_id', $platform->id)
            ->first();

        if (! $userPlatform) {
            throw new \Exception('Platform not connected.');
        }

        $isActive = ! $userPlatform->pivot->is_active;

        $user->platforms()->updateExistingPivot($platform->id, [
            'is_active' => $isActive,
        ]);

        return $isActive;
    }
}
