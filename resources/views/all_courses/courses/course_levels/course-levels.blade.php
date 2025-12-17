<script src="//unpkg.com/alpinejs" defer></script>
@auth
@php
    $allActiveCourses = collect();
    foreach ($userClassroom as $classroom) {
        if ($classroom->user_role === 'teacher' && isset($classroom->display_level_id)) {
            // For teachers, use the grouped level data
            $allActiveCourses->push([
                'classroom_id' => $classroom->id,
                'classroom_name' => $classroom->name,
                'level_id' => $classroom->display_level_id,
                'level_name' => $classroom->display_level_name,
                'level_img_url' => $classroom->display_level_img_url,
                'link' => url('/classroom/' . $classroom->id . '/level/' . $classroom->display_level_id),
            ]);
        } else {
            // For students, show all levels as before
            foreach ($classroom->classroomLevels->where('is_active', true) as $classroomLevel) {
                $allActiveCourses->push([
                    'classroom_id' => $classroom->id,
                    'classroom_name' => $classroom->name,
                    'level_id' => $classroomLevel->level->id,
                    'level_name' => $classroomLevel->level->name,
                    'level_img_url' => $classroomLevel->level->img_url,
                    'link' => url('/classroom/' . $classroom->id . '/level/' . $classroomLevel->level->id),
                ]);
            }
        }
    }
    $classroomOptions = $userClassroom->filter(function ($classroom) {
        return $classroom->classroomLevels->where('is_active', true)->isNotEmpty();
    });
@endphp
@endauth
<div class="flex flex-col grow gap-y-8" x-data="{ selectedClassroom: 'all' }">
    <div class="flex items-center justify-center gap-x-5 flex-col sm:flex-row gap-y-5">
        <h2 class="uppercase text-2xl font-bold text-blue-900 text-center">Course yang Tersedia</h2>
        @auth
        <select x-model="selectedClassroom" class="bg-white text-xl font-bold text-blue-900 rounded-full px-4 py-2 border-4 border-blue-900">
            <option value="all">Semua Kelas</option>
            @foreach ($classroomOptions as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
            @endforeach
        </select>
        @endauth
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4 lg:gap-8">
        @auth
            @forelse ($allActiveCourses as $course)
                <div x-show="selectedClassroom === 'all' || selectedClassroom == '{{ $course['classroom_id'] }}'" style="display: none;" class="transition-all">
                    @php
                        $classroom = $userClassroom->firstWhere('id', $course['classroom_id']);
                        $role = $classroom->user_role ?? 'unknown';
                        $roleBadge = '';
                        if($role === 'teacher') {
                            $roleBadge = '<div class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-semibold">👨‍🏫 Teacher</div>';
                        } elseif($role === 'student') {
                            $roleBadge = '<div class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">👨‍🎓 Student</div>';
                        }
                    @endphp
                    @component('all_courses.courses.course_styles.course-style-card')
                        @slot('background', asset('uploads/' . $course['level_img_url']))
                        @slot('headline',"Kelas: ".$course['classroom_name'])
                        @slot('subheadline', $course['level_name'])
                        @slot('link', $course['link'])
                        @slot('textColor', 'text-white')
                        @if($roleBadge)
                            @slot('roleBadge', $roleBadge)
                        @endif
                    @endcomponent
                </div>
            @empty
                <div class="col-span-12">
                    Tidak ada kelas yang tersedia
                </div>
            @endforelse
        @endauth
        @guest
            <div class="col-span-12">
                Silahkan <a href="{{route('login')}}" class="text-blue-500"> Login </a> untuk melihat kelas yang tersedia
            </div>
        @endguest
    </div>
</div>