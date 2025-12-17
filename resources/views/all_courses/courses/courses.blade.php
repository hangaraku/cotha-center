<div class="flex flex-col px-16 lg:px-24 py-8 lg:py-16 gap-y-20" style="background-color: #f2f4f9;">
    <div class="flex flex-col gap-y-8">
        @component('components.input_fields.search-field')
            @slot('placeholder', 'Search courses')
        @endcomponent
        <div class="flex flex-col md:flex-row gap-y-12 gap-x-8 lg:gap-x-12">
            @include('all_courses.courses.course_levels.course-levels')
            {{-- @include('all_courses.courses.skills.skills') --}}
        </div>
    </div>
    @include('all_courses.courses.course_styles.course-styles')
</div>
