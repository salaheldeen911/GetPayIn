@extends('layouts.app')

@section('title', 'Welcome to GetPayIn')

@section('content')

    <div class="min-h-screen bg-gradient-to-br from-indigo-100 to-blue-100">
        <div class="relative flex items-center justify-center min-h-full py-16">
            <div class="max-w-4xl px-6 mx-auto text-center">
                <div class="mb-12">
                    <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Welcome to <span class="text-blue-600 relative">
                            GetPayIn
                            <span class="absolute bottom-0 left-0 w-full h-2 bg-blue-200 opacity-50 rounded"></span>
                        </span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Your comprehensive platform for managing social media posts across multiple platforms seamlessly.
                    </p>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Easy Post Creation</h3>
                        <p class="text-gray-600">Create and schedule posts for multiple platforms in one place.</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Smart Scheduling</h3>
                        <p class="text-gray-600">Schedule your posts for the perfect time on each platform.</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Analytics & Insights</h3>
                        <p class="text-gray-600">Track your social media performance across all platforms.</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="space-x-4">
                    @guest
                        <a href="{{ route('login') }}" wire:navigate
                           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transform hover:-translate-y-0.5 transition-all duration-200">
                            Get Started
                        </a>
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-500 focus:ring-opacity-50 transform hover:-translate-y-0.5 transition-all duration-200 border-2 border-blue-600">
                            Create Account
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" wire:navigate
                           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transform hover:-translate-y-0.5 transition-all duration-200">
                            Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection
