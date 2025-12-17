<?php

namespace App\Filament\Resources\ClassroomScheduleSessionResource\Pages;

use App\Filament\Resources\ClassroomScheduleSessionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroomScheduleSession extends EditRecord
{
    protected static string $resource = ClassroomScheduleSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
