<script src="https://cdn.tailwindcss.com"></script>

<div style="" class="text-center flex flex-col h-screen" style="flex-grow: 1">



<div class="w-full min-h-[220px] md:min-h-[300px] lg:min-h-[320px] flex flex-col lg:flex-row items-center bg-gradient-to-r from-blue-500 to-blue-600">
    <div class="flex flex-1 flex-col justify-center items-start lg:text-left text-center gap-y-2 px-6 sm:px-10 md:px-16 lg:px-24 xl:px-48 py-8 md:py-12 text-white w-full">
        <div class="text-base md:text-lg font-medium opacity-80 mb-1">
            {{ $module->name }} &mdash; {{ $classroom->name }}
        </div>
        <div class="flex items-center gap-x-2">
            @php
                $units = $module->units->sortBy(function($u) { return [$u->order_number, $u->id]; })->values();
                $unitOrder = $units->search(function($u) use ($unit) { return $u->id == $unit->id; }) + 1;
            @endphp
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-pink-500 text-white font-bold text-lg">{{ $unitOrder }}</span>
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                {{ $unit->name }}
            </h1>
        </div>
    </div>
    @if($unit->img_url && $unit->img_url != "#")
    <div class="flex justify-center items-center w-full lg:w-auto mt-6 lg:mt-0">
        <img src="{{ strpos($unit->img_url, 'http') !== false ? $unit->img_url : asset('uploads/' . $unit->img_url) }}" alt="" class="z-10 w-40 sm:w-48 md:w-56 lg:w-56 xl:w-64 2xl:w-72 mx-auto lg:mr-32 rounded-lg object-cover">
    </div>
    @endif
</div>
