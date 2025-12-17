<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\Unit;
use App\Models\UserModule;
use App\Models\UserModuleScore;
use App\Models\UserModuleStatus;
use App\Models\UserUnit;
use Livewire\Component;

class ProgressBadge extends Component
{
    public StudentClassroom $student;
    public Unit $unit;
    public $is_active;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(StudentClassroom $student, Unit $unit)
    {
        $this->student = $student;
        $this->unit = $unit;
        $hasAnyUnit = $this->student->user->userUnits;
        $hasThisParticularUnit = $this->student->user->userUnits->where('unit_id', '=', $this->unit->id)->count() > 0;
        $this->is_active = $hasAnyUnit && $hasThisParticularUnit;

    }

    public function reupdate(){
        
        $this->is_active = !$this->is_active;

    }
    public function render()
    {
        return view('livewire.progress-badge');
    }

    public function toggleProgress()
    {
        if (!UserUnit::where('user_id', $this->student->user->id)
             ->where('unit_id', $this->unit->id)
             ->exists()){
            if($this->student->user->userModules->where("module_id",$this->unit->module->id)->first() == null){
                UserModule::create([
                    "user_id" => $this->student->user->id,
                    "module_id" => $this->unit->module->id,
                    "user_module_score_id" => UserModuleScore::where('name','Unmarked')->first()->id,
                    "user_module_status_id" => UserModuleStatus::first()->id

                ]);
            }

            // Create UserUnit - Observer will automatically add points
            $status = UserUnit::create([
                 'user_id' => $this->student->user->id,
                 'unit_id' => $this->unit->id,
             ]);
             

         }else{
            // Delete UserUnit - Observer will automatically subtract points
            UserUnit::where('user_id', $this->student->user->id)
             ->where('unit_id', $this->unit->id)
             ->delete();
         }
         
        $this->reupdate();
         
    }


}
