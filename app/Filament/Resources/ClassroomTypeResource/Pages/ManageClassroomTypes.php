<?php

namespace App\Filament\Resources\ClassroomTypeResource\Pages;

use App\Filament\Resources\ClassroomTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageClassroomTypes extends ManageRecords
{
    protected static string $resource = ClassroomTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
