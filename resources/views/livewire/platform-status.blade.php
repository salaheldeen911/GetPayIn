<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Platform Status</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($platforms as $platform)            
        <div wire:key="platform-{{ $platform->id }}" class="bg-white overflow-hidden shadow-sm rounded-lg border {{ $platform->is_active ? 'border-green-500' : 'border-gray-200' }}">
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $platform->name }}</h4>
                            <p class="mt-1 text-sm text-gray-500">{{ ucfirst($platform->type->value) }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $platform->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $platform->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>                    
                    @if($platform->token_expires_at)
                        <p class="text-sm text-gray-500 mt-2">
                            Token expires: {{ \Carbon\Carbon::parse($platform->token_expires_at)->diffForHumans() }}
                        </p>
                    @endif

                    <div class="mt-4 space-y-2">
                        <button wire:click="toggleActive({{ $platform->id }})" wire:confirm="{{ $platform->is_active ? 'Are you sure you want to deactivate this platform?' : 'Are you sure you want to activate this platform?' }}" class="w-full inline-flex justify-center items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium transition-colors duration-200 {{ $platform->is_active ? 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100' : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' }}">
                            {{ $platform->is_active ? 'Deactivate' : 'Activate' }}
                        </button>

                        <button wire:click="disconnect({{ $platform->id }})" wire:confirm="Are you sure you want to disconnect this platform?" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            Disconnect
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4 text-center">
        <a href="{{ route('platforms.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Manage Platforms</a>
    </div>
</div> 