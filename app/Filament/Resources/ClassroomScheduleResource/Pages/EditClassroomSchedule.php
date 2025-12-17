<?php

namespace App\Filament\Resources\ClassroomScheduleResource\Pages;

use App\Filament\Resources\ClassroomScheduleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroomSchedule extends EditRecord
{
    protected static string $resource = ClassroomScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
