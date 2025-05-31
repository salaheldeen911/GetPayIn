<?php

namespace App\Livewire;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpcomingPosts extends Component
{
    public function deletePost($postId)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($postId);
        $post->delete();

        session()->flash('status', 'Post deleted successfully.');
    }

    public function render()
    {
        $posts = Post::where('user_id', Auth::id())
            ->where('status', PostStatus::SCHEDULED)
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $hasMorePosts = Post::where('user_id', Auth::id())
            ->where('status', PostStatus::SCHEDULED)
            ->count() > 5;

        return view('livewire.upcoming-posts', [
            'posts' => $posts,
            'hasMorePosts' => $hasMorePosts,
        ]);
    }
}
