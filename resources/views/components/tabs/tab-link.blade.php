<li class="mr-1 md:mr-2">
    {{-- Use tab.js to configure tab link's style --}}
    <a href="{{ $href ?? '#' }}"
        class="tab-link inline-flex items-center justify-center gap-x-1 md:gap-x-2 p-2 sm:px-4 sm:py-3 text-xs sm:text-base rounded-t-lg">
        {{ $icon ?? '' }}
        {{ $label ?? '' }}
    </a>
</li>
