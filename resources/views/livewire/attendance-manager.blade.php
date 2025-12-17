<div class="relative bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
    <!-- Instruction -->
    <div class="mb-4">
        <div class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>
            @if($finalized)
                Attendance has been finalized. Students marked as present have had their credits deducted.
            @else
                Mark students as present by clicking their name. Click again to move them back to "Not Present". Use the "Finalize Attendance" button in the header when ready.
            @endif
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Not Present -->
        <div class="flex-1 bg-red-50 border-2 border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white font-bold">{{ count($absent) }}</span>
                <span class="font-semibold text-red-700 text-lg">Not Present</span>
            </div>
            <input type="text" class="w-full mb-3 p-2 border border-red-200 rounded focus:ring-red-400 focus:border-red-400" placeholder="Search" wire:model.debounce.300ms="searchAbsent">
            <div class="overflow-y-auto max-h-64 space-y-2">
                @forelse($absent as $student)
                    @if($searchAbsent === '' || str_contains(strtolower($student['name']), strtolower($searchAbsent)))
                        <div class="cursor-pointer select-none p-3 rounded border border-red-200 bg-white hover:bg-red-100 transition flex items-center justify-between {{ $finalized ? 'opacity-50 cursor-not-allowed' : '' }}"
                            wire:click="toggleAttendance({{ $student['id'] }})"
                            @if($finalized) disabled @endif
                        >
                            <span class="text-red-700 font-medium">{{ $student['name'] }}</span>
                            <span class="text-xs text-red-400">Click to mark present</span>
                        </div>
                    @endif
                @empty
                    <div class="text-red-400 italic text-center">All students are present!</div>
                @endforelse
            </div>
        </div>

        <!-- Present -->
        <div class="flex-1 bg-green-50 border-2 border-green-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white font-bold">{{ count($present) }}</span>
                <span class="font-semibold text-green-700 text-lg">Present</span>
            </div>
            <input type="text" class="w-full mb-3 p-2 border border-green-200 rounded focus:ring-green-400 focus:border-green-400" placeholder="Search" wire:model.debounce.300ms="searchPresent">
            <div class="overflow-y-auto max-h-64 space-y-2">
                @forelse($present as $student)
                    @if($searchPresent === '' || str_contains(strtolower($student['name']), strtolower($searchPresent)))
                        <div class="cursor-pointer select-none p-3 rounded border border-green-200 bg-white hover:bg-green-100 transition flex items-center justify-between {{ $finalized ? 'opacity-50 cursor-not-allowed' : '' }}"
                            wire:click="toggleAttendance({{ $student['id'] }})"
                            @if($finalized) disabled @endif
                        >
                            <span class="text-green-700 font-medium">{{ $student['name'] }}</span>
                            <span class="text-xs text-green-400">Click to mark absent</span>
                        </div>
                    @endif
                @empty
                    <div class="text-green-400 italic text-center">No students present yet.</div>
                @endforelse
            </div>
        </div>
    </div>
    @if($finalized)
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center gap-2 text-green-700">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold">Attendance Finalized</span>
            </div>
            <p class="text-sm text-green-600 mt-1">Credits have been deducted for present students. No further changes can be made.</p>
        </div>
    @endif
</div>
