<?php

namespace App\Filament\Resources\UserClassroomScheduleSessionResource\Pages;

use App\Filament\Resources\UserClassroomScheduleSessionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserClassroomScheduleSessions extends ListRecords
{
    protected static string $resource = UserClassroomScheduleSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
