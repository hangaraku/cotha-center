<?php

namespace App\Filament\Resources\StudentClassroomResource\Pages;

use App\Filament\Resources\StudentClassroomResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentClassrooms extends ListRecords
{
    protected static string $resource = StudentClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
