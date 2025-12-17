<?php

namespace App\Filament\Resources\ClassroomScheduleSessionReplacementResource\Pages;

use App\Filament\Resources\ClassroomScheduleSessionReplacementResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroomScheduleSessionReplacement extends EditRecord
{
    protected static string $resource = ClassroomScheduleSessionReplacementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
