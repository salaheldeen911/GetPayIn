<?php

namespace App\Livewire;

use App\Models\Platform;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlatformStatus extends Component
{
    public function toggleActive($platformId)
    {
        $platform = Platform::findOrFail($platformId);
        $user = Auth::user();

        $isActive = $user->platforms()->where('platform_id', $platformId)->value('is_active');
        $user->platforms()->updateExistingPivot($platformId, ['is_active' => ! $isActive]);

        session()->flash('status', $isActive ? 'Platform deactivated.' : 'Platform activated.');
    }

    public function disconnect($platformId)
    {
        $platform = Platform::findOrFail($platformId);
        $user = Auth::user();

        $user->platforms()->detach($platformId);

        session()->flash('status', 'Platform disconnected successfully.');
    }

    public function render()
    {
        $platforms = Auth::user()->platforms()
            ->select('platforms.*', 'user_platform.is_active', 'user_platform.token_expires_at')
            ->get();

        return view('livewire.platform-status', [
            'platforms' => $platforms,
        ]);
    }
}
