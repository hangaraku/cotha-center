<?php

namespace App\Filament\Resources\ClassroomScheduleResource\Pages;

use App\Filament\Resources\ClassroomScheduleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomSchedules extends ListRecords
{
    protected static string $resource = ClassroomScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
