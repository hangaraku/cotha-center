@extends('layouts.app')

@section('title', 'Course')

@section('content')
    @include('components.nav_bar.nav-bar', ['isCoursePage' => true])
    <div class="w-full flex flex-col items-center">
        @include('course.hero', ['classroom' => $classroom, 'level' => $level])
        @include('course.about_course.about-course', ['classroom' => $classroom, 'level' => $level])
    </div>
    @include('components/footer/footer')
    <script src="{{ asset('js/expandable-card.js') }}"></script>
@endsection
