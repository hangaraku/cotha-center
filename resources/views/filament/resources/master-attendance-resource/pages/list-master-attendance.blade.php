<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Class Selection Form -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            {{ $this->form }}
        </div>

        <!-- Master Attendance Table -->
        @if($classrooms && $classrooms->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Master Absensi
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Center: {{ $classrooms->first()->center->name }}
                        </p>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Total Kelas: {{ $classrooms->count() }}
                    </div>
                </div>

                @if($maxSessions > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Kelas
                                    </th>
                                    @foreach($this->getSessionHeaders() as $header)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ $header }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($classrooms as $classroom)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $classroom->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $classroom->classroomType->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        @for($i = 1; $i <= $maxSessions; $i++)
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $sessionDetails = $this->getClassroomSessionDetails($classroom->id, $i);
                                                @endphp
                                                @if($sessionDetails)
                                                    @if($sessionDetails['sessions']->count() == 1)
                                                        {{-- Single session - direct link --}}
                                                        <a href="{{ url('/admin/classroom-sessions/' . $sessionDetails['sessions']->first()->id . '/edit') }}" 
                                                           class="text-sm text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 hover:underline"
                                                           target="_blank">
                                                            <div>{{ $sessionDetails['date'] }}</div>
                                                            @if($this->isFiltered() && isset($sessionDetails['overall_session_number']))
                                                                <div class="text-xs text-gray-500">(#{{ $sessionDetails['overall_session_number'] }})</div>
                                                            @endif
                                                        </a>
                                                    @else
                                                        {{-- Multiple sessions - show all with times --}}
                                                        <div class="space-y-1">
                                                            @foreach($sessionDetails['sessions'] as $session)
                                                                <a href="{{ url('/admin/classroom-sessions/' . $session->id . '/edit') }}" 
                                                                   class="block text-sm text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 hover:underline"
                                                                   target="_blank">
                                                                    <div>{{ $sessionDetails['date'] }}</div>
                                                                    @if($this->isFiltered() && isset($sessionDetails['overall_session_number']))
                                                                        <div class="text-xs text-gray-500">(#{{ $sessionDetails['overall_session_number'] }}) {{ $session->start_time->format('H:i') }}</div>
                                                                    @else
                                                                        <div class="text-xs text-gray-500">({{ $session->start_time->format('H:i') }})</div>
                                                                    @endif
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-sm text-gray-400 dark:text-gray-500">
                                                        -
                                                    </div>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada sesi</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Kelas yang dipilih belum memiliki sesi resmi.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Informasi
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            @if($this->isFiltered())
                                <p>
                                    Kolom menampilkan nomor urut (1, 2, 3...) untuk bulan yang dipilih. 
                                    Angka (#N) di bawah tanggal menunjukkan sesi ke-N secara keseluruhan dari kelas tersebut.
                                </p>
                            @else
                                <p>
                                    Tabel ini menampilkan semua sesi resmi untuk setiap kelas. 
                                    Gunakan filter Bulan dan Tahun di atas untuk melihat sesi periode tertentu.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="text-center py-8">
                    <div class="text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Pilih Kelas</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Pilih center dan kelas di atas untuk melihat master absensi.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

