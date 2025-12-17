@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $classroom->name }} Point Statistics</h1>
            <p class="mt-2 text-gray-600">Detailed point analysis for all students</p>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $totalStudents = count($stats);
                $totalPoints = array_sum(array_column($stats, 'current_points'));
                $avgPoints = $totalStudents > 0 ? round($totalPoints / $totalStudents, 1) : 0;
                $maxPoints = max(array_column($stats, 'current_points'));
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-2xl font-bold text-blue-600">{{ $totalStudents }}</div>
                <div class="text-sm text-gray-500 mt-1">Total Students</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-2xl font-bold text-green-600">{{ $totalPoints }}</div>
                <div class="text-sm text-gray-500 mt-1">Total Points</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-2xl font-bold text-purple-600">{{ $avgPoints }}</div>
                <div class="text-sm text-gray-500 mt-1">Average Points</div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-2xl font-bold text-orange-600">{{ $maxPoints }}</div>
                <div class="text-sm text-gray-500 mt-1">Highest Points</div>
            </div>
        </div>

        <!-- Student Details Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Student Point Details</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Current Points</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Points Earned</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Points Spent</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Units Completed</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rewards Redeemed</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stats as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-pink-600">
                                                {{ strtoupper(substr($student['name'], 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $student['name'] }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $student['user_id'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-lg font-bold text-pink-600">{{ $student['current_points'] }}</div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-green-600">+{{ $student['detailed_calculation']['points_earned'] }}</div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-red-600">-{{ $student['detailed_calculation']['points_spent'] }}</div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-blue-600">{{ $student['detailed_calculation']['completed_units'] }}</div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-orange-600">{{ $student['detailed_calculation']['rewards_redeemed'] }}</div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button class="text-pink-600 hover:text-pink-900 text-sm font-medium" 
                                            onclick="showStudentDetails({{ $student['user_id'] }})">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No students found in this classroom.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center space-x-4">
            <a href="{{ route('student-points.leaderboard', $classroom->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                View Leaderboard
            </a>
            
            <a href="{{ route('course', $classroom->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Classroom
            </a>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div id="studentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Student Details</h3>
                <button onclick="closeStudentDetails()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="studentDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showStudentDetails(userId) {
    // This would typically make an AJAX call to get detailed student information
    // For now, we'll just show a simple message
    document.getElementById('studentDetailsContent').innerHTML = `
        <p class="text-gray-600">Detailed information for student ID: ${userId}</p>
        <p class="text-sm text-gray-500 mt-2">This would show detailed point history and breakdown.</p>
    `;
    document.getElementById('studentDetailsModal').classList.remove('hidden');
}

function closeStudentDetails() {
    document.getElementById('studentDetailsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('studentDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStudentDetails();
    }
});
</script>
@endsection 