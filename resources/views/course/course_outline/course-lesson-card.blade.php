<a href="{{ $href ?? '#' }}" class="flex items-center gap-x-2 md:gap-x-4 px-4 md:px-6 py-2 hover:bg-white hover:bg-opacity-10 transition">
    <img src="{{ $imageSrc }}" alt="" class="aspect-video rounded-md h-12 md:h-14">
    <div class="flex flex-row justify-between items-center text-white text-xs md:text-base font-normal gap-x-4 py-2 w-full">
        <p class="line-clamp-2 w-full">
            {{ $title }}
        </p>
        <p>{{ $duration }}</p>
    </div>
</a>
