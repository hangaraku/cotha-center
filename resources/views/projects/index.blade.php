@extends('layouts.app')

@section('title', 'Project Saya')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        
        <!-- Hero Section -->
        <div class="relative z-10 px-8 sm:px-16 lg:px-24 xl:px-48 py-16">
            <div class="max-w-4xl mx-auto text-left">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 text-white">Project Saya</h1>
                <p class="text-xl md:text-2xl opacity-90 text-white mb-8">Lihat semua project yang telah Anda submit</p>
                <button onclick="openAddModal()" class="inline-flex items-center px-6 py-3 bg-[#da5597] hover:bg-[#c94c89] text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Project
                </button>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="relative z-10 -mt-10 px-4 sm:px-8 md:px-16 lg:px-24 pb-16">
        <div class="max-w-7xl mx-auto">
        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    @if($project->thumbnail)
                        <div class="h-32 bg-gray-200 overflow-hidden">
                            <img src="{{ asset('uploads/' . $project->thumbnail) }}" 
                                 alt="{{ $project->title }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-32 bg-gradient-to-br from-[#da5597] to-[#c94c89] flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-block bg-[#da5597] bg-opacity-10 text-[#da5597] text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $project->type }}
                            </span>
                            <div class="flex items-center space-x-1 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $project->views }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    {{ $project->score }}
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2">{{ $project->title }}</h3>
                        <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $project->description }}</p>
                        
                        @if($project->userModule)
                            <div class="mb-2">
                                <span class="text-xs text-gray-500">Dari modul:</span>
                                <span class="text-xs font-medium text-gray-700 line-clamp-1">{{ $project->userModule->module->title ?? 'Unknown Module' }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                {{ $project->created_at->format('d M Y') }}
                            </span>
                            <div class="flex items-center space-x-1">
                                @if($project->type === 'Scratch')
                                    <button onclick="openScratchModal('{{ $project->url }}', {{ $project->id }}, '{{ $project->title }}')" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-[#da5597] bg-[#da5597] bg-opacity-10 rounded hover:bg-[#da5597] hover:bg-opacity-20 transition-colors duration-200">
                                        Lihat
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ $project->url }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-[#da5597] bg-[#da5597] bg-opacity-10 rounded hover:bg-[#da5597] hover:bg-opacity-20 transition-colors duration-200">
                                        Lihat
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                @endif
                                <button onclick="openEditModal({{ $project->id }}, '{{ $project->title }}', '{{ $project->description }}', '{{ $project->type }}', '{{ $project->url }}')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="confirmDelete({{ $project->id }}, '{{ $project->title }}')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-50 rounded hover:bg-red-100 transition-colors duration-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl shadow-xl p-8">
                <div class="w-24 h-24 bg-[#da5597] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-[#da5597]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">Belum Ada Project</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki project yang disubmit. Mulai belajar dan submit project pertama Anda!</p>
                <button onclick="openAddModal()" class="inline-flex items-center px-6 py-3 bg-[#da5597] hover:bg-[#c94c89] text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Project Pertama
                </button>
            </div>
        @endif
        </div>
    </div>

    <!-- Add Project Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Tambah Project</h3>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Project</label>
                        <input id="title" name="title" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Project</label>
                        <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none">
                            <option value="">Pilih tipe project</option>
                            <option value="Scratch">Scratch</option>
                            <option value="Construct 3">Construct 3</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL Project</label>
                        <input id="url" name="url" type="url" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none @error('url') border-red-500 @enderror" placeholder="https://...">
                        <p class="text-gray-500 text-xs mt-1" style="display: none;">Untuk Scratch: URL harus dari scratch.mit.edu dan berakhir dengan angka (ID project)</p>
                        @error('url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (opsional)</label>
                        <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none">
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-[#da5597] text-white rounded-lg hover:bg-[#c94c89] transition-colors">
                            Tambah Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Edit Project</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">Judul Project</label>
                        <input id="edit_title" name="title" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none">
                    </div>
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="edit_description" name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Project</label>
                        <select id="edit_type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none">
                            <option value="Scratch">Scratch</option>
                            <option value="Construct 3">Construct 3</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_url" class="block text-sm font-medium text-gray-700 mb-1">URL Project</label>
                        <input id="edit_url" name="url" type="url" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none @error('url') border-red-500 @enderror">
                        <p class="text-gray-500 text-xs mt-1" style="display: none;">Untuk Scratch: URL harus dari scratch.mit.edu dan berakhir dengan angka (ID project)</p>
                        @error('url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit_thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (opsional)</label>
                        <input id="edit_thumbnail" name="thumbnail" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#da5597] focus:ring-[#da5597] focus:outline-none">
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-[#da5597] text-white rounded-lg hover:bg-[#c94c89] transition-colors">
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scratch Embed Modal -->
    <div id="scratchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Scratch Project: <span id="scratchProjectTitle"></span></h3>
                    <button onclick="closeScratchModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <div id="scratchEmbedContainer" class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#da5597] mx-auto mb-4"></div>
                            <p class="text-gray-600">Memuat Scratch project...</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <a id="scratchOriginalLink" href="#" target="_blank" class="text-[#da5597] hover:text-[#c94c89] text-sm font-medium">
                        Buka di Scratch
                    </a>
                    <button onclick="closeScratchModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus project "<span id="deleteProjectName"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex space-x-3">
                        <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <form id="deleteForm" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Hapus Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components/footer/footer')

    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, title, description, type, url) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_url').value = url;
            document.getElementById('editForm').action = `/projects/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id, title) {
            document.getElementById('deleteProjectName').textContent = title;
            document.getElementById('deleteForm').action = `/projects/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Scratch embed functions
        function openScratchModal(url, projectId, title) {
            document.getElementById('scratchProjectTitle').textContent = title;
            document.getElementById('scratchOriginalLink').href = url;
            
            // Increment view count and check embed validity
            fetch(`/projects/${projectId}/increment-view`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    // If server indicates embed is not available, redirect to original URL
                    console.log(data.message);
                    window.open(data.redirect, '_blank');
                    return;
                }
                
                // Convert Scratch URL to embed URL
                let embedUrl = convertScratchUrlToEmbed(url);
                
                // Create iframe for embed
                const container = document.getElementById('scratchEmbedContainer');
                container.innerHTML = `
                    <iframe 
                        src="${embedUrl}" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        scrolling="no" 
                        allowtransparency="true"
                        onload="handleScratchLoad()"
                        onerror="handleScratchError('${url}')"
                    ></iframe>
                `;
                
                document.getElementById('scratchModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error checking embed validity:', error);
                // Fallback to original behavior
                let embedUrl = convertScratchUrlToEmbed(url);
                
                const container = document.getElementById('scratchEmbedContainer');
                container.innerHTML = `
                    <iframe 
                        src="${embedUrl}" 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        scrolling="no" 
                        allowtransparency="true"
                        onload="handleScratchLoad()"
                        onerror="handleScratchError('${url}')"
                    ></iframe>
                `;
                
                document.getElementById('scratchModal').classList.remove('hidden');
            });
        }

        function closeScratchModal() {
            document.getElementById('scratchModal').classList.add('hidden');
            // Reset container
            document.getElementById('scratchEmbedContainer').innerHTML = `
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#da5597] mx-auto mb-4"></div>
                    <p class="text-gray-600">Memuat Scratch project...</p>
                </div>
            `;
        }

        function convertScratchUrlToEmbed(url) {
            // Handle different Scratch URL formats
            if (url.includes('scratch.mit.edu/projects/')) {
                // Extract project ID from URL
                const projectId = url.match(/projects\/(\d+)/)?.[1];
                if (projectId) {
                    return `https://scratch.mit.edu/projects/${projectId}/embed`;
                }
            }
            // If URL doesn't match expected format, return original URL
            return url;
        }

        function handleScratchLoad() {
            // Successfully loaded
            console.log('Scratch project loaded successfully');
        }

        function handleScratchError(originalUrl) {
            // If embed fails, redirect to original URL
            console.log('Scratch embed failed, redirecting to original URL');
            window.open(originalUrl, '_blank');
            closeScratchModal();
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed')) {
                e.target.classList.add('hidden');
            }
        });

        // Show/hide URL hint based on project type
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const editTypeSelect = document.getElementById('edit_type');
            
            function updateUrlHint(select) {
                const urlInput = select.closest('form').querySelector('input[name="url"]');
                const urlHint = urlInput.parentNode.querySelector('p.text-gray-500');
                
                if (select.value === 'Scratch' && urlInput.value.trim() !== '') {
                    urlHint.style.display = 'block';
                } else {
                    urlHint.style.display = 'none';
                }
            }
            
            if (typeSelect) {
                typeSelect.addEventListener('change', () => updateUrlHint(typeSelect));
                typeSelect.addEventListener('input', () => updateUrlHint(typeSelect));
            }
            
            if (editTypeSelect) {
                editTypeSelect.addEventListener('change', () => updateUrlHint(editTypeSelect));
                editTypeSelect.addEventListener('input', () => updateUrlHint(editTypeSelect));
            }
            
            // Also listen to URL input changes
            const urlInputs = document.querySelectorAll('input[name="url"]');
            urlInputs.forEach(input => {
                input.addEventListener('input', () => {
                    const form = input.closest('form');
                    const typeSelect = form.querySelector('select[name="type"]');
                    if (typeSelect) {
                        updateUrlHint(typeSelect);
                    }
                });
            });
        });

        // Validate Scratch URL before attempting embed
        function isValidScratchUrl(url) {
            return /^https?:\/\/(www\.)?scratch\.mit\.edu\/projects\/\d+\/?$/.test(url);
        }
    </script>
@endsection 