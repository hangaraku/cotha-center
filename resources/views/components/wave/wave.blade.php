@switch($position ?? 'top')
    @case('bottom')
        <svg viewBox="0 0 1440 50" fill="none" xmlns="http://www.w3.org/2000/svg" class="hidden sm:block {{ $class ?? '' }}">
            <path
                d="M1440 50.1447H0V12.6447C0 12.6447 291.594 27.5046 480 25.1447C669.666 22.769 770.328 -2.11028 960 0.144278C1092.3 1.71682 1440 40.1447 1440 40.1447V50.1447Z" />
        </svg>
        <svg viewBox="0 0 390 50" fill="none" xmlns="http://www.w3.org/2000/svg" class="block sm:hidden {{ $class ?? '' }}">
            <path d="M390 50H0V12.6082C0 12.6082 64 25 120 25C176 25 214 0 270 0C326 0 390 25 390 25V50Z" />
        </svg>
    @break

    @default
        <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg"
            class="hidden sm:block {{ $class ?? '' }}">
            <path
                d="M0 0H1440V37.5C1440 37.5 1148.41 22.6401 960 25C770.334 27.3757 669.672 52.255 480 50.0004C347.705 48.4279 0 10 0 10V0Z" />
        </svg>
        <svg viewBox="0 0 390 50" fill="none" xmlns="http://www.w3.org/2000/svg" class="block sm:hidden {{ $class ?? '' }}">
            <path d="M0 0H390V37.3918C390 37.3918 326 25 270 25C214 25 176 50 120 50C64 50 0 25 0 25V0Z" />
        </svg>
@endswitch
