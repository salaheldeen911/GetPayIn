@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Posts</h1>

    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Posts</h1>
        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Post
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Filters Section -->
            <div class="p-6 border-b border-gray-200">
                <form action="{{ route('posts.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
                    <!-- Status Filter -->
                    <div class="flex-1">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="this.form.submit()">
                            <option value="">All Status</option>                            <option value="{{ App\Enums\PostStatus::DRAFT->value }}" {{ request('status') === App\Enums\PostStatus::DRAFT->value ? 'selected' : '' }}>Draft</option>
                            <option value="{{ App\Enums\PostStatus::SCHEDULED->value }}" {{ request('status') === App\Enums\PostStatus::SCHEDULED->value ? 'selected' : '' }}>Scheduled</option>
                            <option value="{{ App\Enums\PostStatus::PUBLISHED->value }}" {{ request('status') === App\Enums\PostStatus::PUBLISHED->value ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="flex-1">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}" 
                               class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                               onchange="this.form.submit()">
                    </div>

                    <!-- Clear Filters -->
                    @if(request()->hasAny(['status', 'date']))
                        <div class="flex-none self-end">
                            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Posts List -->
            <div class="divide-y divide-gray-200">
                @forelse($posts as $post)
                    <div class="p-6 flex items-center space-x-4">
                        @if($post->image_url)
                        <div class="flex-shrink-0">
                            <img src="{{ asset($post->image_url) }}" alt="{{ $post->title }}" class="h-16 w-16 object-cover rounded-lg">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $post->title }}
                            </h3>
                            <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $post->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $post->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($post->status) }}
                                </span>

                                <!-- Scheduled Time -->
                                @if($post->scheduled_at)
                                    <span>•</span>
                                    <span>{{ $post->scheduled_at->format('M j, Y g:i A') }}</span>
                                @endif

                                <!-- Platforms -->
                                @if($post->platforms->isNotEmpty())
                                    <span>•</span>
                                    <span class="flex items-center space-x-1">
                                        @foreach($post->platforms as $platform)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $platform->name }}
                                            </span>
                                        @endforeach
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Edit
                            </a>
                            <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Show
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')" 
                                        class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No posts found. 
                        <a href="{{ route('posts.create') }}" class="text-blue-600 hover:text-blue-800">Create your first post</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $posts->withQueryString()->links() }}
                </div>
            @endif        </div>
@endsection