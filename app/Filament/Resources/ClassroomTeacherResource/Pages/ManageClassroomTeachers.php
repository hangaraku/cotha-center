<?php

namespace App\Filament\Resources\ClassroomTeacherResource\Pages;

use App\Filament\Resources\ClassroomTeacherResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageClassroomTeachers extends ManageRecords
{
    protected static string $resource = ClassroomTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
