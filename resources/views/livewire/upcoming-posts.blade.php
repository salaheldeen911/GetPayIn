<div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">Upcoming Posts</h3>
        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            New Post
        </a>
    </div>
    <div class="space-y-4">
        @forelse($posts as $post)
            <a href="{{ route('posts.show', $post) }}" class="block">
                <div wire:key="post-{{ $post->id }}" class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                    <div class="flex flex-col sm:flex-row">
                        @if($post->image_url)
                            <div class="sm:w-48 sm:h-auto">
                                <img src="{{ asset($post->image_url) }}" alt="{{ $post->title }}" 
                                    class="w-full h-48 sm:h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1 p-4">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->status === App\Enums\PostStatus::PUBLISHED ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $post->scheduled_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $post->title }}</h4>
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ Str::limit($post->content, 150) }}</p>
                                    
                                    @if($post->platforms->isNotEmpty())
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach($post->platforms as $platform)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $platform->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-auto pt-3 border-t">
                                <a href="{{ route('posts.edit', $post) }}" 
                                    class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <button wire:click="deletePost({{ $post->id }})" 
                                        wire:confirm="Are you sure you want to delete this post?" 
                                        class="inline-flex items-center text-sm text-red-600 hover:text-red-900">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-4">
                <p class="text-gray-500">No upcoming posts scheduled.</p>
                <a href="{{ route('posts.create') }}" class="mt-2 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900">
                    Create your first post
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        @endforelse
    </div>
    @if($hasMorePosts)
        <div class="mt-4 text-center">
            <a href="{{ route('posts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all posts</a>
        </div>
    @endif
</div>