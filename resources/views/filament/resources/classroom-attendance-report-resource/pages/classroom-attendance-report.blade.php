<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-wrap gap-4 mb-4">
                <div class="bg-gray-50 rounded-lg p-4 flex-1 min-w-[300px]">
                    <div class="flex items-center">
                        <x-heroicon-o-academic-cap class="h-6 w-6 text-blue-600 mr-3" />
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipe Kelas</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $this->classroom->classroomType->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex-1 min-w-[300px]">
                    <div class="flex items-center">
                        <x-heroicon-o-users class="h-6 w-6 text-green-600 mr-3" />
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $this->students ? count($this->students) : 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4 mb-4">
                <div class="bg-gray-50 rounded-lg p-4 flex-1 min-w-[300px]">
                    <div class="flex items-center">
                        <x-heroicon-o-calendar class="h-6 w-6 text-purple-600 mr-3" />
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pertemuan</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $this->classroom->total_credit ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex-1 min-w-[300px]">
                    <div class="flex items-center">
                        <x-heroicon-o-clock class="h-6 w-6 text-orange-600 mr-3" />
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sesi Official</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $this->getOfficialSessionsCount() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4 mb-4">
                <div class="bg-gray-50 rounded-lg p-4 flex-1 min-w-[300px]">
                    <div class="flex items-center">
                        <x-heroicon-o-clock class="h-6 w-6 text-yellow-600 mr-3" />
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sesi Unofficial</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $this->getUnofficialSessionsCount() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex-1 min-w-[300px]">
                    <div class="flex items-center">
                        <x-heroicon-o-calendar-days class="h-6 w-6 text-indigo-600 mr-3" />
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Mulai</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $this->classroom->start_date ? \Carbon\Carbon::parse($this->classroom->start_date)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Attendance Table -->
        @if($this->sessions && count($this->sessions) > 0 && $this->students && count($this->students) > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 border-r border-gray-200">
                                    Nama Siswa
                                </th>
                                @foreach($this->sessions as $session)
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-32">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $session->session_date->format('d/m/Y') }}</span>
                                            <span class="text-xs text-gray-400">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}</span>
                                            <span class="text-xs text-gray-400 capitalize">{{ $session->type }}</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($this->attendanceData)
                                @foreach($this->attendanceData as $studentData)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r border-gray-200">
                                        {{ $studentData['name'] }}
                                    </td>
                                    @foreach($studentData['sessions'] as $sessionData)
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            @php
                                                $status = $sessionData['status'];
                                                $isPresent = $sessionData['is_present'];
                                            @endphp
                                            <div class="flex items-center justify-center">
                                                @if($status === 'Present')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                                        Hadir
                                                    </span>
                                                @elseif($status === 'Absent')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                                        Tidak Hadir
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <x-heroicon-o-minus-circle class="w-4 h-4 mr-1" />
                                                        Belum Dicatat
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <x-heroicon-o-information-circle class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(!$this->sessions || count($this->sessions) === 0)
                            Belum ada sesi kelas yang dibuat untuk kelas ini.
                        @elseif(!$this->students || count($this->students) === 0)
                            Belum ada siswa yang terdaftar di kelas ini.
                        @else
                            Tidak ada data absensi yang tersedia.
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>