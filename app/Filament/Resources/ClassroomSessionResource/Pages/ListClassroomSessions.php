<?php

namespace App\Filament\Resources\ClassroomSessionResource\Pages;

use App\Filament\Resources\ClassroomSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomSessions extends ListRecords
{
    protected static string $resource = ClassroomSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
