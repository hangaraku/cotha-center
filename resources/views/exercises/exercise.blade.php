@extends('layouts.app')

@section('title', 'Lesson')

@section('content')
    
    @include('components.nav_bar.nav-bar', ['isCoursePage' => true])
    <div class="w-full min-h-[220px] md:min-h-[300px] lg:min-h-[320px] flex flex-col items-start justify-center bg-gradient-to-r from-blue-500 to-blue-600 px-8 md:px-16 lg:px-24 py-8 md:py-12 text-white">
        <div class="text-base md:text-lg font-medium opacity-80 mb-1">
            @if($module && $module->level)
                {{ $module->level->name }} &mdash; {{ $classroom->name }}
            @else
                {{ $classroom->name }}
            @endif
        </div>
        <h1 class="text-2xl md:text-4xl font-extrabold leading-tight">
            {{ $module->name ?? 'Exercise' }}
        </h1>
    </div>
    
    <div class="relative z-10 -mt-20 px-8 md:px-16 lg:px-24 pb-16">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 w-full text-black">
            <h1 class="text-3xl mb-8 font-bold text-blue-900">{{ $exercise->name }}</h1>
            
            @if($isTeacher)
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-blue-800 font-medium">Teacher View - You can see correct answers and explanations</span>
                    </div>
                </div>
            @endif
            
            @if(auth()->user()->exercises->where('exercise_id',$exercise->id)->first())
            @php
            $user_exercise_questions = auth()->user()->exercises->where("exercise_id",$exercise->id)->first()->user_exercise_questions;
            $score = 0;
            foreach (auth()->user()->exercises->where("exercise_id",$exercise->id)->first()->user_exercise_questions as $answer){
                if($answer->multipleChoiceAnswer){
                    if($answer->multipleChoiceAnswer->is_correct_option){
                    $score += $answer->exercise_question->score;
                }
                }
            }
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-xl text-green-800"> You have finished this exercise </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-xl text-green-800"> Score: {{$score}}/{{$exercise->exerciseQuestions->sum("score")}} </span>
            @endif
            
            <form method="POST" action="{{route('saveAnswer')}}">
                @csrf
                <input type="hidden" value="{{$exercise->id}}" name="exercise">
                @foreach($exercise->exerciseQuestions as $question)
                    <div class="mb-8">
                        <div class="bg-white rounded-lg p-6 mb-4">
                            <div class="flex items-start mb-4">
                                <div class="mr-4 text-2xl font-bold text-blue-700 flex-shrink-0">{{$loop->index+1}}.</div>
                                <div class="text-lg font-medium text-gray-900">{!! $question->question !!}</div>
                            </div>
                            <fieldset>
                                <legend class="sr-only">Answer choices</legend>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                    @php
                                        $user_answer_id = null;
                                        if(isset($user_exercise_questions)) {
                                            $user_question = $user_exercise_questions->where('exercise_question_id', $question->id)->first();
                                            if($user_question) {
                                                $user_answer_id = $user_question->answer_id;
                                            }
                                        }
                                        $is_submitted = isset($user_exercise_questions);
                                    @endphp
                                    @foreach($question->multipleChoiceAnswers as $answer)
                                        @php
                                            $is_correct = $answer->is_correct_option;
                                            $is_user_answer = $is_submitted && $user_answer_id == $answer->id;
                                            $show_correct = ($isTeacher && $is_correct) || ($is_submitted && $is_correct);
                                            $show_user_answer = $is_submitted && $is_user_answer;
                                            
                                            $border_class = '';
                                            $bg_class = '';
                                            if($isTeacher) {
                                                if($is_correct) {
                                                    $border_class = 'border-green-500';
                                                    $bg_class = 'bg-green-50';
                                                }
                                            } else if($is_submitted) {
                                                if($is_correct) {
                                                    $border_class = 'border-green-500';
                                                    $bg_class = 'bg-green-50';
                                                } else if($is_user_answer) {
                                                    $border_class = 'border-red-500';
                                                    $bg_class = 'bg-red-50';
                                                }
                                            }
                                        @endphp
                                        <label class="flex items-center p-3 rounded-lg cursor-pointer transition hover:bg-blue-50 border border-transparent focus-within:border-blue-400 {{ $border_class }} {{ $bg_class }}">
                                            <input id="answer-{{$question->id}}-{{$answer->id}}" value="{{$answer->id}}" name="question{{$question->id}}" type="radio" class="custom-radio h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 mr-4"
                                                @if($is_submitted && $user_answer_id == $answer->id) checked @endif
                                                @if($is_submitted || $isTeacher) disabled @endif
                                            />
                                            <span class="text-base text-gray-900 flex items-center">
                                                {{$answer->text}}
                                                @if($answer->img)
                                                    <img class="h-12 ml-3 rounded floating-img-border" src="{{asset('uploads/'.$answer->img)}}"/>
                                                @endif
                                                @if($show_correct)
                                                    <span class="ml-2 text-green-600 font-semibold">✓ Correct</span>
                                                @endif
                                                @if($show_user_answer && !$is_correct)
                                                    <span class="ml-2 text-red-600 font-semibold">✗ Your Answer</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            
                            @if(($isTeacher || auth()->user()->exercises->where('exercise_id',$exercise->id)->first()) && $question->explanation)
                                <div class="questionbox border-l-4 border-green-700 p-5 mt-10 rounded-md bg-green-50">
                                    <h2 class="font-semibold text-green-700 mb-2">Penjelasan:</h2>
                                    <div class="text-green-900">{!! $question->explanation !!}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                @if(!auth()->user()->exercises->where('exercise_id',$exercise->id)->first() && !$isTeacher)
                <div class="flex justify-end mt-8">
                    <input type="submit" value="Selesai" class="inline-flex items-center px-5 py-2 border border-transparent text-base leading-5 font-semibold rounded-full shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition"/>
                </div>
                @endif
            </form>
        </div>
    </div>
    
    <script src="{{ asset('js/tab.js') }}"></script>
    <style>
        #TEST ul{
            list-style-type: disc;
        }

        #TEST p{
            margin-bottom: 15px;
        }

        .custom-radio {
            appearance: none;
            background-color: #fff;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            width: 1.25em;
            height: 1.25em;
            display: inline-block;
            position: relative;
            transition: border 0.2s;
        }
        .custom-radio:checked {
            border: 6px solid #2563eb;
            background-color: #fff;
        }
        .custom-radio:focus {
            outline: none;
            box-shadow: 0 0 0 2px #2563eb33;
        }
        .floating-img-border {
            border: 2px solid #cbd5e1;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px 0 rgba(0,0,0,0.07);
            background: #fff;
            padding: 0.25rem;
            display: inline-block;
            max-width: 4.5rem;
            max-height: 4.5rem;
            object-fit: contain;
            vertical-align: middle;
        }
        .floating-img-border[title], .floating-img-border[size] {
            title: none !important;
            size: none !important;
        }
        .attachment__caption {
            display: none !important;
        }
        figure img {
            margin-top: 0.75rem !important; /* Tailwind m-3 = 0.75rem */
            margin-bottom: 0.75rem !important; /* Tailwind m-3 = 0.75rem */
            border: 2px solid #000 !important;
            border-radius: 0.5rem;
        }
    </style>

    <script>
        // Find all elements with the class "questionbox"
var questionBoxes = document.querySelectorAll('.questionbox');

// Loop through each questionbox
questionBoxes.forEach(function(questionBox) {
  // Find all <figure> elements inside the current questionbox
  var figures = questionBox.querySelectorAll('figure');

  // Loop through each figure element
  figures.forEach(function(figure) {
    // Find all <a> elements inside the current figure
    var anchorTags = figure.querySelectorAll('a');

    // Loop through each <a> element and remove the href attribute
    anchorTags.forEach(function(a) {
      a.removeAttribute('href');
    });
  });
});

        </script>

@include('components/footer/footer')
@endsection