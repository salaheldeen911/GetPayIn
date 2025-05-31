<div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
    <h3 class="text-lg font-medium text-gray-900">Scheduling Options</h3>
    <p class="text-sm text-gray-500">Choose when to publish your post.</p>

    <div class="space-y-4">
        <!-- Publishing Options -->
        <div class="flex items-center space-x-4">
            <label class="flex items-center">
                <input type="radio" name="status" value="draft" class="form-radio text-indigo-600" 
                    {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Save as Draft</span>
            </label>
            <label class="flex items-center">
                <input type="radio" name="status" value="scheduled" class="form-radio text-indigo-600"
                    {{ old('status') === 'scheduled' ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Schedule for Later</span>
            </label>
        </div>

        <!-- Scheduling DateTime -->
        <div id="schedulingOptions" class="space-y-4" style="display: none;">
            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">
                    Publication Date and Time
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('scheduled_at', now()->addMinutes(5)->format('Y-m-d\TH:i')) }}"
                        min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
                @error('scheduled_at')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Platform-specific Warnings -->
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Platform Limitations</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Twitter: Maximum 280 characters</li>
                                <li>Facebook: Maximum 63,206 characters</li>
                                <li>Instagram: Requires at least one image</li>
                                <li>LinkedIn: Maximum 3,000 characters</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const schedulingOptions = document.getElementById('schedulingOptions');

    function toggleSchedulingOptions() {
        const isScheduled = document.querySelector('input[name="status"]:checked').value === 'scheduled';
        schedulingOptions.style.display = isScheduled ? 'block' : 'none';
        
        // Make scheduled_at required only when scheduling is selected
        const scheduledAtInput = document.getElementById('scheduled_at');
        if (scheduledAtInput) {
            scheduledAtInput.required = isScheduled;
        }
    }

    statusRadios.forEach(radio => {
        radio.addEventListener('change', toggleSchedulingOptions);
    });

    // Initial state
    toggleSchedulingOptions();
});
</script>
@endpush