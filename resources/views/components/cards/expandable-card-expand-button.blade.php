<button class="expandable-card-button inline-flex items-center gap-x-1">
    <p class="uppercase text-xs lg:text-sm font-semibold">{{ $label }}</p>
    <div class="expandable-card-expand-icon transition">
        @component('components.icons.chevron-down')
            @slot('class', 'w-4 stroke-2')
        @endcomponent
    </div>
</button>
