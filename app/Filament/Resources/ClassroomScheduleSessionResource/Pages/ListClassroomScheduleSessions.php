<?php

namespace App\Filament\Resources\ClassroomScheduleSessionResource\Pages;

use App\Filament\Resources\ClassroomScheduleSessionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomScheduleSessions extends ListRecords
{
    protected static string $resource = ClassroomScheduleSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
