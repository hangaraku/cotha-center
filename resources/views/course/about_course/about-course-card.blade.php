<div class="expandable-card w-full flex flex-col text-black rounded-md bg-white shadow-lg gap-y-6 py-10 px-8 sm:px-16 lg:px-24 xl:px-48">
    <!-- Breadcrumb Navigation -->
    <nav class="text-sm text-gray-500 mb-2" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('home') }}" class="hover:underline text-blue-600">
                    Daftar Kursus
                </a>
            </li>
            <li><span class="mx-2">></span></li>
            <li class="text-gray-700">
                {{ $classroom->name ?? 'Classroom' }}
            </li>
            <li><span class="mx-2">></span></li>
            <li class="text-gray-700">
                {{ $level->name ?? 'Course' }}
            </li>
        </ol>
    </nav>



    <!-- Course Image and Description -->
    <div class="flex flex-col lg:flex-row gap-x-4 items-start">
        <!-- Thumbnail -->
        @if(strpos($level->img_url, "http") !== false)
            <div class="w-full lg:w-60 aspect-video rounded-lg overflow-hidden mb-4 lg:mb-0 flex-shrink-0">
                <img src="{{ $level->img_url }}"
                     alt="Course Thumbnail"
                     class="w-full h-full object-cover object-center" />
            </div>
        @else
            <div class="w-full lg:w-60 aspect-video rounded-lg overflow-hidden mb-4 lg:mb-0 flex-shrink-0">
                <img src="{{ asset('uploads/' . $level->img_url) }}"
                     alt="Course Thumbnail"
                     class="w-full h-full object-cover object-center" />
            </div>
        @endif

        <!-- Scrollable Description (synced height with image) -->
        <div class="flex flex-col justify-start gap-y-4 px-2 flex-1 w-full max-h-[135px] overflow-y-auto border-2 border-gray-200 rounded-md">
            <p class="text-xs md:text-sm lg:text-base leading-relaxed">
                {!! $level->description !!}
            </p>
        </div>
    </div>

    <!-- (Optional) Expandable Body -->
    <div class="expandable-card-body hidden px-2">
        <p class="text-xs md:text-sm lg:text-base">
            <!-- Additional expandable content can go here -->
        </p>
    </div>

    @include('course.course_outline.course-outline')

</div>
