@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
    <div style="  background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9; "
        class="relative">
        @include('components.nav_bar.nav-bar')
        @include('all_courses.hero')
    </div>
    @include('all_courses.pill-menu')
    @include('all_courses.courses.courses')
    @include('components/footer/footer')
@endsection