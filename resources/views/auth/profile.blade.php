@extends('layouts.app')

@section('content')
<div class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z\' fill=\'%234338ca\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');"></div>
    </div>

    <div class="relative flex items-center justify-center min-h-full py-16">
        <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md mx-4">
            <div class="flex items-center justify-center mb-6">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-center text-gray-900 mb-2">Profile Settings</h2>
            <p class="text-center text-gray-600 mb-6">Update your personal information</p>
              <form action="{{ route('profile') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ auth()->user()->name }}"
                           class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                           required>
                    @error('name')
                        <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ auth()->user()->email }}"
                           class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                           required>
                    @error('email')
                        <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-all transform hover:-translate-y-0.5">
                    Update Profile
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
