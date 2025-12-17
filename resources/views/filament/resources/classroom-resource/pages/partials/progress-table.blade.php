@php
    // Helper to get avatar (use initials if no avatar field)
    function getAvatar($user) {
        $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=cb5283&color=fff&size=64&rounded=true';
    }
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-xl shadow border border-gray-200">
        <thead>
            <tr>
                <th class="border-b px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase rounded-tl-xl">Student</th>
                @foreach ($module->units as $unit)
                    <th class="text-center px-4 py-3 text-xs font-bold text-gray-700 uppercase border-b" title="{{ $unit->name }}">
                        Step {{ $loop->iteration }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($classroom->students as $student)
                <tr class="hover:bg-pink-50 transition">
                    <td class="border-r px-4 py-3 flex items-center gap-x-3 min-w-[220px]">
                        <img src="{{ getAvatar($student->user) }}" alt="Avatar" class="h-9 w-9 rounded-full border border-pink-200 shadow-sm">
                        <span class="font-medium text-gray-900">{{ $student->user->name }}</span>
                    </td>
                    @foreach ($module->units as $unit)
                        <td class="text-center px-4 py-3">
                            @php
                                $hasAccess = $studentProgress[$module->id][$student->user_id][$unit->id] ?? false;
                            @endphp
                            
                            <button 
                                wire:click="toggleUnitAccess({{ $module->id }}, {{ $student->user_id }}, {{ $unit->id }})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 {{ $hasAccess ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}"
                                title="{{ $hasAccess ? 'Click to revoke access' : 'Click to grant access' }} to {{ $unit->name }}"
                            >
                                @if($hasAccess)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @endif
                            </button>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div> 