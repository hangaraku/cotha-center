@extends('layouts.app')

@section('title', 'Riwayat Penukaran')

@section('content')
    <div style="background-image: url('{{ asset('images/background-nav-hero.jpg') }}?id={{ now() }}'); background-position: bottom; background-repeat: no-repeat; background-size: cover; min-height: 450px; background-color: #f2f4f9;" class="relative">
        @include('components.nav_bar.nav-bar')
        <div class="flex flex-col items-start gap-y-4 px-8 sm:px-16 lg:px-24 xl:px-48 py-12 text-white text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-2">Riwayat Penukaran</h1>
            <a href="{{ route('rewards.index') }}" class="mt-4 px-6 py-2 rounded-full font-bold text-white bg-[#da5597] hover:bg-[#c94c89] transition w-full sm:w-auto text-center">Kembali ke Rewards</a>
        </div>
    </div>
    <div class="relative z-10 -mt-20 px-8 md:px-16 lg:px-24 pb-16">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 w-full text-black">
            <h2 class="text-2xl font-bold mb-6 text-[#da5597]">Riwayat Penukaran</h2>
            
            @if(config('app.debug'))
            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded text-xs">
                <strong>Debug Info:</strong>
                @foreach($orders as $order)
                    <div>Order #{{ $order->id }}: status = "{{ $order->status }}" (type: {{ gettype($order->status) }}, raw: {{ $order->getRawOriginal('status') }})</div>
                @endforeach
            </div>
            @endif
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hadiah</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-4 py-3 flex items-center gap-x-3">
                                <img src="{{ $order->reward && $order->reward->img_url ? (strpos($order->reward->img_url, 'http') !== false ? $order->reward->img_url : asset('uploads/' . $order->reward->img_url)) : '' }}" alt="Reward Image" class="w-16 h-16 object-cover rounded-lg bg-gray-100">
                                <span class="font-bold text-gray-900">{{ $order->reward->name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                @if($order->status == 0 || $order->status === 'pending')
                                    <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-xs relative group cursor-pointer" tabindex="0">
                                        Terdaftar
                                        <span class="tooltip group-hover:opacity-100 group-focus:opacity-100">Pesanan sudah masuk sistem, menunggu approval admin. Bisa dibatalkan sebelum di-approve.</span>
                                    </span>
                                @elseif($order->status == 1 || $order->status === 'claimed')
                                    <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold text-xs relative group cursor-pointer" tabindex="0">
                                        Sedang dikemas
                                        <span class="tooltip group-hover:opacity-100 group-focus:opacity-100">Pesanan sudah di-approve dan sedang diproses. Tidak bisa dibatalkan.</span>
                                    </span>
                                @elseif($order->status == 2 || $order->status === 'cancelled')
                                    <span class="inline-block px-3 py-1 rounded-full bg-gray-200 text-gray-600 font-semibold text-xs relative group cursor-pointer" tabindex="0">
                                        Dibatalkan
                                        <span class="tooltip group-hover:opacity-100 group-focus:opacity-100">Pesanan dibatalkan oleh pengguna, poin sudah dikembalikan.</span>
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-500 font-semibold text-xs">
                                        {{ $order->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($order->status == 0 || $order->status === 'pending')
                                    <form method="POST" action="{{ route('user-rewards.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penukaran hadiah ini? Poin akan dikembalikan ke akun Anda.');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-full font-bold text-white bg-red-500 hover:bg-red-600 transition">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">Belum ada penukaran hadiah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">
                {!! str_replace('dark:', '', $orders->links()) !!}
            </div>
        </div>
    </div>
    @include('components/footer/footer')
@endsection

<style>
.tooltip {
    opacity: 0;
    pointer-events: none;
    position: absolute;
    left: 50%;
    top: 110%;
    transform: translateX(-50%);
    min-width: 200px;
    background: rgba(55, 65, 81, 0.95);
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.9rem;
    z-index: 10;
    transition: opacity 0.2s;
    box-shadow: 0 2px 8px 0 rgba(0,0,0,0.07);
    white-space: pre-line;
}
.group:hover .tooltip, .group:focus .tooltip {
    opacity: 1;
    pointer-events: auto;
}
</style> 