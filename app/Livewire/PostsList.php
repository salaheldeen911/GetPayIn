<?php

namespace App\Livewire;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PostsList extends Component
{
    use WithPagination;

    public $status = '';

    public $date = '';

    public $search = '';

    public $perPage = 10;

    protected $queryString = [
        'status' => ['except' => ''],
        'date' => ['except' => ''],
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Post::query()
            ->where('user_id', Auth::id())
            ->when($this->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($this->date, function ($query, $date) {
                $query->whereDate('created_at', $date);
            })
            ->latest();

        $posts = $query->paginate($this->perPage);

        return view('livewire.posts-list', [
            'posts' => $posts,
            'statusOptions' => [
                PostStatus::DRAFT->value => 'Draft',
                PostStatus::SCHEDULED->value => 'Scheduled',
                PostStatus::PUBLISHED->value => 'Published',
            ],
        ]);
    }

    public function deletePost(Post $post)
    {
        if ($post->status !== PostStatus::PUBLISHED && Auth::user()->can('delete', $post)) {
            $post->delete();
            session()->flash('status', 'Post deleted successfully.');
        } else {
            session()->flash('error', 'Cannot delete published posts.');
        }
    }
}
