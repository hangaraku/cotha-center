<a href="{{ $href ?? '#' }}"
    class="flex items-center gap-x-6 p-3 rounded-lg text-primary hover:text-white hover:bg-gradient-to-br from-slate-700 to-slate-500 hover:shadow-2xl border-2 border-primary">
    @component('components.icons.light-bulb')
        @slot('class', 'w-5 hover:stroke-white')
    @endcomponent
    <p class="uppercase text-sm lg:text-base font-medium">{{ $headline }}</p>
</a>
