<div class="relative" x-data="{ open: false }" x-cloak>
    <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
        @if(Auth::user()->profile_picture)
            <img src="{{ asset('uploads/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-8 h-8 rounded-full object-cover shadow border border-gray-200">
        @else
            <div class="w-8 h-8 rounded-full bg-white shadow border border-gray-200 flex items-center justify-center text-pink-500 font-semibold text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        @endif
        <span class="hidden md:block text-sm font-medium text-pink-500 bg-white px-2 py-1 rounded-md shadow-sm">
            @php
                $name = Auth::user()->name;
                $words = explode(' ', $name);
                if (count($words) > 1) {
                    // If multiple words, show first word
                    echo $words[0];
                } else {
                    // If single word, truncate to 8 characters
                    echo strlen($name) > 8 ? substr($name, 0, 8) . '...' : $name;
                }
            @endphp
        </span>
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 z-50 border border-gray-100" style="min-width: 180px;">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Edit Profil
            </div>
        </a>

        <hr class="my-1">
        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </div>
        </a>
    </div>
</div> 