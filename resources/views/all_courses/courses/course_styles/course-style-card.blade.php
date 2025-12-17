{{-- Use background for image, has overlay --}}
{{-- Use backgroundColor for color --}}
<div class="flex flex-col justify-between border-0 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 shadow-sm p-5 h-full min-h-[220px] w-full transition-transform hover:scale-105 relative">
    @if(isset($roleBadge))
        <div class="absolute top-2 right-2 z-10">
            {!! $roleBadge !!}
        </div>
    @endif
    <div class="flex items-start mb-4">
        <div class="rounded-lg bg-white shadow w-12 h-12 flex items-center justify-center mr-2 border border-blue-200 overflow-hidden">
            <img src="{{$background ?? ''}}" alt="Level Icon" class="object-cover w-12 h-12" />
        </div>
    </div>
    <div class="flex flex-col flex-1 mb-4">
        <div class="font-bold text-lg text-gray-900 mb-1 leading-tight line-clamp-2">{{$subheadline}}</div>
        <div class="text-sm text-gray-700 leading-snug line-clamp-2">{{$headline}}</div>
    </div>
    <div class="mt-auto flex justify-center">
        <a href="{{$link}}" class="block w-full">
            <button class="w-full bg-white text-blue-900 font-bold rounded-full py-2 border border-blue-200 shadow-sm hover:bg-blue-50 transition">
                Lihat Detail
            </button>
        </a>
    </div>
</div>
