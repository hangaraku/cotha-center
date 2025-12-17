@extends('layouts.app')

@section('title', 'Showcase - Student Projects')

@section('content')
<div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
    @include('components.nav_bar.nav-bar')
    
    <!-- Hero Section -->
    <div class="relative z-10 px-8 sm:px-16 lg:px-24 xl:px-48 py-16">
        <div class="max-w-4xl mx-auto text-left">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 text-white">🎨 Student Showcase</h1>
            <p class="text-xl md:text-2xl opacity-90 text-white mb-8">Discover amazing projects created by our talented students. Like, love, and star your favorites!</p>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="relative z-10 -mt-10 px-4 sm:px-8 md:px-16 lg:px-24 pb-16">
    <div class="max-w-7xl mx-auto">

        <!-- Section Tabs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row items-center gap-4">
                <!-- Section Toggle -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button onclick="showSection('students')" 
                            id="students-tab"
                            class="flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-500 hover:text-gray-700">
                        👥 Students
                    </button>
                    <button onclick="showSection('projects')" 
                            id="projects-tab"
                            class="flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors bg-white text-gray-900 shadow-sm">
                        🎨 Projects
                    </button>
                </div>

                <!-- Search (shown in both students and projects tabs) -->
                <div class="flex-1 relative" id="search-container">
                    <form method="GET" action="{{ route('showcase.index') }}" class="flex gap-2">
                        <input type="hidden" name="tab" id="tab-input" value="{{ request('tab', 'projects') }}">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   name="search" 
                                   id="search-input"
                                   value="{{ request('search') }}"
                                   placeholder="Search projects, creators, or descriptions..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            
                            <!-- User Suggestions Dropdown -->
                            <div id="search-suggestions" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden">
                                <div class="p-2">
                                    <p class="text-xs font-medium text-gray-500 mb-2">Creators:</p>
                                    <div id="suggestions-list">
                                        <!-- Suggestions will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Students Section -->
        <div id="students-section" style="display: none;">
            @if($students->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($students as $student)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <!-- Student Avatar -->
                            <div class="relative p-6 text-center">
                                @if($student->user->profile_picture)
                                    <img src="{{ asset('uploads/' . $student->user->profile_picture) }}" 
                                         alt="{{ $student->user->name }}"
                                         loading="lazy"
                                         width="80"
                                         height="80"
                                         class="w-20 h-20 rounded-full object-cover mx-auto mb-4 border-2 border-gray-200">
                                @else
                                    <div class="w-20 h-20 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                                        {{ substr($student->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $student->user->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $student->school ?? 'No school specified' }}</p>
                                <p class="text-xs text-gray-500 mb-4">{{ $student->user->userProjects->count() }} projects</p>
                                
                                <!-- Student Details -->
                                <div class="text-xs text-gray-500 space-y-1">
                                    @if($student->city)
                                        <p>📍 {{ $student->city }}</p>
                                    @endif
                                    @if($student->user->point)
                                        <p>⭐ {{ $student->user->point }} points</p>
                                    @endif
                                </div>
                            </div>

                            <!-- View Profile Button -->
                            <div class="p-4 pt-0">
                                <a href="{{ route('showcase.user-profile', $student->user->slug) }}" 
                                   class="w-full block text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    View Profile & Projects
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State for Students -->
                <div class="text-center py-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Students Found</h3>
                        <p class="text-gray-500">
                            @if(request('search'))
                                No students match your search "{{ request('search') }}". Try a different search term.
                            @else
                                No students have shared projects yet.
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Projects Section -->
        <div id="projects-section">
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($projects as $project)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Project Thumbnail -->
                        <div class="relative">
                            @if($project->thumbnail)
                                <img src="{{ asset('uploads/' . $project->thumbnail) }}" 
                                     alt="{{ $project->title }}"
                                     loading="lazy"
                                     width="400"
                                     height="300"
                                     class="w-full h-48 object-cover lazy-image">
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
                            <!-- Creator Info -->
                            <a href="{{ route('showcase.user-profile', $project->user->slug) }}" class="flex items-center gap-3 mb-3 hover:opacity-80 transition-opacity">
                                @if($project->user->profile_picture)
                                    <img src="{{ asset('uploads/' . $project->user->profile_picture) }}" 
                                         alt="{{ $project->user->name }}"
                                         loading="lazy"
                                         width="32"
                                         height="32"
                                         class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($project->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $project->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $project->created_at->diffForHumans() }}</p>
                                </div>
                            </a>

                            <!-- Project Title -->
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ $project->url }}" 
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   onclick="incrementView({{ $project->id }})"
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $project->title }}
                                </a>
                            </h3>
                            
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
                {{ $projects->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Projects Found</h3>
                    <p class="text-gray-500">
                        @if(request('search') || request('type') !== 'all')
                            Try adjusting your search or filter criteria.
                        @else
                            No projects have been shared yet. Be the first to showcase your work!
                        @endif
                    </p>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
</div>

<script>
// CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Lazy loading enhancement - remove shimmer when images are loaded
document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    lazyImages.forEach(img => {
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        }
    });
});

// Section switching functionality
function showSection(section) {
    const studentsTab = document.getElementById('students-tab');
    const projectsTab = document.getElementById('projects-tab');
    const studentsSection = document.getElementById('students-section');
    const projectsSection = document.getElementById('projects-section');
    const searchInput = document.getElementById('search-input');
    const tabInput = document.getElementById('tab-input');

    if (section === 'students') {
        studentsTab.className = 'flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors bg-white text-gray-900 shadow-sm';
        projectsTab.className = 'flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-500 hover:text-gray-700';
        studentsSection.style.display = 'block';
        projectsSection.style.display = 'none';
        searchInput.placeholder = 'Search students, schools...';
        tabInput.value = 'students';
    } else {
        studentsTab.className = 'flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-500 hover:text-gray-700';
        projectsTab.className = 'flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors bg-white text-gray-900 shadow-sm';
        studentsSection.style.display = 'none';
        projectsSection.style.display = 'block';
        searchInput.placeholder = 'Search projects, creators, or descriptions...';
        tabInput.value = 'projects';
    }
}

// Restore active tab from URL parameter on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'projects'; // Default to projects
    showSection(activeTab);
});

// Real-time search functionality
let searchTimeout;
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('search-suggestions');
    const suggestionsList = document.getElementById('suggestions-list');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                suggestionsContainer.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('showcase.search-suggestions') }}?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.users && data.users.length > 0) {
                        suggestionsList.innerHTML = data.users.map(user => {
                            const avatar = user.profile_picture 
                                ? `<img src="{{ asset('uploads/') }}/${user.profile_picture}" alt="${user.name}" class="w-8 h-8 rounded-full object-cover">`
                                : `<div class="w-8 h-8 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">${user.name.charAt(0)}</div>`;
                            
                            return `
                                <a href="{{ route('showcase.user-profile', '') }}/${user.slug}" 
                                   class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                    ${avatar}
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">${user.name}</p>
                                        <p class="text-xs text-gray-500">${user.user_projects_count} projects</p>
                                    </div>
                                </a>
                            `;
                        }).join('');
                        suggestionsContainer.classList.remove('hidden');
                    } else {
                        suggestionsContainer.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    suggestionsContainer.classList.add('hidden');
                });
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                suggestionsContainer.classList.add('hidden');
            }
        });
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

/* Lazy loading styles */
img[loading="lazy"] {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

img[loading="lazy"].loaded {
    animation: none;
    background: none;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}
</style>
@endsection
