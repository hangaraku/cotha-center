@if(App\Models\Center::where('name',request()->segment(count(request()->segments())))->first())




@extends("layouts.app")
<!--
 This example requires Tailwind CSS v2.0+ 
 
 This example requires some changes to your config:
 
 ```
 // tailwind.config.js
 module.exports = {
 // ...
 plugins: [
 // ...
 require('@tailwindcss/forms'),
 ],
 }
 ```
-->
<!--
 This example requires updating your template:

 ```
 <html class="h-full bg-gray-50">
 <body class="h-full">
 ```
-->

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-24 bg-gray-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
    @if(App\Models\Center::where('name',request()->segment(count(request()->segments())))->first()->logo_url)
    <img class="mx-auto h-24 w-auto" src="{{App\Models\Center::where('name',request()->segment(count(request()->segments())))->first()->logo_url}}" alt="Workflow">
    
    @else
    <img class="mx-auto h-14 w-auto" src="{{asset('images/logo.png')}}?id={{now()}}" alt="Workflow">
    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{strtoupper(request()->segment(count(request()->segments())))}}</h2>

    @endif

    </div>
   
    {{-- <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
    <form action="{{route('registerstudent')}}" method="POST" class="space-y-6" action="#" method="POST">
    @csrf
    <div>
     
        <input type="hidden" name="center" value="{{App\Models\Center::where('name',request()->segment(count(request()->segments())))->first()->id}}">

    <div>

    <div>
        <label for="password" class="block mt-4 text-sm font-medium text-gray-700"> Full Name </label>
        <div class="mt-1">
        <input id="password" name="fullname" type="text" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
     </div>

    <div>
    <label for="email" class="block  mt-4 text-sm font-medium text-gray-700"> Email address </label>
    <div class="mt-1">
    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
    </div>


    <div>
        <label for="password" class="block mt-4 text-sm font-medium text-gray-700"> Password </label>
        <div class="mt-1">
        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        </div>
   
    <div class="flex items-center justify-between">
    <div class="flex items-center">
    </div>

    </div>
   
    <div>
    <button type="submit" class="w-full mt-4 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Register</button>
    </div>
    </form>
   
    <div class="mt-6">
    <div class="relative">
    <div class="absolute inset-0 flex items-center">
    <div class="w-full border-t border-gray-300"></div>
    </div>
    
    
   
 
    </div>
    </div>
    </div>
    </div> --}}


    <div class="space-y-6 mt-5  sm:px-96 lg:px-0 lg:col-span-9 ">
        <section aria-labelledby="payment-details-heading">
            <form action="{{route('registerstudent')}}" method="POST" class="space-y-6" action="#" method="POST">
                @csrf
                <div>
                 
                    <input type="hidden" name="center" value="{{App\Models\Center::where('name',request()->segment(count(request()->segments())))->first()->id}}">
                <div>


        <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 sm:p-6">
        <div>
        <h2 id="payment-details-heading" class="text-lg leading-6 font-medium text-gray-900">User Registration</h2>
        </div>
       
        <div class="mt-6 grid grid-cols-4 gap-6">
        <div class="col-span-4 sm:col-span-1">
        <label for="first-name" class="block text-sm font-medium text-gray-700">Full name</label>
        <input type="text" name="fullname" id="first-name" autocomplete="cc-given-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
        </div>
        <div class="col-span-4 sm:col-span-1">
            <label for="first-name" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" id="first-name" autocomplete="cc-given-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
            </div>
        <div class="col-span-4 sm:col-span-1">
        <label for="last-name" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input type="text" name="email" id="last-name" autocomplete="cc-family-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
        </div>
       
        <div class="col-span-4 sm:col-span-1">
            <label for="last-name" class="block text-sm font-medium text-gray-700">Gender</label>
            <select id="country" name="gender" autocomplete="country-name" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
                <option>Male</option>
                <option>Female</option>
                </select>
        </div>

        <div class="col-span-4 sm:col-span-1">
        <label for="email-address" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="email-address" autocomplete="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
        </div>
       
        <div class="col-span-4 sm:col-span-1">
        <label for="expiration-date" class="block text-sm font-medium text-gray-700">Birthdate</label>
        <input type="date" name="birthdate" id="expiration-date" autocomplete="cc-exp" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" placeholder="MM / YY">
        </div>
       
        <div class="col-span-4 sm:col-span-1">
        <label for="security-code" class="flex items-center text-sm font-medium text-gray-700">
        <span>School</span>
        <!-- Heroicon name: solid/question-mark-circle -->
     
        </label>
        <input type="text" name="school" id="security-code" autocomplete="cc-csc" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
        </div>
       
        <div class="col-span-4 sm:col-span-1">
            <label for="security-code" class="flex items-center text-sm font-medium text-gray-700">
            <span>City</span>
            <!-- Heroicon name: solid/question-mark-circle -->
         
            </label>
            <input type="text" name="city" id="security-code" autocomplete="cc-csc" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
            </div>
       
   
        </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <button type="submit" class="bg-orange-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-900">Register</button>
        </div>
        </div>
        </form>
        </section>
   </div>
@else
<h1>Center not found. <a class="text-blue-500" href="{{route('home')}}">Back to home</a></h1>
@endif