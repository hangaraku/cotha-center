<?php

namespace App\Filament\Resources\ClassroomResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\ClassroomSession;
use App\Filament\Resources\ClassroomSessionResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ProgressControl extends Page
{
    protected static string $resource = ClassroomResource::class;
    protected static string $view = 'filament.resources.classroom-resource.pages.progress-control';

    public ?Classroom $classroom = null;
    public $modules = null;
    public $students = null;
    public bool $showAllModules = false;
    public $selectedModules = [];
    public $studentProgress = [];
    public $loadedModules = []; // Track which modules are loaded
    public $isLoading = false;
    public $activeSession = null;

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;
        
        // Check if user has a valid session for this classroom
        $user = Auth::user();
        $validSession = ClassroomSession::where('classroom_id', $classroom->id)
            ->where('teacher_id', $user->id)
            ->where('status', 'active')
            ->whereDate('session_date', now()->toDateString())
            ->first();
            
        if (!$validSession) {
            // Show a notification and redirect to session creation page
            \Filament\Notifications\Notification::make()
                ->title('Session Required')
                ->body('You need to create a valid session before viewing student progress. Sessions are valid until 12 AM of the same day.')
                ->warning()
                ->persistent()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('create_session')
                        ->label('Create Session')
                        ->url(ClassroomSessionResource::getUrl('create', ['classroom_id' => $classroom->id]))
                        ->button()
                        ->color('primary'),
                ])
                ->send();
                
            // Redirect to session creation page
            $this->redirect(ClassroomSessionResource::getUrl('create', [
                'classroom_id' => $classroom->id,
                'error' => 'You need to create a valid session before viewing student progress.'
            ]));
            return;
        }

        // Store session info for display
        $this->activeSession = $validSession;
        
        $this->modules = $classroom->projects()->with('level')->orderBy('modules.order_number')->get();
        $this->students = $classroom->students;
        $this->showAllModules = false;
        $this->selectedModules = [$this->modules->first()->id];
        $this->calculateStudentProgress();
    }

    public function getModulesGroupedByLevel()
    {
        return $this->modules->groupBy('level.name');
    }

    public function calculateStudentProgress()
    {
        $this->studentProgress = [];
        if (empty($this->selectedModules)) return;
        
        // Use cache key based on classroom and selected modules
        $cacheKey = "progress_{$this->classroom->id}_" . implode('_', $this->selectedModules);
        
        // Try to get from cache first
        $cachedProgress = Cache::get($cacheKey);
        if ($cachedProgress !== null) {
            $this->studentProgress = $cachedProgress;
            return;
        }
        
        // Use a single optimized query to get all user unit access for selected modules
        $userUnits = DB::table('user_units')
            ->join('units', 'user_units.unit_id', '=', 'units.id')
            ->whereIn('units.module_id', $this->selectedModules)
            ->whereIn('user_units.user_id', $this->students->pluck('user_id'))
            ->select('user_units.user_id', 'user_units.unit_id')
            ->get()
            ->groupBy('user_id')
            ->map(function ($units) {
                return $units->pluck('unit_id')->toArray();
            });

        foreach ($this->students as $student) {
            $studentUnits = $userUnits->get($student->user_id, []);
            foreach ($this->modules as $module) {
                if (in_array($module->id, $this->selectedModules)) {
                    foreach ($module->units as $unit) {
                        $this->studentProgress[$student->user_id][$unit->id] = in_array($unit->id, $studentUnits);
                    }
                }
            }
        }
        
        // Cache the result for 5 minutes
        Cache::put($cacheKey, $this->studentProgress, 300);
    }

    public function toggleModule($moduleId)
    {
        if (in_array($moduleId, $this->selectedModules)) {
            // Remove from selection
            $this->selectedModules = array_diff($this->selectedModules, [$moduleId]);
        } else {
            // Add to selection
            $this->selectedModules[] = $moduleId;
        }
        
        // Ensure at least one module is selected
        if (empty($this->selectedModules)) {
            $this->selectedModules = [$this->modules->first()->id];
        }
        
        $this->calculateStudentProgress();
    }

    public function toggleShowAllModules()
    {
        $this->showAllModules = !$this->showAllModules;
        
        if ($this->showAllModules) {
            // Only load all modules if not already loaded
            if (empty($this->loadedModules)) {
                $this->loadAllModulesProgress();
            }
        } else {
            $this->calculateStudentProgress();
        }
    }

    public function loadAllModulesProgress()
    {
        $this->isLoading = true;
        
        // Use cache key for all modules
        $cacheKey = "progress_all_{$this->classroom->id}";
        
        // Try to get from cache first
        $cachedProgress = Cache::get($cacheKey);
        if ($cachedProgress !== null) {
            $this->studentProgress = $cachedProgress;
            $this->loadedModules = $this->modules->pluck('id')->toArray();
            $this->isLoading = false;
            return;
        }
        
        // Use a single optimized query to get all user unit access
        $userUnits = DB::table('user_units')
            ->join('units', 'user_units.unit_id', '=', 'units.id')
            ->join('modules', 'units.module_id', '=', 'modules.id')
            ->whereIn('modules.id', $this->modules->pluck('id'))
            ->whereIn('user_units.user_id', $this->students->pluck('user_id'))
            ->select('user_units.user_id', 'user_units.unit_id')
            ->get()
            ->groupBy('user_id')
            ->map(function ($units) {
                return $units->pluck('unit_id')->toArray();
            });

        $this->studentProgress = [];
        foreach ($this->students as $student) {
            $studentUnits = $userUnits->get($student->user_id, []);
            foreach ($this->modules as $module) {
                foreach ($module->units as $unit) {
                    $this->studentProgress[$student->user_id][$unit->id] = in_array($unit->id, $studentUnits);
                }
            }
        }
        
        // Cache the result for 5 minutes
        Cache::put($cacheKey, $this->studentProgress, 300);
        
        $this->loadedModules = $this->modules->pluck('id')->toArray();
        $this->isLoading = false;
    }

    public function toggleUnitAccess($studentId, $unitId)
    {
        $userUnit = \App\Models\UserUnit::where('user_id', $studentId)
            ->where('unit_id', $unitId)
            ->first();

        if ($userUnit) {
            // Remove access - Observer will automatically subtract points
            $userUnit->delete();
            $this->studentProgress[$studentId][$unitId] = false;
        } else {
            // Grant access - Observer will automatically add points
            \App\Models\UserUnit::create([
                'user_id' => $studentId,
                'unit_id' => $unitId,
            ]);
            $this->studentProgress[$studentId][$unitId] = true;
            
            // Check if this is the first unit being opened from this module
            $unit = \App\Models\Unit::find($unitId);
            if ($unit) {
                $moduleId = $unit->module_id;
                
                // Check if user has any other units from this module
                $otherUnitsFromModule = \App\Models\UserUnit::where('user_id', $studentId)
                    ->whereHas('unit', function($query) use ($moduleId) {
                        $query->where('module_id', $moduleId);
                    })
                    ->where('unit_id', '!=', $unitId)
                    ->exists();
                
                // If this is the first unit from this module, create UserModule record
                if (!$otherUnitsFromModule) {
                    \App\Models\UserModule::firstOrCreate([
                        'user_id' => $studentId,
                        'module_id' => $moduleId,
                    ], [
                        'user_module_score_id' => 2, // "Unmarked" score
                        'user_module_status_id' => 1, // "Avaliable" status
                    ]);
                }
            }
        }
        
        // Clear cache when data changes
        $this->clearProgressCache();
    }

    // Helper method to check if a module is loaded
    public function isModuleLoaded($moduleId)
    {
        return in_array($moduleId, $this->loadedModules);
    }

    // Helper method to get loading state
    public function getLoadingState()
    {
        return $this->isLoading;
    }

    // Clear progress cache when data changes
    private function clearProgressCache()
    {
        Cache::forget("progress_all_{$this->classroom->id}");
        foreach ($this->modules as $module) {
            Cache::forget("progress_{$this->classroom->id}_{$module->id}");
        }
    }

    public function addOneStep($studentId, $moduleId)
    {
        // Find the module
        $module = $this->modules->find($moduleId);
        if (!$module) {
            return;
        }

        // Get all units for this module ordered by order_number
        $units = $module->units()->orderBy('order_number')->get();
        
        // Find the first unit that the student doesn't have access to
        $unitToGrant = null;
        foreach ($units as $unit) {
            $hasAccess = $this->studentProgress[$studentId][$unit->id] ?? false;
            if (!$hasAccess) {
                $unitToGrant = $unit;
                break;
            }
        }

        // If all units are already granted, don't do anything
        if (!$unitToGrant) {
            \Filament\Notifications\Notification::make()
                ->title('No More Units')
                ->body('Student already has access to all units in this module.')
                ->warning()
                ->send();
            return;
        }

        // Grant access to the next unit
        \App\Models\UserUnit::create([
            'user_id' => $studentId,
            'unit_id' => $unitToGrant->id,
        ]);
        
        // Update the progress array
        $this->studentProgress[$studentId][$unitToGrant->id] = true;
        
        // Check if this is the first unit being opened from this module
        $otherUnitsFromModule = \App\Models\UserUnit::where('user_id', $studentId)
            ->whereHas('unit', function($query) use ($moduleId) {
                $query->where('module_id', $moduleId);
            })
            ->where('unit_id', '!=', $unitToGrant->id)
            ->exists();
        
        // If this is the first unit from this module, create UserModule record
        if (!$otherUnitsFromModule) {
            \App\Models\UserModule::firstOrCreate([
                'user_id' => $studentId,
                'module_id' => $moduleId,
            ], [
                'user_module_score_id' => 2, // "Unmarked" score
                'user_module_status_id' => 1, // "Avaliable" status
            ]);
        }
        
        // Clear cache when data changes
        $this->clearProgressCache();
        
        // Show success notification
        \Filament\Notifications\Notification::make()
            ->title('Access Granted')
            ->body("Student now has access to unit: {$unitToGrant->name}")
            ->success()
            ->send();
    }
}
