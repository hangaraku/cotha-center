@php
    // Helper to get avatar (use initials if no avatar field)
    function getAvatar($user) {
        $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w,0,1)))->join('');
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=cb5283&color=fff&size=64&rounded=true';
    }
@endphp

<x-filament::page>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Custom Tooltip Styles -->
    <style>
        .unit-tooltip-container {
            position: relative;
        }
        
        .unit-tooltip-container:hover .unit-tooltip-content {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        .unit-tooltip-content {
            position: absolute !important;
            bottom: 100% !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            margin-bottom: 8px !important;
            padding: 8px 12px !important;
            background-color: #1f2937 !important;
            color: white !important;
            font-size: 12px !important;
            border-radius: 6px !important;
            white-space: nowrap !important;
            z-index: 99999 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: opacity 0.2s ease, visibility 0.2s ease !important;
            pointer-events: none !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        
        .unit-tooltip-content::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #1f2937;
        }
        
        /* Memastikan tooltip bisa keluar dari batas container */
        .bg-white.rounded-xl.shadow-sm.border.border-gray-200.overflow-hidden {
            overflow: visible !important;
        }
        
        /* Enable horizontal scrolling for tables with many columns */
        .relative.overflow-x-auto {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }
    </style>

    <div class="">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-gray-600">
                        Classroom: <span class="font-semibold text-primary-600">{{ $classroom->name }}</span>
                    </p>
                    @if($activeSession)
                        <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">
                                    Active Session: {{ $activeSession->type_label }} ({{ $activeSession->start_time->format('H:i') }} - {{ $activeSession->end_time->format('H:i') }})
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- All Modules Toggle and Refresh Button -->
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <!-- Custom toggle switch as a div, with wire:click directly on it -->
                        <div wire:click="toggleShowAllModules"
                             wire:loading.class="opacity-50 cursor-not-allowed"
                             class="w-11 h-6 bg-gray-200 rounded-full transition-all duration-300 
                                    {{ $showAllModules ? 'bg-primary-600' : 'bg-gray-300' }}
                                    cursor-pointer">
                            <!-- The moving circle inside the toggle -->
                            <div class="h-5 w-5 bg-white border border-gray-300 rounded-full 
                                        absolute top-[2px] left-[2px] 
                                        transition-transform" style="{{ $showAllModules ? 'transform: translateX(20px)' : 'transform: translateX(0)' }}">
                            </div>
                        </div>

                        <!-- Label text next to the toggle -->
                        <span class="ml-3 text-sm font-medium">
                            Show All Modules
                            <span wire:loading wire:target="toggleShowAllModules" class="text-xs text-gray-500">
                                (Loading...)
                            </span>
                        </span>
                    </label>
                    

                </div>
            </div>

            <!-- Module Switcher -->
            @if(!$showAllModules && $modules->count() > 1)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-5">
                    <h3 class="text-lg font-semibold mb-4">Select Modules ({{ count($selectedModules) }} selected)</h3>
                    
                    @php
                        $groupedModules = $this->getModulesGroupedByLevel();
                    @endphp
                    
                    <div class="space-y-4">
                        @foreach($groupedModules as $levelName => $levelModules)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b">
                                    <h4 class="font-semibold text-gray-700">{{ $levelName }}</h4>
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($levelModules as $module)
                                            <button wire:click="toggleModule({{ $module->id }})"
                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                    class="px-4 py-2 rounded-lg border-2 transition-all duration-200 font-medium text-sm relative
                                                           @if(in_array($module->id, $selectedModules))
                                                               border-primary-500 bg-primary-50 text-primary-700 shadow-md
                                                           @else
                                                               border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50
                                                           @endif">
                                                {{ $module->name }}
                                                @if(in_array($module->id, $selectedModules))
                                                    <span style="left: -.5rem" class="absolute -top-2  w-5 h-5 bg-primary-500 text-white text-xs rounded-full flex items-center justify-center">
                                                        ✓
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Loading Overlay -->


        <!-- Progress Tables -->
        @if($showAllModules)
            <!-- All Modules View -->
            <div class="space-y-6">
                @foreach($modules as $module)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r text-white from-primary-500 to-primary-600 px-6 py-4">
                            <h3 class="text-xl font-bold">{{ $module->name }}</h3>
                            <p class="text-primary-100 text-sm">{{ $module->units->count() }} units</p>
                        </div>
                        
                        <div class="relative overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-4 text-left text-sm font-semibold border-b sticky left-0 z-10 bg-gray-50 min-w-[300px]">Student</th>
                                        @foreach($module->units as $unit)
                                            <th class="text-center px-4 py-4 text-xs font-semibold border-b min-w-[80px]">
                                                <div class="unit-tooltip-container">
                                                    <a href="{{ route('lesson', ['classroom' => $classroom->id, 'id' => $module->id, 'unitId' => $unit->id]) }}" 
                                                       class="block w-full h-full cursor-pointer hover:text-primary-600 transition-colors"
                                                       title="{{ $unit->name }}">
                                                        <span class="font-bold">{{ $loop->iteration }}</span>
                                                    </a>
                                                    <div class="unit-tooltip-content">
                                                        {{ $unit->name }}
                                                    </div>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 border-b sticky left-0 z-10 bg-white hover:bg-gray-50 min-w-[300px]">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ getAvatar($student->user) }}" alt="Avatar" 
                                                         class="h-10 w-10 rounded-full border-2 border-primary-200 shadow-sm">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <div class="font-medium">{{ $student->user->name }}</div>
                                                            <button wire:click="addOneStep({{ $student->user->id }}, {{ $module->id }})"
                                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                                    class="inline-flex items-center justify-center w-6 h-6 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition-colors"
                                                                    title="Add 1 Step">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $student->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach($module->units as $unit)
                                                <td class="text-center px-4 py-4 border-b min-w-[80px]">
                                                    @php
                                                        $hasAccess = $studentProgress[$student->user->id][$unit->id] ?? false;
                                                    @endphp
                                                    
                                                    <button wire:click="toggleUnitAccess({{ $student->user->id }}, {{ $unit->id }})"
                                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full transition-all duration-200 hover:scale-110"
                                                            style="color:white; {{ $hasAccess ? 'background-color: #10B981; border: 1px solid #059669;' : 'background-color: #EF4444; border: 1px solid #DC2626;' }}"
                                                            title="{{ $hasAccess ? 'Click to remove access' : 'Click to grant access' }}">
                                                        @if($hasAccess)
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                    </div>
                @endforeach
            </div>
        @else
            <!-- Selected Modules View -->
            @if(!empty($selectedModules))
                <div class="space-y-6">
                    @foreach($modules as $module)
                        @if(in_array($module->id, $selectedModules))
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="bg-gradient-to-r text-white from-primary-500 to-primary-600 px-6 py-4">
                                    <h3 class="text-xl font-bold ">{{ $module->name }}</h3>
                                    <p class="text-primary-100 text-sm">{{ $module->units->count() }} units</p>
                                </div>
                                
                                <div class="relative overflow-x-auto">
                                    <table class="w-full min-w-max">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-6 py-4 text-left text-sm font-semibold border-b sticky left-0 z-10 bg-gray-50 min-w-[300px]">Student</th>
                                                @foreach($module->units as $unit)
                                                    <th class="text-center px-4 py-4 text-xs font-semibold border-b min-w-[80px]">
                                                        <div class="unit-tooltip-container">
                                                            <a href="{{ route('lesson', ['classroom' => $classroom->id, 'id' => $module->id, 'unitId' => $unit->id]) }}" 
                                                               class="block w-full h-full cursor-pointer hover:text-primary-600 transition-colors"
                                                               title="{{ $unit->name }}">
                                                                <span class="font-bold">{{ $loop->iteration }}</span>
                                                            </a>
                                                            <div class="unit-tooltip-content">
                                                                {{ $unit->name }}
                                                            </div>
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 border-b sticky left-0 z-10 bg-white hover:bg-gray-50 min-w-[300px]">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ getAvatar($student->user) }}" alt="Avatar" 
                                                         class="h-10 w-10 rounded-full border-2 border-primary-200 shadow-sm">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <div class="font-medium">{{ $student->user->name }}</div>
                                                            <button wire:click="addOneStep({{ $student->user->id }}, {{ $module->id }})"
                                                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                                                    class="inline-flex items-center justify-center w-6 h-6 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition-colors"
                                                                    title="Add 1 Step">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $student->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach($module->units as $unit)
                                                <td class="text-center px-4 py-4 border-b min-w-[80px]">
                                                    @php
                                                        $hasAccess = $studentProgress[$student->user->id][$unit->id] ?? false;
                                                    @endphp
                                                    
                                                    <button wire:click="toggleUnitAccess({{ $student->user->id }}, {{ $unit->id }})"
                                                            wire:loading.class="opacity-50 cursor-not-allowed"
                                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full transition-all duration-200 hover:scale-110"
                                                            style="color:white; {{ $hasAccess ? 'background-color: #10B981; border: 1px solid #059669;' : 'background-color: #EF4444; border: 1px solid #DC2626;' }}"
                                                            title="{{ $hasAccess ? 'Click to remove access' : 'Click to grant access' }}">
                                                        @if($hasAccess)
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium mb-2">No Modules Selected</h3>
                        <p class="text-gray-500">Please select at least one module to view student progress.</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
    

</x-filament::page>
