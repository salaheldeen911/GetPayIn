@extends('layouts.app')

@section('title', 'View Post')

@section('content')

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
                    <div class="flex items-center space-x-4">
                        @if($post->status !== 'published')
                            <a href="{{ route('posts.edit', $post) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Edit Post
                            </a>
                            
                            <form action="{{ route('posts.schedule', $post) }}" method="POST" class="inline" onsubmit="return confirm('This will schedule the post to be published in 1 minute. Continue?');">
                                @csrf
                                <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Schedule Now
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('posts.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Back to Posts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-4">
                <!-- Status Badge -->
                <div class="mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($post->status === 'published') bg-green-100 text-green-800
                        @elseif($post->status === 'scheduled') bg-blue-100 text-blue-800
                        @elseif($post->status === 'failed') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($post->status) }}
                    </span>
                    @if($post->scheduled_at)
                        <span class="ml-2 text-sm text-gray-500">
                            Scheduled for {{ $post->scheduled_at->format('M j, Y g:i A') }}
                        </span>
                    @endif
                </div>

                <!-- Content -->
                <div class="prose max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Image -->
                @if($post->image_url)
                    <div class="mt-6">
                        <img src="{{ asset($post->image_url) }}" 
                             alt="Post image" 
                             class="max-w-full h-auto rounded-lg shadow-sm">
                    </div>
                @endif

                <!-- Platforms -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Selected Platforms</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($post->platforms as $platform)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ ucfirst($platform->name) }}</h4>
                                    <p class="text-sm text-gray-500">
                                        Status: 
                                        <span class="@if($platform->pivot->platform_status === 'published') text-green-600
                                                    @elseif($platform->pivot->platform_status === 'failed') text-red-600
                                                    @else text-blue-600
                                                    @endif">
                                            {{ ucfirst($platform->pivot->platform_status) }}
                                        </span>
                                    </p>
                                    @if($platform->pivot->platform_post_id)
                                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900">View on {{ ucfirst($platform->name) }}</a>
                                    @endif
                                    @if($platform->pivot->error_message)
                                        <p class="mt-1 text-sm text-red-600">{{ $platform->pivot->error_message }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    Created {{ $post->created_at->diffForHumans() }}
                    @if($post->updated_at->ne($post->created_at))
                        · Updated {{ $post->updated_at->diffForHumans() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection