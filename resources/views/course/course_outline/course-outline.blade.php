<div class="flex flex-col justify-center items-center gap-y-2 md:gap-y-4  ">
    <h2 class="text-blue-900 text-lg md:text-xl lg:text-2xl font-semibold w-full text-left flex items-center gap-x-2">
        Daftar Modul
        <span class="relative group cursor-pointer inline-block">
            <svg class="w-5 h-5 text-gray-400 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="white"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
            </svg>
            <span class="absolute left-0 top-full mt-2 w-64 p-2 rounded bg-gray-800 text-white text-xs opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition pointer-events-auto z-30 group-hover:pointer-events-auto group-focus-within:pointer-events-auto"
                style="pointer-events: auto;">
                Untuk membuka modul berikutnya, siswa harus menyelesaikan modul sebelumnya dan mengajukan permintaan kepada pengajar.
            </span>
        </span>
    </h2>
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mt-4">
        @forelse($level->modules as $project)
            @php
                $unitCount = $project->units->count();
                $quizCount = $project->units->sum(fn($unit) => $unit->exercises->count());
                $isTeacher = \App\Models\ClassroomTeacher::where('user_id', auth()->user()->id)
                    ->where('classroom_id', $classroom->id)
                    ->exists();
                $hasUserModule = $isTeacher || auth()->user()->userModules->where('module_id', $project->id)->first();
                $hasUserUnit = $isTeacher || auth()->user()->userUnits->whereIn('unit_id', $project->units->pluck('id'))->isNotEmpty();
                $isLocked = !$hasUserModule || !$hasUserUnit;
            @endphp
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-[320px] relative">
                <!-- Order badge -->
                <div class="absolute top-3 left-3 z-10">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-pink-500 text-white font-bold">{{$loop->iteration}}</span>
                </div>
                <!-- Lock overlay -->
                @if($isLocked)
                    <div class="absolute inset-0 bg-gray-700 bg-opacity-40 flex items-center justify-center z-20 pointer-events-none">
                        <svg class="h-16 w-16 text-white opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                @endif
                <!-- Module image -->
                <div class="w-full aspect-video bg-gray-200 flex items-center justify-center overflow-hidden">
                    @if($project->img_url)
                        <img src="{{ strpos($project->img_url, 'http') !== false ? $project->img_url : asset('uploads/' . $project->img_url) }}" alt="Module Image" class="object-cover w-full h-full rounded-t-2xl">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                    @endif
                </div>
                <!-- Card content -->
                <div class="flex flex-col flex-1 p-4 gap-y-2">
                    <h3 class="font-bold text-lg text-gray-900 line-clamp-2">{{ $project->name }}</h3>
                    <div class="flex flex-row gap-x-4 text-xs text-gray-500 mb-1 items-center">
                        <span class="flex items-center gap-x-1">
                            <!-- Unit icon (book) -->
                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            {{ $unitCount }} Unit
                        </span>
                        <span class="flex items-center gap-x-1">
                            <!-- Quiz icon -->
                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            {{ $quizCount }} Quiz
                        </span>
                    </div>
                    <div class="flex-1 overflow-y-auto max-h-20 mb-2">
                        <p class="text-gray-600 text-sm">{!! $project->description !!}</p>
                    </div>
                    <div class="mt-auto flex justify-end">
                        @if($project->units->first())
                            <a href="{{ route('lesson', ['classroom' => $classroom->id, 'id' => $project->id, 'unitId' => $project->units->first()->id]) }}"
                               class="inline-block px-4 py-2 rounded-full bg-pink-500 text-white font-semibold shadow hover:bg-pink-600 transition @if($isLocked) pointer-events-none opacity-60 @endif">
                                Buka Modul
                            </a>
                        @else
                            <button class="inline-block px-4 py-2 rounded-full bg-gray-400 text-white font-semibold cursor-not-allowed" disabled>
                                Buka Modul
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center">You have no lesson available for now</div>
        @endforelse
    </div>
</div>
