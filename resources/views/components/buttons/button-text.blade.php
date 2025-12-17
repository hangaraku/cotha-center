<a href="{{ $href ?? '#' }}" onclick="{{ $onClick ?? '' }}"
    class="flex items-center gap-x-2 px-3 lg:px-3 py-2 lg:py-2 {{ $textColor ?? 'text-primary' }} text-xs md:text-base font-medium">
    {{ $icon ?? '' }}
    {{ $label ?? '' }}
</a>
