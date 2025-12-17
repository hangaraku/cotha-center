<div class="space-y-4">
    @if($record->attendance_proof_photo)
        <div class="text-center">
            <img src="{{ Storage::url($record->attendance_proof_photo) }}" 
                 alt="Foto Bukti Presensi" 
                 class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                 style="max-height: 500px;">
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800">Informasi Foto</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        Foto ini diunggah sebagai bukti presensi untuk sesi kelas pada tanggal 
                        <strong>{{ $record->session_date->format('d M Y') }}</strong> 
                        pukul <strong>{{ $record->start_time->format('H:i') }} - {{ $record->end_time->format('H:i') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Foto Bukti Presensi</h3>
            <p class="text-gray-600">Foto bukti presensi belum diunggah untuk sesi ini.</p>
        </div>
    @endif
</div> 