<button type="button" onclick="{{ $onMarkAsComplete ?? '' }}"
    class="flex items-center gap-x-2 uppercase px-3 lg:px-5 py-2 w-fit rounded-full text-center text-white text-xs lg:text-sm font-medium bg-slate-400 bg-opacity-30">
    @if ($isComplete ?? false)
        @component('components.icons.check-circle-filled')
        @endcomponent
    @else
        @component('components.icons.check-circle')
        @endcomponent
    @endif
    <p>
        {{ $isComplete ?? false ? 'Completed' : 'Mark Lesson as Complete' }}
    </p>
</button>
