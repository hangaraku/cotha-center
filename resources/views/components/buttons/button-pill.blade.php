@switch($size ?? 'medium')
    @case('small')
        <a href="{{ $href ?? '#' }}" onclick="{{ $onClick ?? '' }}"
            class="flex items-center gap-x-2 px-3 lg:px-5 py-1 w-fit rounded-full text-center {{ $textColor ?? 'text-white' }} text-xs lg:text-sm font-medium {{ $backgroundColor ?? 'bg-accent-orange' }}">
            {{ $icon ?? '' }}
            {{ $label ?? '' }}
        </a>
    @break

    @case('large')
        <a href="{{ $href ?? '#' }}" onclick="{{ $onClick ?? '' }}"
            class="flex items-center gap-x-2 px-3 lg:px-5 py-1 w-fit rounded-full text-center {{ $textColor ?? 'text-white' }} text-xs md:text-base lg:text-lg font-medium {{ $backgroundColor ?? 'bg-accent-orange' }}">
            {{ $icon ?? '' }}
            {{ $label ?? '' }}
        </a>
    @break

    @default
        <a href="{{ $href ?? '#' }}" onclick="{{ $onClick ?? '' }}"
            class="flex items-center gap-x-2 px-3 lg:px-5 py-1  w-fit rounded-full text-center {{ $textColor ?? 'text-white' }} text-sm md:text-base font-medium {{ $backgroundColor ?? 'bg-accent-orange' }}">
            {{ $icon ?? '' }}
            {{ $label ?? '' }}
        </a>
@endswitch
