<?php

namespace App\Filament\Resources\ClassroomSessionResource\Pages;

use App\Filament\Resources\ClassroomSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class CreateClassroomSession extends CreateRecord
{
    protected static string $resource = ClassroomSessionResource::class;

    public function mount(): void
    {
        parent::mount();
        
        // Check for classroom_id parameter
        $classroomId = Request::get('classroom_id');
        if ($classroomId) {
            $this->form->fill(['classroom_id' => $classroomId]);
        }
        
        // Check for error message
        $error = Request::get('error');
        if ($error) {
            Notification::make()
                ->title('Session Required')
                ->body($error)
                ->warning()
                ->send();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['teacher_id'] = Auth::id();
        $data['status'] = 'active';
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Check if this session was created from progress control
        $classroomId = Request::get('classroom_id');
        if ($classroomId) {
            // Redirect to progress control for this classroom
            return \App\Filament\Resources\ClassroomResource::getUrl('progress', ['classroom' => $classroomId]);
        }
        
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        // Check if there's already an active session for this classroom today
        $existingSession = \App\Models\ClassroomSession::where('classroom_id', $this->data['classroom_id'])
            ->where('status', 'active')
            ->whereDate('session_date', $this->data['session_date'])
            ->first();

        if ($existingSession) {
            $this->halt('There is already an active session for this classroom today. Please complete or cancel the existing session first.');
        }
    }
}
