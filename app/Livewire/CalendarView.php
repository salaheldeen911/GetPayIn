<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CalendarView extends Component
{
    public function getEvents()
    {
        return Post::where('user_id', Auth::id())
            ->where('status', 'scheduled')
            ->get()
            ->map(function ($post) {
                return [
                    'title' => $post->title,
                    'start' => $post->scheduled_at,
                    'url' => route('posts.edit', $post),
                    'backgroundColor' => $post->status === 'published' ? '#34D399' : '#60A5FA',
                ];
            });
    }

    public function render()
    {
        return view('livewire.calendar-view', [
            'events' => $this->getEvents(),
        ]);
    }
}
