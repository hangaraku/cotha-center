<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    @vite('resources/css/app.css')
</head>

<body class="flex flex-col min-h-screen" style="background-color: #f2f4f9;">
    @if(session('error'))
        <div id="floating-error" class="fixed top-6 left-6 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-x-3 animate-fade-in">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notif = document.getElementById('floating-error');
                if (notif) {
                    setTimeout(() => {
                        notif.style.transition = 'opacity 0.5s';
                        notif.style.opacity = 0;
                        setTimeout(() => notif.remove(), 600);
                    }, 3000);
                }
            });
        </script>
    @endif
    @if(Route::currentRouteName() == 'lesson' || Route::currentRouteName() == 'exercise' )
    @auth
    <x-sidebar>
        @slot("module", $module)
        @slot("classroom", $classroom)

        @slot("unit", $unit)
        
        @yield('content')
    </x-sidebar>
    @endauth
    @else
    <div class="flex flex-col min-h-screen">
        @yield('content')
    </div>
    @endif


    <script src="{{ asset('js/nav-bar.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

<script>
    // Wait for the DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        // Get all elements with the class "attachment__name"
        var elements = document.querySelectorAll('.attachment__caption');

        // Hide each element
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = 'none';
        }
    });
</script>
</html>
