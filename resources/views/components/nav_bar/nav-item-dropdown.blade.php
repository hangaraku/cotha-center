<li>
    <div class="nav-item-dropdown relative inline-block text-left">
        <button type="button" class="nav-item-dropdown-button block uppercase font-medium text-primary p-2">
            <div class="flex gap-1">
                {{ $label }}
                @component('components.icons.chevron-down')
                    @slot('class', 'w-4 stroke-2')
                @endcomponent
            </div>
        </button>
        @component('components.nav_bar.nav-panel')
            @slot('navPanelItems')
                {{ $navPanelItems }}
            @endslot
        @endcomponent
    </div>
</li>
