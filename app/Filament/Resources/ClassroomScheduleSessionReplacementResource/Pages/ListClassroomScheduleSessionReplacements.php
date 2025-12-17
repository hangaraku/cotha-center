<?php

namespace App\Filament\Resources\ClassroomScheduleSessionReplacementResource\Pages;

use App\Filament\Resources\ClassroomScheduleSessionReplacementResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomScheduleSessionReplacements extends ListRecords
{
    protected static string $resource = ClassroomScheduleSessionReplacementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
