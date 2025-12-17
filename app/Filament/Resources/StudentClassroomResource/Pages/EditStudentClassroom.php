<?php

namespace App\Filament\Resources\StudentClassroomResource\Pages;

use App\Filament\Resources\StudentClassroomResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentClassroom extends EditRecord
{
    protected static string $resource = StudentClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
