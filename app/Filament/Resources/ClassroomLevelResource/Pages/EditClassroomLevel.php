<?php

namespace App\Filament\Resources\ClassroomLevelResource\Pages;

use App\Filament\Resources\ClassroomLevelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroomLevel extends EditRecord
{
    protected static string $resource = ClassroomLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
