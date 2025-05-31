<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function index(Request $request)
    {
        $posts = $this->postService->getPaginatedPosts(Auth::user(), [
            'status' => $request->status,
            'date' => $request->date,
        ]);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return view('posts.create', [
            'platforms' => Auth::user()->activePlatforms,
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $post = $this->postService->createPost(
                Auth::user(),
                $request->validated(),
                $request->hasFile('image') ? $request->file('image') : null
            );

            return redirect()
                ->route('posts.show', $post)
                ->with('success', 'Post '.($post->status === 'scheduled' ? 'scheduled' : 'saved').' successfully.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to create post');

            return back()
                ->withInput()
                ->with('error', 'Failed to create post. '.$e->getMessage());
        }
    }

    public function schedule(Post $post)
    {
        $this->authorize('update', $post);

        try {
            $this->postService->schedulePost($post);

            return redirect()
                ->route('posts.index')
                ->with('success', 'Post has been scheduled and will be published in 1 minute.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to schedule post');

            return back()->with('error', 'Failed to schedule post. '.$e->getMessage());
        }
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return view('posts.show', [
            'post' => new PostResource($post->load('platforms')),
        ]);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', [
            'post' => $post->load('platforms'),
            'platforms' => Auth::user()->activePlatforms,
        ]);
    }

    public function update(CreatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        if ($post->status === 'published') {
            return back()->with('error', 'Cannot update a published post');
        }

        try {
            $this->postService->updatePost(
                $post,
                $request->validated(),
                $request->hasFile('image') ? $request->file('image') : null,
                (bool) $request->get('remove_image')
            );

            return redirect()
                ->route('posts.show', $post)
                ->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to update post');

            return back()->with('error', 'Failed to update post. '.$e->getMessage());
        }
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        try {
            $this->postService->deletePost($post);

            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to delete post');

            return back()->with('error', 'Failed to delete post. '.$e->getMessage());
        }
    }
}
