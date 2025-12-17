<?php

namespace App\Filament\Resources\UserClassroomScheduleSessionStatusResource\Pages;

use App\Filament\Resources\UserClassroomScheduleSessionStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserClassroomScheduleSessionStatuses extends ManageRecords
{
    protected static string $resource = UserClassroomScheduleSessionStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
