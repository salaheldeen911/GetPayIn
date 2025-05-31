<div>
    <!-- General Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Posts -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Posts</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['totalPosts'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduled Posts -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Scheduled Posts</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['scheduledPosts'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Published Posts -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Published Posts</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['publishedPosts'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Success Rate</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $stats['successRate'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Stats -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Platform Analytics</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($platformStats as $platform)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">                            <h4 class="font-medium text-gray-900">{{ $platform->name }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $platform->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $platform->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-500">Total Posts</p>
                                <p class="text-lg font-semibold">{{ $platform->posts_count }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Published</p>
                                <p class="text-lg font-semibold">{{ $platform->published_count }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Scheduled</p>
                                <p class="text-lg font-semibold">{{ $platform->scheduled_count }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Success Rate</p>
                                <p class="text-lg font-semibold">{{ $platform->published_count > 0 ? round(($platform->success_count / $platform->published_count) * 100) : 0 }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>