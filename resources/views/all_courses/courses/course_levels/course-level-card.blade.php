{{-- Use background for image, has overlay --}}
{{-- Use backgroundColor for color --}}
<div
    class="group flex flex-col justify-center items-center aspect-square rounded-xl {{ $backgroundColor ?? 'bg-primary' }} {{ $background ?? '' }} bg-cover bg-center">
    {{-- Default --}}
    <div
        class="flex group-hover:hidden flex-col justify-center items-center aspect-square gap-y-2 p-4 w-full h-48 md:h-full rounded-xl uppercase text-center {{ isset($background) ? 'bg-primary bg-opacity-0' : '' }}">
        <p class="line-clamp-2 text-sm md:text-2xl text-ellipsis font-bold {{ $textColor ?? 'text-white' }}">
            {{ $headline }}</p>
        <p class="line-clamp-2 text-xs md:text-sm text-ellipsis font-medium {{ $textColor ?? 'text-white' }}">
            {{ $subheadline }}</p>
    </div>
    {{-- On hover --}}
    <div
        class="hidden group-hover:flex flex-col justify-between items-center aspect-square gap-y-4 p-4 h-48 md:h-full rounded-xl {{ isset($background) ? 'bg-primary bg-opacity-0' : '' }}">
        <div class="flex items-center h-full">
            <p class="line-clamp-5 text-ellipsis text-xs lg:text-base text-center {{ $textColor ?? 'text-white' }}">
                {{ $description }}</p>
        </div>

        @component('components.buttons.button-pill')
            @slot('size', 'large')
            @slot('backgroundColor', 'bg-white bg-opacity-20')
            @slot('label', 'View Course')
            @slot('href', $link)
        @endcomponent

    </div>
</div>
