<script src="//unpkg.com/alpinejs" defer></script>

  <!-- This example requires Tailwind CSS v2.0+ -->
<!--
 This example requires updating your template:

 ```
 <html class="h-full bg-gray-100">
 <body class="h-full">
 ```
-->
<div x-data="{ open: false }">
    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
    <div x-show="open" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">
    <!--
    Off-canvas menu overlay, show/hide based on off-canvas menu state.
   
    Entering: "transition-opacity ease-linear duration-300"
    From: "opacity-0"
    To: "opacity-100"
    Leaving: "transition-opacity ease-linear duration-300"
    From: "opacity-100"
    To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>
   
    <!--
    Off-canvas menu, show/hide based on off-canvas menu state.
   
    Entering: "transition ease-in-out duration-300 transform"
    From: "-translate-x-full"
    To: "translate-x-0"
    Leaving: "transition ease-in-out duration-300 transform"
    From: "translate-x-0"
    To: "-translate-x-full"
    -->
    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
    <!--
    Close button, show/hide based on off-canvas menu state.
   
    Entering: "ease-in-out duration-300"
    From: "opacity-0"
    To: "opacity-100"
    Leaving: "ease-in-out duration-300"
    From: "opacity-100"
    To: "opacity-0"
    -->
    <div class="absolute top-0 right-0 -mr-12 pt-2">
    <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
    <span  @click="open = false" class="sr-only">Close sidebar</span>
    <!-- Heroicon name: outline/x -->
    <svg  @click="open = false" class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
    </button>
    </div>
   
    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
    <div class="flex-shrink-0 flex items-center px-4">
      <a href="{{route('home')}}">
    {{-- <img class="h-12 w-auto" src="{{asset(Auth::user()->center->logo_url)}}" alt="Workflow"> --}}
    <img class="h-12 w-auto" src="{{asset('images/logo.png')}}" alt="Workflow">
      </a>
    </div>
    <nav class="mt-5 px-2 space-y-1">
    <!-- Current: "bg-gray-100 text-gray-900", Default: "text-gray-600 hover:bg-gray-50 hover:text-gray-900" -->
    
    
    <a href="{{route('course',["id"=>$classroom->id])}}" class="whitespace-nowrap text-ellipsis overflow-hidden text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
      <!-- Heroicon name: outline/folder -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
      </svg>
      
      <span style="width: 10em" class="whitespace-nowrap text-ellipsis overflow-hidden">
      Kembali
      </span>
      </a>
      
      @php
          $isTeacher = \App\Models\ClassroomTeacher::where('user_id', auth()->user()->id)
              ->where('classroom_id', $classroom->id)
              ->exists();
      @endphp
      
      @if($isTeacher)
      <a href="{{route('raise-hand.teacher-view', $classroom->id)}}" class="whitespace-nowrap text-ellipsis overflow-hidden text-blue-600 hover:bg-blue-50 hover:text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <!-- Heroicon name: outline/hand-raised -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.548 1.548 0 10-3.096 0A1.548 1.548 0 0010.05 4.575zm0 0a1.548 1.548 0 013.096 0A1.548 1.548 0 0110.05 4.575zM10.05 4.575a1.548 1.548 0 013.096 0A1.548 1.548 0 0110.05 4.575zM10.05 4.575a1.548 1.548 0 013.096 0A1.548 1.548 0 0110.05 4.575z" />
        </svg>
        
        <span style="width: 10em" class="whitespace-nowrap text-ellipsis overflow-hidden">
        Raise Hands
        </span>
      </a>
      @endif
    <a href="#" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-base font-medium rounded-md">
      <!-- Heroicon name: outline/folder -->
      <svg class="text-gray-400 group-hover:text-gray-500 mr-4 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
      </svg>
      {{$module->name}}
    </a>

    @foreach($module->units->sortBy("order_number") as $currentUnit)
    <a href="{{route('lesson',["classroom"=>$classroom->id,"id"=>$module->id,"unitId"=>$currentUnit->id])}}" class="text-gray-600 ml-5 {{$unit == $currentUnit ? "bg-gray-100 text-gray-900" : ""}}  hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <!-- Heroicon name: outline/folder -->
        @php
            $isTeacher = \App\Models\ClassroomTeacher::where('user_id', auth()->user()->id)
                ->where('classroom_id', $classroom->id)
                ->exists();
            $hasUnitAccess = $isTeacher || auth()->user()->userUnits->where('unit_id', $currentUnit->id)->first();
        @endphp
        @if(!$hasUnitAccess)
        <svg class=" text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </svg>
        @else
        <svg class=" text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </svg>
        
        @endif
        U{{$loop->index+1}}: {{$currentUnit->name}}
        </a>
        @if($hasUnitAccess)
        <a href="{{ route('lesson', ["classroom"=>$classroom->id,'id' => $module, 'unitId' => $currentUnit]) }}"  class="ml-12 text-gray-600 {{route('lesson',["classroom"=>$classroom->id,"id"=>$module, "unitId" => $currentUnit]) == Request::url() ? "bg-gray-100 text-gray-900":"hover:bg-gray-50 hover:text-gray-900" }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
            <!-- Heroicon name: outline/folder -->
            <svg class=" text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            
           
            Resource
            </a>
            @foreach($currentUnit->exercises as $exercise)
    <a href="{{route('exercise',["classroom"=>$classroom->id,"id"=>$exercise->id])}}" class="ml-12  text-gray-600 {{route('exercise',["classroom"=>$classroom->id,"id"=>$exercise->id]) == Request::url() ? "bg-gray-100 text-gray-900":"hover:bg-gray-50 hover:text-gray-900" }}  group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <!-- Heroicon name: outline/folder -->
        <svg  class=" text-gray-400  group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
        
        @php
        if(auth()->user()->exercises->where("exercise_id",$exercise->id)->first()){

        $user_exercise_questions = auth()->user()->exercises->where("exercise_id",$exercise->id)->first()->user_exercise_questions;
        $score = 0;
        foreach (auth()->user()->exercises->where("exercise_id",$exercise->id)->first()->user_exercise_questions as $answer){
            if($answer->multipleChoiceAnswer){
                if($answer->multipleChoiceAnswer->is_correct_option){
                $score += $answer->exercise_question->score;
            }
            }
         
    
        }
      }
        @endphp
 <span style="width: 9em" class="whitespace-nowrap text-ellipsis overflow-hidden">
  {{$exercise->name}} 
  </span>
  @if(auth()->user()->exercises->where("exercise_id",$exercise->id)->first())
  <span class="text-green-600"> ({{$score}}/{{$exercise->exerciseQuestions->sum("score")}}) </span>
  @endif        </a>
    @endforeach
    @endif
    @endforeach
   

   


   

    </nav>
    </div>
    {{-- <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
    <a href="#" class="flex-shrink-0 group block">
    <div class="flex items-center">
    <div>
    <img class="inline-block h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
    </div>
    <div class="ml-3">
    <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">{{auth()->user()->name}}</p>
    <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
    </div>
    </div>
    </a>
    </div> --}}
    </div>
   
    <div class="flex-shrink-0 w-14">
    <!-- Force sidebar to shrink to fit close icon -->
    </div>
    </div>
   
    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0" style="max-height: 100vh;">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white max-h-screen overflow-y-auto">
    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
    <div class="flex items-center flex-shrink-0 px-4">
      <a href="{{route('home')}}">

    {{-- <img class="h-14 w-auto" src="{{asset(Auth::user()->center->logo_url)}}?id={{now()}}" alt="Workflow"> --}}
    <img class="h-14 w-auto" src="{{asset('images/logo.png')}}?id={{now()}}" alt="Workflow">
      </a>
    </div>
    <nav class="mt-5 flex-1 px-2 bg-white space-y-1">
    <!-- Current: "bg-gray-100 text-gray-900", Default: "text-gray-600 hover:bg-gray-50 hover:text-gray-900" -->
   
    <a href="{{route('course',["id"=>$classroom->id])}}" class="text-gray-600 whitespace-nowrap text-ellipsis overflow-hidden  hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
      <!-- Heroicon name: outline/folder -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
      </svg>
      <span style="width: 10em" class="whitespace-nowrap text-ellipsis overflow-hidden">

  
      Kembali
      </span>
      </a>
      
      @php
          $isTeacher = \App\Models\ClassroomTeacher::where('user_id', auth()->user()->id)
              ->where('classroom_id', $classroom->id)
              ->exists();
      @endphp
      
      @if($isTeacher)
      <a href="{{route('raise-hand.teacher-view', $classroom->id)}}" class="text-blue-600 whitespace-nowrap text-ellipsis overflow-hidden hover:bg-blue-50 hover:text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <!-- Heroicon name: outline/hand-raised -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.548 1.548 0 10-3.096 0A1.548 1.548 0 0010.05 4.575zm0 0a1.548 1.548 0 013.096 0A1.548 1.548 0 0110.05 4.575zM10.05 4.575a1.548 1.548 0 013.096 0A1.548 1.548 0 0110.05 4.575zM10.05 4.575a1.548 1.548 0 013.096 0A1.548 1.548 0 0110.05 4.575z" />
        </svg>
        <span style="width: 10em" class="whitespace-nowrap text-ellipsis overflow-hidden">
        Raise Hands
        </span>
      </a>
      @endif

    <a href="#" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <!-- Heroicon name: outline/folder -->
    <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
    </svg>

    Module: {{$module->name}}
    </a>
    @foreach($module->units->sortBy("order_number") as $currentUnit)
    <a href="{{route('lesson',["classroom"=>$classroom->id,"id"=>$module->id,"unitId"=>$currentUnit->id])}}" class="text-gray-600 ml-5 {{$unit == $currentUnit ? "bg-gray-100 text-gray-900" : ""}}  hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <!-- Heroicon name: outline/folder -->
        @php
            $isTeacher = \App\Models\ClassroomTeacher::where('user_id', auth()->user()->id)
                ->where('classroom_id', $classroom->id)
                ->exists();
            $hasUnitAccess = $isTeacher || auth()->user()->userUnits->where('unit_id', $currentUnit->id)->first();
        @endphp
        @if(!$hasUnitAccess)
        <svg class=" text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </svg>
        @else
        <svg class=" text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
        </svg>
        
        @endif
        U{{$loop->index+1}}: {{$currentUnit->name}}
        </a>
        @if($hasUnitAccess)
        <a href="{{ route('lesson', ["classroom"=>$classroom->id,'id' => $module, 'unitId' => $currentUnit]) }}"  class="ml-12 text-gray-600 {{route('lesson',["classroom"=>$classroom->id,"id"=>$module, "unitId" => $currentUnit]) == Request::url() ? "bg-gray-100 text-gray-900":"hover:bg-gray-50 hover:text-gray-900" }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
            <!-- Heroicon name: outline/folder -->
            <svg class=" text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            
           
            Resource
            </a>
            @foreach($currentUnit->exercises as $exercise)
    <a href="{{route('exercise',["classroom"=>$classroom->id,"id"=>$exercise->id])}}" class="ml-12  text-gray-600 {{route('exercise',["classroom"=>$classroom->id,"id"=>$exercise->id]) == Request::url() ? "bg-gray-100 text-gray-900":"hover:bg-gray-50 hover:text-gray-900" }}  group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <!-- Heroicon name: outline/folder -->
        <svg  class=" text-gray-400  group-hover:text-gray-500 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
        @php
        if(auth()->user()->exercises->where("exercise_id",$exercise->id)->first()){

        $user_exercise_questions = auth()->user()->exercises->where("exercise_id",$exercise->id)->first()->user_exercise_questions;
        $score = 0;
        foreach (auth()->user()->exercises->where("exercise_id",$exercise->id)->first()->user_exercise_questions as $answer){
            if($answer->multipleChoiceAnswer){
                if($answer->multipleChoiceAnswer->is_correct_option){
                $score += $answer->exercise_question->score;
            }
            }
         
    
        }
      }
        @endphp
        <span style="width: 9em" class="whitespace-nowrap text-ellipsis overflow-hidden">
        {{$exercise->name}} 
        </span>
        @if(auth()->user()->exercises->where("exercise_id",$exercise->id)->first())
        <span class="text-green-600"> ({{$score}}/{{$exercise->exerciseQuestions->sum("score")}}) </span>
        @endif
        </a>
    @endforeach
    @endif
    @endforeach

      </nav>
    </div>
    {{-- <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
    <a href="#" class="flex-shrink-0 w-full group block">
    <div class="flex items-center">
    <div>
      @php
      $initials = strtoupper(substr(auth()->user()->name, 0, 2)); // Change this based on your logic
      $avatar = Avatar::create($initials)->setBackground('#cb5283')->toBase64();
      @endphp
      <img class="inline-block h-9 w-9 rounded-full" src="{{ $avatar }}" alt="">
    </div>
    <div class="ml-3">
    <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{auth()->user()->name}}</p>
    <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">View profile</p>
    </div>
    </div>
    </a>
    </div> --}}
    </div>
    </div>
    </div>
    <div class="md:pl-64 flex flex-col flex-1 h-screen">
    <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-100">
    <button type="button" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
    <span class="sr-only">Open sidebar</span>
    <!-- Heroicon name: outline/menu -->
    <svg class="h-6 w-6"  @click="open = true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
    </button>
    </div>
    <main class="flex-1">

    <div class=" mx-auto h-screen">
    <!-- Replace with your content -->
        {{$slot}}
    <!-- /End replace --
    </div>
    </div>
    </main>
    </div>
   </div>