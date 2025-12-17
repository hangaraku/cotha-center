@if($classroomSessionId)
    @livewire('attendance-manager', ['classroomSessionId' => $classroomSessionId])
@else
    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
        <div class="text-center text-gray-600">
            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            <p class="font-medium">Attendance will be available after the session is created</p>
            <p class="text-sm text-gray-500 mt-1">Save the session first to manage student attendance</p>
        </div>
    </div>
@endif 