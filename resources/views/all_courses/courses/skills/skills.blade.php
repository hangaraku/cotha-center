<div class="flex flex-col basis-1/3 gap-y-8">
    {{-- <h2 class="uppercase text-xl font-bold text-primary">Top 10 Points</h2> --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:flex flex-col gap-x-4 gap-y-4 lg:gap-y-5">
       @php
$leaderboardData = [
    ['name' => 'Lee Taeyong', 'points' => 258.244],
    ['name' => 'Mark Lee', 'points' => 258.242],
    ['name' => 'Xiao Dejun', 'points' => 258.223],
    ['name' => 'Qian Kun', 'points' => 258.212],
    ['name' => 'Johnny Suh', 'points' => 258.208],
];

       @endphp
        {{-- <x-leaderboard :data="$leaderboardData" /> --}}
    </div>
</div>
