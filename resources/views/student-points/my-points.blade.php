@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Points</h1>
            <p class="mt-2 text-gray-600">Track your progress and point history</p>
        </div>

        <!-- Points Summary Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Points -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-pink-600">{{ $pointCalculation['net_points'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Points</div>
                </div>
                
                <!-- Points Earned -->
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">+{{ $pointCalculation['points_earned'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Points Earned</div>
                </div>
                
                <!-- Points Spent -->
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">-{{ $pointCalculation['points_spent'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Points Spent</div>
                </div>
                
                <!-- Completed Units -->
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $pointCalculation['completed_units'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Units Completed</div>
                </div>
            </div>
        </div>

        <!-- Point History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Point History</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($pointHistory as $entry)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                @if($entry['type'] === 'unit_completed')
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div>
                                    <div class="font-medium text-gray-900">{{ $entry['description'] }}</div>
                                    @if(isset($entry['module']))
                                        <div class="text-sm text-gray-500">{{ $entry['module'] }}</div>
                                    @endif
                                    @if(isset($entry['status']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($entry['status'] === 'claimed') bg-green-100 text-green-800
                                            @elseif($entry['status'] === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($entry['status'] === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($entry['status']) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-lg font-semibold @if($entry['points'] > 0) text-green-600 @else text-red-600 @endif">
                                @if($entry['points'] > 0)+@endif{{ $entry['points'] }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($entry['date'])->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <div class="text-gray-500">No point history yet. Complete units to earn points!</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center">
            <a href="{{ route('rewards.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Redeem Rewards
            </a>
        </div>
    </div>
</div>
@endsection 