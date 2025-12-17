@extends('layouts.app')

@section('title', 'Rewards')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero-reward.jpg') }}?id={{ now() }}'); background-position: top; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        <div class="flex flex-col justify-center items-start gap-y-4 px-8 sm:px-16 lg:px-24 xl:px-48 py-12 text-white text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-2">Rewards</h1>
            <p class="text-lg md:text-2xl font-medium opacity-90">Tukarkan poin dengan hadiah kesukaanmu!</p>
            @auth
                <div class="flex items-center gap-x-2 mt-2 bg-white bg-opacity-20 px-4 py-2 rounded-xl shadow">
                    <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27L16.18 18l-1.64-7.03L19 7.24l-7.19-.61L10 0 8.19 6.63 1 7.24l5.46 3.73L4.82 18z"/></svg>
                    <span class="font-bold text-lg md:text-xl">{{ number_format(Auth::user()->point) }} poin</span>
                </div>
            @endauth
        </div>
    </div>
    <div class="relative z-10 -mt-20 px-8 md:px-16 lg:px-24 pb-16">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 w-full text-black">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div id="floating-error" class="fixed top-6 left-6 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-x-3 animate-fade-in">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
                <audio id="error-sound" src="https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa1c82.mp3" preload="auto"></audio>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const notif = document.getElementById('floating-error');
                        const sound = document.getElementById('error-sound');
                        if (notif && sound) {
                            sound.volume = 0.15;
                            sound.play();
                            setTimeout(() => {
                                notif.style.transition = 'opacity 0.5s';
                                notif.style.opacity = 0;
                                setTimeout(() => notif.remove(), 600);
                            }, 3500);
                        }
                    });
                </script>
            @endif
            <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('user-rewards.index') }}" class="px-6 py-2 rounded-full font-bold text-white bg-[#da5597] hover:bg-[#c94c89] transition w-full sm:w-auto text-center">Riwayat Penukaran</a>
                    @php
                        $pinnedCount = $rewards->where('is_pinned', true)->count();
                        $totalCount = $rewards->total();
                    @endphp
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold">{{ $pinnedCount }}</span> disematkan • 
                        <span class="font-semibold">{{ $totalCount }}</span> total rewards
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse($rewards as $reward)
                    <div class="rounded-2xl shadow flex flex-col overflow-hidden h-full justify-between transition-all duration-300 {{ $reward->is_pinned ? 'bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-300 shadow-lg transform scale-105 pinned-reward' : 'bg-gray-50' }}">
                        @if($reward->is_pinned)
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 text-xs font-bold text-center">
                                ⭐ DISEMATKAN
                            </div>
                        @endif
                        <div class="w-full aspect-video bg-gray-200 flex items-center justify-center overflow-hidden">
                            @if($reward->img_url)
                                <img src="{{ strpos($reward->img_url, 'http') !== false ? $reward->img_url : asset('uploads/' . $reward->img_url) }}" alt="Reward Image" class="object-cover w-full h-full">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                        </div>
                        <div class="flex flex-col flex-1 p-4 gap-y-2">
                            <h3 class="font-bold text-lg text-gray-900 line-clamp-2">{{ $reward->name }}</h3>
                            <div class="flex flex-row gap-x-4 text-xs text-gray-500 mb-1 items-center">
                                <span class="flex items-center gap-x-1">
                                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    Stock: {{ $reward->stock }}
                                </span>
                                <span class="flex items-center gap-x-1">
                                    <svg class="w-4 h-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17.25l6.16 3.24-1.18-6.88 5-4.87-6.91-1-3.09-6.26-3.09 6.26-6.91 1 5 4.87-1.18 6.88L12 17.25z" />
                                    </svg>
                                    <span class="font-semibold text-base text-yellow-600">{{ number_format($reward->price) }} poin</span>
                                </span>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('user-rewards.store') }}" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                    @php
                                        $disabled = $reward->stock < 1 || Auth::user()->point < $reward->price;
                                    @endphp
                                    <button type="submit"
                                        class="w-full px-8 py-2 rounded-full font-bold text-white transition disabled:opacity-80 disabled:cursor-not-allowed tukarkan-btn"
                                        style="background-color: #da5597;"
                                        data-disabled-message="{{ $reward->stock < 1 ? 'Stok hadiah habis.' : 'Poin kamu tidak cukup.' }}"
                                        @if($disabled) disabled @endif>
                                        Tukarkan
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center">No rewards available.</div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($rewards->hasPages())
                <div class="mt-8 flex flex-col items-center space-y-4">
                    <div class="flex items-center space-x-2">
                        {{ $rewards->links() }}
                    </div>
                    <div class="text-sm text-gray-500 text-center">
                        Menampilkan {{ $rewards->firstItem() ?? 0 }}-{{ $rewards->lastItem() ?? 0 }} dari {{ $rewards->total() }} rewards
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('components/footer/footer')
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tukarkan-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (btn.disabled) {
                e.preventDefault();
                // Remove existing notification if any
                let oldNotif = document.getElementById('floating-error-client');
                if (oldNotif) oldNotif.remove();
                // Create notification
                let notif = document.createElement('div');
                notif.id = 'floating-error-client';
                notif.className = 'fixed top-6 left-6 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-x-3 animate-fade-in';
                notif.innerHTML = `<svg class='w-6 h-6 text-white' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M6 18L18 6M6 6l12 12'/></svg><span class='font-semibold'>${btn.getAttribute('data-disabled-message')}</span>`;
                document.body.appendChild(notif);
                // Play sound
                let sound = document.getElementById('error-sound-client');
                if (!sound) {
                    sound = document.createElement('audio');
                    sound.id = 'error-sound-client';
                    sound.src = 'https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa1c82.mp3';
                    sound.preload = 'auto';
                    document.body.appendChild(sound);
                }
                sound.volume = 0.15;
                sound.play();
                setTimeout(() => {
                    notif.style.transition = 'opacity 0.5s';
                    notif.style.opacity = 0;
                    setTimeout(() => notif.remove(), 600);
                }, 3500);
            }
        });
        // Custom hover color for enabled state
        btn.addEventListener('mouseover', function() {
            if (!btn.disabled) btn.style.backgroundColor = '#c94c89';
        });
        btn.addEventListener('mouseout', function() {
            if (!btn.disabled) btn.style.backgroundColor = '#da5597';
        });
    });
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.5s;
}

/* Custom pagination styles */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
}

.pagination li {
    margin: 0;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background-color: #f3f4f6;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.pagination .page-link:hover {
    background-color: #da5597;
    color: white;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background-color: #da5597;
    color: white;
    border: none;
}

.pagination .page-item.disabled .page-link {
    background-color: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
}

/* Pinned reward animation */
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 5px rgba(251, 191, 36, 0.5); }
    50% { box-shadow: 0 0 20px rgba(251, 191, 36, 0.8); }
}

.pinned-reward {
    animation: pulse-glow 2s infinite;
}
</style> 