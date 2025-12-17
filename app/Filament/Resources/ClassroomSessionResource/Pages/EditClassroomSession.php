<?php

namespace App\Filament\Resources\ClassroomSessionResource\Pages;

use App\Filament\Resources\ClassroomSessionResource;
use App\Models\ClassroomSessionAttendance;
use App\Models\StudentClassroom;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class EditClassroomSession extends EditRecord
{
    protected static string $resource = ClassroomSessionResource::class;

    public $attendance = [];
    public $finalized = false;

    public function mount($record): void
    {
        parent::mount($record);
        $this->finalized = $this->record->finalized;
        $this->attendance = $this->getAttendanceList();
    }

    public function getAttendanceList()
    {
        $session = $this->record;
        $students = StudentClassroom::where('classroom_id', $session->classroom_id)->get();
        $attendance = [];
        foreach ($students as $student) {
            $att = ClassroomSessionAttendance::firstOrCreate([
                'classroom_session_id' => $session->id,
                'student_id' => $student->id,
            ], [
                'is_present' => false,
            ]);
            $attendance[$student->id] = [
                'name' => $student->user->name,
                'is_present' => $att->is_present,
            ];
        }
        return $attendance;
    }

    public function toggleAttendance($studentId)
    {
        if ($this->finalized) return;
        $session = $this->record;
        $att = ClassroomSessionAttendance::where('classroom_session_id', $session->id)
            ->where('student_id', $studentId)->first();
        if ($att) {
            $att->is_present = !$att->is_present;
            $att->save();
            $this->attendance[$studentId]['is_present'] = $att->is_present;
        }
    }

    public function finalizeAttendance()
    {
        if ($this->finalized) return;
        $session = $this->record;
        DB::transaction(function () use ($session) {
            $presentIds = [];
            foreach ($this->attendance as $studentId => $data) {
                $att = ClassroomSessionAttendance::where('classroom_session_id', $session->id)
                    ->where('student_id', $studentId)->first();
                if ($att && $att->is_present) {
                    $presentIds[] = $studentId;
                }
            }
            // Deduct credit for present students
            if (!empty($presentIds)) {
                StudentClassroom::whereIn('id', $presentIds)->decrement('credit_left');
            }
            $session->finalized = true;
            $session->save();
        });
        $this->finalized = true;
        Notification::make()->success()->title('Attendance finalized')->send();
    }

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        $user = auth()->user();
        $isTeacher = $user->roles->where('name', 'Teacher')->count() > 0;
        $isSuperAdmin = $user->roles->where('name', 'super_admin')->count() > 0;
        
        $actions = [
            Actions\DeleteAction::make(),
            Actions\Action::make('view_progress')
                ->label('See Student Progress')
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->url(\App\Filament\Resources\ClassroomResource::getUrl('progress', ['classroom' => $record->classroom_id]))
                ->openUrlInNewTab(),
        ];

        // Add finalize button only if session is active
        if ($record->status === 'active') {
            $actions[] = Actions\Action::make('finalize_attendance')
                ->label('Finalize Attendance')
                ->icon('heroicon-o-check-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Finalize Attendance')
                ->modalDescription('This will deduct credits from present students and mark the session as completed. This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, Finalize Attendance')
                ->action(function () {
                    $this->finalizeAttendance();
                });
        }

        return $actions;
    }

    protected function getHeaderWidgets(): array
    {
        $record = $this->getRecord();
        $user = auth()->user();
        $isTeacher = $user->roles->where('name', 'Teacher')->count() > 0;
        
        // Show hint for teachers when session is completed
        if ($record->status === 'completed' && $isTeacher) {
            return [
                \App\Filament\Widgets\SessionCompletedHint::class,
            ];
        }
        
        return [];
    }

    protected function getViewData(): array
    {
        return [
            'attendance' => $this->attendance,
            'finalized' => $this->finalized,
        ];
    }
}
