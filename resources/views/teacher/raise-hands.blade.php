@extends('layouts.app')

@section('title', 'Raise Hands - Teacher View')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('components.nav_bar.nav-bar')
    
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-700 text-white">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-3xl font-bold mb-4">Raise Hands</h1>
                <p class="text-xl opacity-90">Lihat siswa yang mengangkat tangan meminta bantuan</p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Daftar Tangan Terangkat</h2>
                <button 
                    onclick="refreshRaiseHands()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>

            <div id="raiseHandsList" class="space-y-4">
                <!-- Raise hands will be loaded here -->
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <p>Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let classroomId = {{ $classroom->id }};

function loadRaiseHands() {
    fetch(`/raise-hand/classroom/${classroomId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRaiseHands(data.raised_hands);
            } else {
                console.error('Error loading raise hands:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function displayRaiseHands(raiseHands) {
    const container = document.getElementById('raiseHandsList');
    
    if (raiseHands.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>Tidak ada siswa yang mengangkat tangan saat ini</p>
            </div>
        `;
        return;
    }

    container.innerHTML = raiseHands.map(hand => `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">${hand.user.name.charAt(0).toUpperCase()}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">${hand.user.name}</h3>
                            <p class="text-sm text-gray-600">${hand.unit ? hand.unit.name : 'Unit tidak diketahui'}</p>
                        </div>
                    </div>
                    ${hand.message ? `
                        <div class="bg-white rounded-lg p-3 mt-2">
                            <p class="text-sm text-gray-700">"${hand.message}"</p>
                        </div>
                    ` : ''}
                    <p class="text-xs text-gray-500 mt-2">
                        Mengangkat tangan pada ${new Date(hand.raised_at).toLocaleString('id-ID')}
                    </p>
                </div>
                <button 
                    onclick="lowerHand(${hand.id})"
                    class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-sm"
                >
                    Turunkan
                </button>
            </div>
        </div>
    `).join('');
}

function lowerHand(raiseHandId) {
    fetch(`/raise-hand/${raiseHandId}/lower`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadRaiseHands(); // Refresh the list
        } else {
            console.error('Error lowering hand:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function refreshRaiseHands() {
    loadRaiseHands();
}

// Load raise hands on page load
document.addEventListener('DOMContentLoaded', function() {
    loadRaiseHands();
    
    // Auto-refresh every 30 seconds
    setInterval(loadRaiseHands, 30000);
});
</script>
@endsection 