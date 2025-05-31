@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Post</h1>
    
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Post</h2>

            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                        <div class="mt-2">
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                                class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-200 ease-in-out"
                                placeholder="Enter post title">
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
                                placeholder="Write your post content here...">{{ old('content', $post->content) }}</textarea>
                            <div class="mt-2 text-sm text-gray-500">
                                Characters: <span id="charCount" class="font-medium">0</span>
                            </div>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Image</label>
                        <div class="mt-2 relative">
                            @if($post->image_url)
                                <div class="mb-4 relative" id="currentImage">
                                    <img src="{{ asset($post->image_url) }}" alt="Post image" 
                                         class="h-32 w-auto rounded-lg ring-2 ring-gray-200">
                                    <button type="button" id="removeCurrentImage" 
                                            class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <input type="file" name="image" id="image"
                                        class="block w-full text-sm text-gray-500 
                                        file:mr-4 file:py-2.5 file:px-4 
                                        file:rounded-lg file:border-0 
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700 
                                        hover:file:bg-indigo-100
                                        focus:outline-none cursor-pointer
                                        border border-gray-300 rounded-lg">
                                </div>
                                <div id="imagePreview" class="ml-4 hidden relative">
                                    <img src="" alt="Preview" class="h-32 w-auto rounded-lg ring-2 ring-gray-200">
                                    <button type="button" id="removeNewImage" 
                                            class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200 focus:outline-none">
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
                            <input type="hidden" name="remove_image" id="removeImage" value="0">
                        </div>
                    </div>

                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium leading-6 text-gray-900">Schedule Time</label>
                        <div class="mt-2">
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                value="{{ old('scheduled_at', $post->scheduled_at) }}"
                                class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-200 ease-in-out"
                                {{ $post->status === 'published' ? 'disabled' : '' }}>
                            @error('scheduled_at')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                        <div class="mt-2">
                            <select name="status" id="status"
                                class="block w-full rounded-lg border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-200 ease-in-out"
                                {{ $post->status === App\Enums\PostStatus::PUBLISHED ? 'disabled' : '' }}>
                                <option value="{{ App\Enums\PostStatus::DRAFT->value }}" {{ old('status', $post->status) === App\Enums\PostStatus::DRAFT->value ? 'selected' : '' }}>Draft</option>
                                <option value="{{ App\Enums\PostStatus::SCHEDULED->value }}" {{ old('status', $post->status) === App\Enums\PostStatus::SCHEDULED->value ? 'selected' : '' }}>Scheduled</option>
                                @if($post->status === App\Enums\PostStatus::PUBLISHED)
                                    <option value="{{ App\Enums\PostStatus::PUBLISHED->value }}" selected>Published</option>
                                @endif
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Platforms</label>
                        <div class="mt-2 space-y-3">
                            @foreach($platforms as $platform)
                                <div class="flex items-center">
                                    <input type="checkbox" name="platforms[]" id="platform_{{ $platform->id }}"
                                        value="{{ $platform->id }}"
                                        {{ in_array($platform->id, old('platforms', $post->platforms->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 transition duration-200 ease-in-out"
                                        data-max-length="{{ $platform->getMaxLength() }}"
                                        {{ $post->status === 'published' ? 'disabled' : '' }}>
                                    <label for="platform_{{ $platform->id }}" class="ml-3 text-sm text-gray-700">
                                        {{ $platform->name }}
                                        @if($platform->getMaxLength())
                                            <span class="text-xs text-gray-500">(max {{ $platform->getMaxLength() }} chars)</span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('platforms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors duration-200">
                        Update Post
                    </button>
                    <a href="{{ route('posts.index') }}"
                        class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg bg-white text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentTextarea = document.getElementById('content');
            const charCountSpan = document.getElementById('charCount');
            const platformCheckboxes = document.querySelectorAll('input[name="platforms[]"]');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const currentImage = document.getElementById('currentImage');
            const removeImageField = document.getElementById('removeImage');
            
            // Image preview
            if (imageInput) {
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
                            // Hide current image if exists
                            if (currentImage) {
                                currentImage.style.display = 'none';
                            }
                            
                            // Show preview
                            const previewImg = imagePreview.querySelector('img');
                            previewImg.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            imagePreview.classList.add('animate-fade-in');
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Remove current image
                const removeCurrentImageBtn = document.getElementById('removeCurrentImage');
                if (removeCurrentImageBtn) {
                    removeCurrentImageBtn.addEventListener('click', function() {
                        currentImage.style.display = 'none';
                        removeImageField.value = '1';
                    });
                }

                // Remove new image preview
                const removeNewImageBtn = document.getElementById('removeNewImage');
                if (removeNewImageBtn) {
                    removeNewImageBtn.addEventListener('click', function() {
                        imagePreview.classList.add('hidden');
                        imageInput.value = '';
                        // Show current image if exists
                        if (currentImage) {
                            currentImage.style.display = 'block';
                        }
                        removeImageField.value = '0';
                    });
                }
            }

            // Character counter and platform validation
            function updateCharCount() {
                const content = contentTextarea.value;
                const length = content.length;
                charCountSpan.textContent = length;

                // Check platform limits
                platformCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const maxLength = parseInt(checkbox.dataset.maxLength);
                        if (maxLength && length > maxLength) {
                            checkbox.setCustomValidity(`Content exceeds maximum length for this platform`);
                        } else {
                            checkbox.setCustomValidity('');
                        }
                    }
                });
            }

            contentTextarea.addEventListener('input', updateCharCount);
            platformCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCharCount);
            });

            // Initial count
            updateCharCount();
        });
    </script>
    @endpush
@endsection