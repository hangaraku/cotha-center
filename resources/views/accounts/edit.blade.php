@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        <div class="flex flex-col justify-center items-start gap-y-4 px-8 sm:px-16 lg:px-24 xl:px-48 py-12 text-white text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-2">Edit Akun</h1>
            <p class="text-lg md:text-2xl font-medium opacity-90">Perbarui informasi akun platform pembelajaran Anda</p>
        </div>
    </div>
    
    <div class="relative z-10 -mt-20 px-8 md:px-16 lg:px-24 pb-16">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 w-full text-black">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="{{ route('accounts.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-900">Form Edit Akun</h2>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('accounts.update', $account) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Platform Name -->
                <div class="mb-6">
                    <label for="platform_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Platform *
                    </label>
                    <input type="text" 
                           name="platform_name" 
                           id="platform_name"
                           value="{{ old('platform_name', $account->platform_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Scratch, Construct 3, Unity"
                           required>
                    @error('platform_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Details -->
                <div class="mb-6">
                    <label for="account_details" class="block text-sm font-medium text-gray-700 mb-2">
                        Detail Akun *
                    </label>
                    <textarea name="account_details" 
                              id="account_details"
                              rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Contoh:&#10;Username: john_doe&#10;Password: mypassword123&#10;Email: john@example.com&#10;&#10;Atau:&#10;Nama: John Doe&#10;Password: mypassword123"
                              required>{{ old('account_details', $account->account_details) }}</textarea>
                    @error('account_details')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Masukkan informasi login Anda seperti username, password, email, dll.
                    </p>
                </div>

                <!-- Platform Link -->
                <div class="mb-6">
                    <label for="platform_link" class="block text-sm font-medium text-gray-700 mb-2">
                        Link Platform (Opsional)
                    </label>
                    <input type="url" 
                           name="platform_link" 
                           id="platform_link"
                           value="{{ old('platform_link', $account->platform_link) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="https://scratch.mit.edu atau https://www.construct.net">
                    @error('platform_link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Link langsung ke platform untuk akses cepat.
                    </p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('accounts.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 rounded-full font-bold text-white bg-[#da5597] hover:bg-[#c94c89] transition">
                        Perbarui Akun
                    </button>
                </div>
            </form>

            <!-- Account Info -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-800 mb-2">Informasi Akun</h3>
                <div class="text-sm text-gray-600">
                    <p>Dibuat: {{ $account->created_at->format('d M Y H:i') }}</p>
                    <p>Terakhir diperbarui: {{ $account->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer.footer')
@endsection 