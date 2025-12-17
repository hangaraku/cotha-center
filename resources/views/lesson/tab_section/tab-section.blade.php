<div style="flex-grow: 1" class="flex flex-col gap-y-6 px-8 md:px-16 lg:px-24 py-8 lg:py-16 -mt-24 relative z-10">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 w-full text-black">
        @component('components.tabs.tabs', ['unit' => $unit])
        {!!$unit->description!!}
        @endcomponent
    </div>
</div>
