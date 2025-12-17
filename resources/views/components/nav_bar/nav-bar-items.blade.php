<div class="hidden lg:flex items-center ml-2">
    <ul class="flex flex-col p-2 md:p-0 mt-4 md:flex-row md:space-x-2 md:mt-0 md:border-0">
        <!-- Showcase - visible to everyone -->
        @component('components.nav_bar.nav-item')
            @slot('label', 'Showcase')
            @slot('href', route('showcase.index'))
            @slot('textColor', 'text-white')
            @if(isset($isCoursePage))
                @slot('isCoursePage', $isCoursePage)
            @endif
        @endcomponent
        
        @auth
        @component('components.nav_bar.nav-item')
            @slot('label', 'Project Saya')
            @slot('href', route('projects.index'))
            @slot('textColor', 'text-white')
            @if(isset($isCoursePage))
                @slot('isCoursePage', $isCoursePage)
            @endif
        @endcomponent
        @component('components.nav_bar.nav-item')
            @slot('label', 'Rewards')
            @slot('href', route('rewards.index'))
            @slot('textColor', 'text-white')
            @if(isset($isCoursePage))
                @slot('isCoursePage', $isCoursePage)
            @endif
        @endcomponent
        @component('components.nav_bar.nav-item')
            @slot('label', 'Akun Saya')
            @slot('href', route('accounts.index'))
            @slot('textColor', 'text-white')
            @if(isset($isCoursePage))
                @slot('isCoursePage', $isCoursePage)
            @endif
        @endcomponent
        
        @if(auth()->user()->hasRole(['super_admin', 'Teacher', 'Supervisor']))
        @component('components.nav_bar.nav-item')
            @slot('label', 'Admin Dashboard')
            @slot('href', url('/admin'))
            @slot('textColor', 'text-white')
            @if(isset($isCoursePage))
                @slot('isCoursePage', $isCoursePage)
            @endif
        @endcomponent
        @endif
        @endauth
        {{-- @component('components.nav_bar.nav-item-dropdown')
            @slot('label', 'Courses')
            @slot('navPanelItems')
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'Account')
                @endcomponent
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'Support')
                @endcomponent
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'License')
                @endcomponent
            @endslot
        @endcomponent --}}
        {{-- @component('components.nav_bar.nav-item')
            @slot('label', 'My Classroom')
            @slot('href', route('home'))
        @endcomponent

        @component('components.nav_bar.nav-item')
        @slot('label', 'Reward')
        @slot('href', '#')
    @endcomponent --}}
        {{-- @component('components.nav_bar.nav-item-dropdown')
            @slot('label', 'Package')
            @slot('navPanelItems')
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'Account')
                @endcomponent
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'Support')
                @endcomponent
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'License')
                @endcomponent
            @endslot
        @endcomponent
        @component('components.nav_bar.nav-item-dropdown')
            @slot('label', 'Explore')
            @slot('navPanelItems')
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'Account')
                @endcomponent
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'Support')
                @endcomponent
                @component('components.nav_bar.nav-panel-item')
                    @slot('label', 'License')
                @endcomponent
            @endslot
        @endcomponent
         --}}
    </ul>
</div>
