<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Attendance</h2>
    @if(!$finalized)
        <form wire:submit.prevent="finalizeAttendance">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Student</th>
                        <th class="px-4 py-2 text-center">Present?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendance as $studentId => $data)
                        <tr>
                            <td class="px-4 py-2">{{ $data['name'] }}</td>
                            <td class="px-4 py-2 text-center">
                                <button type="button" wire:click="toggleAttendance({{ $studentId }})"
                                    class="rounded-full px-3 py-1 text-white {{ $data['is_present'] ? 'bg-green-500' : 'bg-red-400' }}">
                                    {{ $data['is_present'] ? 'Present' : 'Absent' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700">Finalize Attendance</button>
            </div>
        </form>
    @else
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 font-semibold">Attendance has been finalized for this session.</div>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Student</th>
                    <th class="px-4 py-2 text-center">Present?</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendance as $studentId => $data)
                    <tr>
                        <td class="px-4 py-2">{{ $data['name'] }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="rounded-full px-3 py-1 text-white {{ $data['is_present'] ? 'bg-green-500' : 'bg-red-400' }}">
                                {{ $data['is_present'] ? 'Present' : 'Absent' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-filament::page> 