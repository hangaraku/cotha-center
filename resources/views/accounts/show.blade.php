@extends('layouts.app')

@section('title', 'Detail Akun')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        <div class="flex flex-col justify-center items-start gap-y-4 px-8 sm:px-16 lg:px-24 xl:px-48 py-12 text-white text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-2">Detail Akun</h1>
            <p class="text-lg md:text-2xl font-medium opacity-90">Informasi lengkap akun platform pembelajaran Anda</p>
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
                    <h2 class="text-2xl font-bold text-gray-900">Detail Akun</h2>
                </div>
            </div>

            <!-- Account Details Card -->
            <div class="bg-gray-50 rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">{{ $account->platform_name }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('accounts.edit', $account) }}" 
                               class="text-white hover:text-blue-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Account Details -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Detail Akun</h4>
                        <div class="bg-white rounded-lg p-4 border">
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap font-mono">{{ $account->account_details }}</pre>
                        </div>
                    </div>

                    <!-- Platform Link -->
                    @if($account->platform_link)
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Link Platform</h4>
                            <a href="{{ $account->platform_link }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                                <span>Kunjungi Platform</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    @endif

                    <!-- Account Info -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Informasi Akun</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Dibuat:</span>
                                <p class="text-gray-600">{{ $account->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Terakhir diperbarui:</span>
                                <p class="text-gray-600">{{ $account->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('accounts.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kembali ke Daftar
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('accounts.edit', $account) }}" 
                       class="px-6 py-2 rounded-full font-bold text-white bg-[#da5597] hover:bg-[#c94c89] transition">
                        Edit Akun
                    </a>
                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-2 rounded-full font-bold text-white bg-red-600 hover:bg-red-700 transition"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer.footer')
@endsection 