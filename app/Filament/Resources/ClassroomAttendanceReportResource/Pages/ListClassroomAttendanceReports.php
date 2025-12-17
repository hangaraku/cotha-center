<?php

namespace App\Filament\Resources\ClassroomAttendanceReportResource\Pages;

use App\Filament\Resources\ClassroomAttendanceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomAttendanceReports extends ListRecords
{
    protected static string $resource = ClassroomAttendanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}










