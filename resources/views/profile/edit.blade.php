@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        
        <!-- Hero Section -->
        <div class="relative z-10 px-8 sm:px-16 lg:px-24 xl:px-48 py-16">
            <div class="max-w-4xl mx-auto text-left">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 text-white">Edit Profil</h1>
                <p class="text-xl md:text-2xl opacity-90 text-white">Perbarui data diri dan foto profil kamu</p>
            </div>
        </div>
    </div>

    <!-- Profile Form Section -->
    <div class="relative z-10 -mt-20 px-4 sm:px-8 md:px-16 lg:px-24 pb-16">
        <div class="max-w-3xl mx-auto w-full">
            <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-8 md:p-12">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="w-full">
                    @csrf
                    <!-- Profile Picture Section -->
                    <div class="mb-8 flex flex-col items-center justify-center">
                        <label class="block text-lg font-semibold text-gray-800 mb-4 w-full text-left">Foto Profil</label>
                        <div class="relative group w-28 h-28 sm:w-32 sm:h-32 mb-2" id="profile_picture_container">
                            <input type="file" name="profile_picture" id="profile_picture_input" class="hidden" accept="image/*">
                            <label for="profile_picture_input" class="block w-full h-full cursor-pointer">
                                @if($user->profile_picture)
                                    <img src="{{ asset('uploads/' . $user->profile_picture) }}" alt="Foto Profil" class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover object-center border-4 border-pink-200 transition duration-200 group-hover:opacity-80" id="profile_picture_preview">
                                @else
                                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-pink-200" id="profile_picture_preview">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-[#da5597] bg-opacity-80 rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200" id="profile_picture_overlay">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12V8a2 2 0 012-2h3.172a2 2 0 011.414.586l.828.828A2 2 0 0013.828 8H18a2 2 0 012 2v4m-6 4v-4m0 0l-2-2m2 2l2-2" /></svg>
                                    <span class="text-white text-xs font-semibold">Unggah Foto</span>
                                </div>
                            </label>
                        </div>
                        @error('profile_picture')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                        <p class="text-sm text-gray-600 mt-2 text-center">Unggah foto profil baru (JPG, PNG, GIF maks 2MB)</p>
                    </div>

                    <!-- Name Section -->
                    <div class="mb-6">
                        <label class="block text-lg font-semibold text-gray-800 mb-3">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-pink-500 focus:outline-none transition-colors @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap kamu">
                        @error('name')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Section -->
                    <div class="mb-8">
                        <label class="block text-lg font-semibold text-gray-800 mb-3">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed" readonly disabled>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-8">
                        <label class="block text-lg font-semibold text-gray-800 mb-3">Password Baru</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-pink-500 focus:outline-none transition-colors @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password baru (biarkan kosong jika tidak ingin mengubah)">
                        @error('password')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                        <p class="text-sm text-gray-600 mt-2">Biarkan kosong jika tidak ingin mengubah password.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                                class="flex-1 bg-[#da5597] hover:bg-[#c94c89] text-white px-8 py-4 rounded-full font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('profile.password') }}" 
                           class="flex-1 bg-gray-100 text-gray-700 px-8 py-4 rounded-full font-semibold hover:bg-gray-200 transition-all duration-200 text-center">
                            Ubah Password
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.footer.footer')

    <script>
        // Profile picture upload overlay logic
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('profile_picture_input');
            const preview = document.getElementById('profile_picture_preview');
            
            console.log('Profile picture script loaded');
            console.log('Input element:', input);
            console.log('Preview element:', preview);
            
            if (preview && input) {
                // Live preview
                input.addEventListener('change', function(e) {
                    console.log('File input changed:', e.target.files);
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            console.log('File loaded, updating preview');
                            if (preview.tagName === 'IMG') {
                                preview.src = ev.target.result;
                            } else {
                                // Convert div to img for better image handling
                                const img = document.createElement('img');
                                img.src = ev.target.result;
                                img.alt = 'Foto Profil';
                                img.className = 'w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover object-center border-4 border-pink-200 transition duration-200 group-hover:opacity-80';
                                img.id = 'profile_picture_preview';
                                preview.parentNode.replaceChild(img, preview);
                            }
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            } else {
                console.error('Required elements not found');
            }
        });
    </script>
@endsection 