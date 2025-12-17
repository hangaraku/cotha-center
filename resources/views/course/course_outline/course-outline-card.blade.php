<div x-data="{ open: false }"
    class="expandable-card w-full flex flex-col text-black rounded-2xl shadow-lg hover:shadow-xl gap-y-2 bg-white transition">
    <a href="{{ $lock ? "#" : $link }}" class="  flex flex-col md:flex-row gap-x-4 gap-y-0 rounded-2xl p-4 bg-blue-50">
        @if(strpos($imageSrc, "http") !== false)

            @if($lock)
                <div class="relative aspect-video md:h-36 lg:h-44 rounded-lg">
                    <img src="{{ $imageSrc }}" alt="" class="aspect-video rounded-lg md:h-36 lg:h-44">

                    <div class="absolute inset-0 flex items-center justify-center">
                        <!-- Gray overlay -->
                        <div class="absolute rounded-lg align-items-center flex inset-0 bg-gray-600 opacity-60">
                            <svg class="text-white m-auto   h-24" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>

                        <!-- Lock icon -->


                    </div>
                </div>
            @else
                <img src="{{ $imageSrc }}" alt="" class="aspect-video rounded-lg md:h-36 lg:h-44">

            @endif
        @else

            @if($lock)
                <div class="relative aspect-video md:h-36 lg:h-44 rounded-lg">
                    <img src="{{ asset('uploads/' . $imageSrc) }}" alt="" class="aspect-video rounded-lg md:h-36 lg:h-44 object-cover">

                    <div class="absolute inset-0 flex items-center justify-center">
                        <!-- Gray overlay -->
                        <div class="absolute rounded-lg align-items-center flex inset-0 bg-gray-600 opacity-60">
                            <svg class="text-white m-auto   h-24" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>

                        <!-- Lock icon -->


                    </div>
                </div>
            @else
                <img src="{{ asset('uploads/' . $imageSrc) }}" alt="" class="aspect-video rounded-lg md:h-36 lg:h-44 object-cover">

            @endif



        @endif
        <div class="flex flex-col w-full justify-between gap-y-3 md:gap-y-4 py-2">
            <div class="flex flex-col gap-y-2">
                <div class="flex gap-x-2">
                    {{ $tags }}
                </div>
                <div class="flex flex-col">
                    <h2 class="uppercase line-clamp-1 text-sm md:text-base lg:text-lg font-bold text-black">
                        {{ $title }}
                    </h2>
                    <p class="line-clamp-2 text-xs md:text-sm lg:text-base">{{ $description }}</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-y-4 w-full">
                <div
                    class="flex flex-row items-start text-sm font-semibold gap-x-2 lg:gap-x-4 divide-x divide-primary w-full md:w-fit">
                    @component('course.course_outline.course-outline-info-item')
                    @slot('icon')
                    @component('components.icons.rectangle-stack')
                    @slot('class', 'w-4 lg:w-5')
                    @endcomponent
                    @endslot
                    @slot('label')
                    {{ $lessonAmount }}
                    @endslot
                    @endcomponent
                    @component('course.course_outline.course-outline-info-item')
                    @slot('icon')
                    @component('components.icons.clock')
                    @slot('class', 'w-4 lg:w-5')
                    @endcomponent
                    @endslot
                    @slot('label', '41:14')
                    @slot('label')
                    {{ $moduleDuration }}
                    @endslot
                    @endcomponent
                </div>
                <button type="button" @click="open = !open"
                    class="focus:outline-none px-4 py-2 rounded-full bg-primary text-white font-semibold shadow hover:bg-primary/80 transition">
                    Lihat Modul
                </button>
            </div>
        </div>
    </a>
    <div x-show="open"
        class="expandable-card-body flex-col rounded-2xl gap-y-2 md:gap-y-4 py-2 md:py-4 text-white bg-primary"
        style="display: none;">
        <div class="px-4 md:px-6">
            <h3 class="uppercase text-xs md:text-base font-bold">{{ $bodyTitle }}</h3>
        </div>
        <div class="flex flex-col">
            @foreach($project->units as $unit)
                @include('course.course_outline.course-lesson-card', [
                    'href' => route('lesson', ['classroom' => $classroom->id, 'id' => $project->id, 'unitId' => $unit->id]),
                    'imageSrc' => strpos($unit->img_url, 'http') !== false ? $unit->img_url : asset('uploads/' . $unit->img_url),
                    'title' => $unit->name,
                    'duration' => $unit->duration ?? '',
                ])
            @endforeach
        </div>
    </div>
</div>
