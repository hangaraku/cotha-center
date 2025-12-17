@extends('layouts.app')

@section('title', $user->name . ' - Creator Profile')

@section('content')
<div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
    @include('components.nav_bar.nav-bar')
    
    <!-- Hero Section -->
    <div class="relative z-10 px-8 sm:px-16 lg:px-24 xl:px-48 py-16">
        <div class="max-w-4xl mx-auto text-left">
            <div class="flex items-center gap-6 mb-6">
                @if($user->profile_picture)
                    <img src="{{ asset('uploads/' . $user->profile_picture) }}" 
                         alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-20 h-20 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-4xl md:text-6xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-xl md:text-2xl opacity-90 text-white">{{ $user->user_projects_count }} Projects Created</p>
                </div>
            </div>
            <a href="{{ route('showcase.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-lg transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Showcase
            </a>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="relative z-10 -mt-10 px-4 sm:px-8 md:px-16 lg:px-24 pb-16">
    <div class="max-w-7xl mx-auto">
        
        <!-- Student Information Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Student Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                    <div class="space-y-3">
                        @if($user->students)
                            @php $student = $user->students; @endphp
                            @if($student->school)
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500">🏫</span>
                                    <span class="text-sm text-gray-700">{{ $student->school }}</span>
                                </div>
                            @endif
                            @if($student->city)
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500">📍</span>
                                    <span class="text-sm text-gray-700">{{ $student->city }}</span>
                                </div>
                            @endif
                        @endif
                        @if($user->point)
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500">⭐</span>
                                <span class="text-sm text-gray-700">{{ $user->point }} points earned</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $user->user_projects_count }}</div>
                            <div class="text-sm text-gray-500">Projects</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            @php
                                $totalViews = $projects->sum('views');
                                if ($totalViews < 10) {
                                    $totalViews += rand(10, 20);
                                }
                            @endphp
                            <div class="text-2xl font-bold text-green-600">
                                {{ $totalViews }}
                            </div>
                            <div class="text-sm text-gray-500">Views</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            @php
                                $totalInteractions = $projects->sum(function($project) { 
                                    return $project->likes_count + $project->loves_count + $project->stars_count; 
                                });
                                if ($totalInteractions < 10) {
                                    $totalInteractions += rand(10, 20);
                                }
                            @endphp
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $totalInteractions }}
                            </div>
                            <div class="text-sm text-gray-500">Interactions</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $user->point ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-500">Points</div>
                        </div>
                    </div>
                    
                    <!-- See Certificate Button -->
                    <div class="mt-4 hidden">
                        <button onclick="openCertificateModal()" 
                                class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            See Certificate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($projects as $project)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Project Thumbnail -->
                        <div class="relative">
                            @if($project->thumbnail)
                                <img src="{{ asset('uploads/' . $project->thumbnail) }}" 
                                     alt="{{ $project->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium">{{ $project->type }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Project Type Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-white bg-opacity-90 text-gray-800 text-xs font-medium rounded-full">
                                    {{ $project->type }}
                                </span>
                            </div>

                            <!-- View Count -->
                            <div class="absolute top-3 right-3" data-project-id="{{ $project->id }}">
                                <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs font-medium rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="view-count">{{ $project->views }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Project Content -->
                        <div class="p-4">
                            <!-- Project Title -->
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $project->title }}</h3>
                            
                            <!-- Project Description -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $project->description }}</p>

                            <!-- Interaction Buttons -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <!-- Like Button -->
                                    <button onclick="toggleInteraction({{ $project->id }}, 'like')" 
                                            class="flex items-center gap-1 text-sm transition-colors {{ $project->user_liked ? 'text-blue-500' : 'text-gray-500 hover:text-blue-500' }}">
                                        <svg class="w-4 h-4" fill="{{ $project->user_liked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                        </svg>
                                        <span>{{ $project->likes_count }}</span>
                                    </button>

                                    <!-- Love Button -->
                                    <button onclick="toggleInteraction({{ $project->id }}, 'love')" 
                                            class="flex items-center gap-1 text-sm transition-colors {{ $project->user_loved ? 'text-pink-500' : 'text-gray-500 hover:text-pink-500' }}">
                                        <svg class="w-4 h-4" fill="{{ $project->user_loved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span>{{ $project->loves_count }}</span>
                                    </button>

                                    <!-- Star Button -->
                                    <button onclick="toggleInteraction({{ $project->id }}, 'star')" 
                                            class="flex items-center gap-1 text-sm transition-colors {{ $project->user_starred ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }}">
                                        <svg class="w-4 h-4" fill="{{ $project->user_starred ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        <span>{{ $project->stars_count }}</span>
                                    </button>
                                </div>

                                <!-- View Project Button -->
                                <a href="{{ $project->url }}" 
                                   target="_blank" 
                                   onclick="incrementView({{ $project->id }})"
                                   class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-full hover:bg-blue-700 transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $projects->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Projects Yet</h3>
                    <p class="text-gray-500">{{ $user->name }} hasn't shared any projects yet.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Certificate Selection Modal -->
<div id="certificateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Select Course Certificate</h3>
            <button onclick="closeCertificateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <p class="text-sm text-gray-600 mb-4">Choose a course to view {{ $user->name }}'s certificate:</p>
        
        <div id="classroomsList" class="space-y-2 max-h-64 overflow-y-auto">
            <div class="flex items-center justify-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end">
            <button onclick="closeCertificateModal()" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
// CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Certificate Modal Functions
function openCertificateModal() {
    document.getElementById('certificateModal').classList.remove('hidden');
    loadClassrooms();
}

function closeCertificateModal() {
    document.getElementById('certificateModal').classList.add('hidden');
}

function loadClassrooms() {
    const classroomsList = document.getElementById('classroomsList');
    
    fetch('{{ route("showcase.user-classrooms", ["slug" => $user->slug]) }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.classrooms.length === 0) {
            classroomsList.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>No courses available for certificates.</p>
                </div>
            `;
            return;
        }
        
        classroomsList.innerHTML = data.classrooms.map(classroom => `
            <a href="/showcase/certificate/{{ $user->id }}/${classroom.id}" 
               class="block p-4 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">${classroom.name}</h4>
                        ${classroom.name !== classroom.original_name ? `<p class="text-xs text-gray-500">${classroom.original_name}</p>` : ''}
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        `).join('');
    })
    .catch(error => {
        console.error('Error:', error);
        classroomsList.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <p>Failed to load courses. Please try again.</p>
            </div>
        `;
    });
}

// Close modal when clicking outside
document.getElementById('certificateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCertificateModal();
    }
});

function toggleInteraction(projectId, type) {
    @guest
        // Redirect to login if user is not authenticated
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    const button = event.target.closest('button');
    const countSpan = button.querySelector('span');
    const icon = button.querySelector('svg');
    
    // Disable button during request
    button.disabled = true;
    
    fetch('{{ route("showcase.toggle-interaction") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            project_id: projectId,
            type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
            return;
        }
        
        // Update count
        countSpan.textContent = data.counts[type + 's'];
        
        // Update button state
        if (data.action === 'added') {
            button.classList.remove('text-gray-500');
            button.classList.add(type === 'like' ? 'text-blue-500' : type === 'love' ? 'text-pink-500' : 'text-yellow-500');
            icon.setAttribute('fill', 'currentColor');
        } else {
            button.classList.remove(type === 'like' ? 'text-blue-500' : type === 'love' ? 'text-pink-500' : 'text-yellow-500');
            button.classList.add('text-gray-500');
            icon.setAttribute('fill', 'none');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
    });
}

function incrementView(projectId) {
    fetch('{{ route("showcase.increment-view") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            project_id: projectId
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update view count in the UI
        const viewSpan = document.querySelector(`[data-project-id="${projectId}"] .view-count`);
        if (viewSpan) {
            viewSpan.textContent = data.views;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
