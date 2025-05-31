@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
            <p class="mt-2 text-gray-600">Create and schedule your post for multiple platforms.</p>
        </div>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Content Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title"
                            class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-200 ease-in-out"
                            placeholder="Enter post title"
                            value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium leading-6 text-gray-900">Content</label>
                    <div class="mt-2">
                        <textarea name="content" id="content" rows="6"
                            class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-200 ease-in-out resize-none"
                            placeholder="Write your post content here..."
                            required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-2 flex justify-between text-sm text-gray-500">
                        <span>Character count: <span id="charCount" class="font-medium">0</span></span>
                        <span id="platformLimit" class="font-medium"></span>
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Image</label>
                    <div class="mt-2 flex items-center">
                        <div class="flex-1">
                            <input type="file" name="image" id="image" accept="image/*"
                                class="block w-full text-sm text-gray-500 
                                file:mr-4 file:py-2.5 file:px-4 
                                file:rounded-lg file:border-0 
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700 
                                hover:file:bg-indigo-100
                                focus:outline-none cursor-pointer
                                border border-gray-300 rounded-lg">
                        </div>
                        <div id="imagePreview" class="ml-4 hidden">
                            <img src="" alt="Preview" class="h-20 w-20 object-cover rounded-lg ring-2 ring-gray-200">
                            <button type="button" id="removeImage" class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Max file size: 5MB. Supported formats: JPG, PNG, GIF</p>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Platform Selection -->
            <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Select Platforms</h3>
                <p class="text-sm text-gray-500">Choose where you want to publish your post.</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($platforms as $platform)
                    <label class="relative flex items-start p-4 rounded-lg border cursor-pointer focus:outline-none">
                        <input type="checkbox" name="platforms[]" value="{{ $platform->id }}"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            {{ in_array($platform->id, old('platforms', [])) ? 'checked' : '' }}>
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">
                                {{ ucfirst($platform->name) }}
                            </span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('platforms')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Scheduling Section -->
            <x-post.scheduling-section />

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Post
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentTextarea = document.getElementById('content');
        const charCountSpan = document.getElementById('charCount');
        const platformLimitSpan = document.getElementById('platformLimit');
        const platformCheckboxes = document.querySelectorAll('input[name="platforms[]"]');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('img');
        const removeImageBtn = document.getElementById('removeImage');

        // Image preview with enhanced animation
        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    e.target.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    imagePreview.classList.add('animate-fade-in');
                }
                reader.readAsDataURL(file);
            } else {
                resetImagePreview();
            }
        });

        // Remove image
        removeImageBtn.addEventListener('click', function() {
            resetImagePreview();
            imageInput.value = '';
        });

        function resetImagePreview() {
            imagePreview.classList.add('hidden');
            imagePreview.classList.remove('animate-fade-in');
            previewImage.src = '';
        }

        // Character count and platform limits
        function updateCharCount() {
            const content = contentTextarea.value;
            const length = content.length;
            charCountSpan.textContent = length;

            // Check platform-specific limits
            let limits = [];
            platformCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const platformName = checkbox.parentElement.querySelector('.text-gray-900').textContent.trim().toLowerCase();
                    switch(platformName) {
                        case 'twitter':
                            if (length > 280) limits.push('Twitter: Exceeds 280 chars');
                            break;
                        case 'facebook':
                            if (length > 63206) limits.push('Facebook: Exceeds 63,206 chars');
                            break;
                        case 'linkedin':
                            if (length > 3000) limits.push('LinkedIn: Exceeds 3,000 chars');
                            break;
                    }
                }
            });

            platformLimitSpan.textContent = limits.join(', ');
            platformLimitSpan.className = limits.length ? 'text-red-600 font-medium' : 'text-gray-500 font-medium';
        }

        // Event listeners
        contentTextarea.addEventListener('input', updateCharCount);
        platformCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCharCount);
        });

        // Initial check
        updateCharCount();
    });
    </script>
    @endpush
@endsection