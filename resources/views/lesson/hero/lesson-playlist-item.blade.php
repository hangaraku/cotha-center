<a href="{{ $href ?? '#' }}"
    class="flex w-full  justify-between items-center gap-x-4 px-4 py-2 text-white text-xs md:text-sm rounded-full {{ isset($isCurrentLesson) ? 'bg-black bg-opacity-20' : '' }} hover:bg-white hover:bg-opacity-10 transition">
    <div class="flex  items-center gap-x-2 w-full">
        @if ($isCurrentLesson ?? false)
            @component('components.icons.pause-circle')
            @endcomponent
        @else
            @component('components.icons.play-circle')
            @endcomponent
        @endif
        <p class="line-clamp-1 w-full">{{ $title }}</p>
    </div>
    <div class="flex  items-center gap-x-3">
        <button type="button" onclick="event.preventDefault(); {{ $onFavoriteClick ?? '' }}">
            @component('components.icons.heart')
                @slot('class')
                    w-6 {{ isset($isFavorite) ? 'fill-white' : '' }}
                @endslot
            @endcomponent
        </button>
        <p>{{ $duration }}</p>
    </div>
</a>
