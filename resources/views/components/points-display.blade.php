@props(['user'])

<div class="flex items-center space-x-2">
    <div class="flex items-center bg-pink-100 rounded-full px-3 py-1">
        <svg class="w-4 h-4 text-pink-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
        </svg>
        <span class="text-sm font-medium text-pink-600">{{ $user->point ?? 0 }}</span>
    </div>
    
    @if(isset($showLabel) && $showLabel)
        <span class="text-sm text-gray-500">points</span>
    @endif
</div> 