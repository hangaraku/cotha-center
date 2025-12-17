{{-- <div class="flex flex-col gap-y-8">
    <h2 class="uppercase text-xl font-bold text-primary">All Avaliable Courses</h2>
    <div class="flex overscroll-x-contain md:grid md:grid-cols-3 xl:grid-cols-5 gap-4 xl:gap-6">
        @auth
        @foreach($levels->where('center_id',auth()->user()->center->id) as $level)
        @component('all_courses.courses.course_styles.course-style-card')
            @slot('background', asset('uploads/'.$level->img_url))
            @slot('headline', $level->name)
            @slot('subheadline', '')
            @slot('link', '#')
        @endcomponent
        @endforeach
        @endauth
     
    </div>
</div> --}}
