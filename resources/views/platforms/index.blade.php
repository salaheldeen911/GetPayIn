@extends('layouts.app')

@section('title', 'Platforms')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Manage Platforms</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($platforms as $platform)            <div class="bg-white overflow-hidden shadow-sm rounded-lg border {{ $platform->is_active ? 'border-green-500' : 'border-gray-200' }}">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $platform->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ ucfirst($platform->type->value) }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $platform->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $platform->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-900">Requirements:</h4>
                        <ul class="mt-2 space-y-1 text-sm text-gray-500">
                            @if($platform->getRequirement('max_length'))
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Max Length: {{ $platform->getRequirement('max_length') }} characters
                                </li>
                            @endif
                            @if($platform->getRequirement('image_formats'))
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Image Formats: {{ implode(', ', $platform->getRequirement('image_formats')) }}
                                </li>
                            @endif
                            @if($platform->getRequirement('max_images'))
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                                    </svg>
                                    Max Images: {{ $platform->getRequirement('max_images') }}
                                </li>
                            @endif
                            @if($platform->getRequirement('aspect_ratios'))
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                    </svg>
                                    Aspect Ratios: {{ implode(', ', $platform->getRequirement('aspect_ratios')) }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="mt-6 space-y-3">
                        @if($platform->is_connected)
                            <form action="{{ route('platforms.toggle-active', $platform) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium transition-colors duration-200 {{ $platform->is_active ? 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100' : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' }}">
                                    {{ $platform->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('platforms.disconnect', $platform) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    Disconnect
                                </button>
                            </form>
                        @else
                            <form action="{{ route('platforms.connect', $platform) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-indigo-300 rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors duration-200">
                                    Connect
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection