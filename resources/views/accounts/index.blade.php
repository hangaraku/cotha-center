@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        <div class="flex flex-col justify-center items-start gap-y-4 px-8 sm:px-16 lg:px-24 xl:px-48 py-12 text-white text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-2">Akun Saya</h1>
            <p class="text-lg md:text-2xl font-medium opacity-90">Kelola akun platform pembelajaran Anda</p>
            @auth
                <div class="flex items-center gap-x-2 mt-2 bg-white bg-opacity-20 px-4 py-2 rounded-xl shadow">
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="font-bold text-lg md:text-xl">{{ Auth::user()->accounts->count() }} akun tersimpan</span>
                </div>
            @endauth
        </div>
    </div>
    
    <div class="relative z-10 -mt-20 px-8 md:px-16 lg:px-24 pb-16">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 w-full text-black">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add New Account Button -->
            <div class="flex flex-col sm:flex-row sm:justify-start mb-6">
                <a href="{{ route('accounts.create') }}" 
                   class="px-6 py-2 rounded-full font-bold text-white bg-[#da5597] hover:bg-[#c94c89] transition w-full sm:w-auto text-center">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Akun Baru
                </a>
            </div>

            <!-- Accounts Grid -->
            @if($accounts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($accounts as $account)
                        <div class="bg-gray-50 rounded-2xl shadow flex flex-col overflow-hidden h-full justify-between">
                            <div class="w-full aspect-video bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center overflow-hidden">
                                <div class="text-white text-center p-4">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="font-bold text-lg">{{ $account->platform_name }}</h3>
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-4 gap-y-2">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $account->platform_name }}</h3>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('accounts.edit', $account) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Account Details Preview -->
                                <div class="mb-4">
                                    <div class="bg-white rounded-lg p-3 border">
                                        <p class="text-sm text-gray-600 line-clamp-3 font-mono">{{ $account->account_details }}</p>
                                    </div>
                                </div>

                                <!-- Platform Link -->
                                @if($account->platform_link)
                                    <div class="mb-4">
                                        <a href="{{ $account->platform_link }}" 
                                           target="_blank"
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 text-sm">
                                            <span>Kunjungi Platform</span>
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                <!-- Created Date -->
                                <div class="text-xs text-gray-500 mt-auto">
                                    Ditambahkan: {{ $account->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada akun</h3>
                    <p class="text-gray-600">Tambahkan akun platform pembelajaran Anda untuk menyimpan informasi login.</p>
                </div>
            @endif
        </div>
    </div>

    @include('components.footer.footer')
@endsection 