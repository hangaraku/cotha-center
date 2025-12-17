<script src="https://cdn.tailwindcss.com"></script>

<nav class="relative z-10 md:pb-4 pb-4 {{ isset($isCoursePage) && $isCoursePage ? 'bg-gradient-to-r from-blue-500 to-blue-600' : '' }}">
    <div class="flex flex-row items-center justify-start px-8 sm:px-16 lg:px-24 xl:px-48 pt-4 text-white">
        @include('components.nav_bar.nav-menu')
        @if(Route::currentRouteName() == 'lesson' || Route::currentRouteName() == 'exercise' )
        
        @else
        <a href="{{route('home')}}" class="flex items-center text-white">
            @guest
            <img src="{{asset('images/logo.png')}}?id={{now()}}" class="h-14 mr-3 rounded-full" alt="Flowbite Logo" />
            @endguest
            @auth
            {{-- <img src="{{asset(Auth::user()->center->logo_url)}}?id={{now()}}" class="h-14 mr-3" alt="Flowbite Logo" /> --}}
            <img src="{{asset('images/logo.png')}}?id={{now()}}" class="h-14 mr-3 rounded-full" alt="Flowbite Logo" />
            @endauth
        </a>
        @endif
        @include('components.nav_bar.nav-bar-items')
        <div class="flex justify-center items-center gap-x-2 ml-auto">
            <div class="hidden sm:inline-flex">
                @guest
                @component('components.buttons.button-pill')
                    @slot('label', 'Log In')
                    @slot('href', route('login'))
                    @slot('backgroundColor', 'bg-pink-500 border-8 border-pink-300 border-opacity-100')
                    @slot('textColor', 'text-white')
                    @slot('size', 'large')
                @endcomponent
                @endguest
            </div>
            @guest
     
            @endguest
            @auth
                @include('components.nav_bar.nav-item-profile')
            @endauth
            
        </div>
    </div>

</nav>

