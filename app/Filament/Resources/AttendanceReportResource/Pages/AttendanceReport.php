<?php

namespace App\Filament\Resources\AttendanceReportResource\Pages;

use App\Filament\Resources\AttendanceReportResource;
use Filament\Resources\Pages\Page;

class AttendanceReport extends Page
{
    protected static string $resource = AttendanceReportResource::class;

    protected static string $view = 'filament.resources.attendance-report-resource.pages.attendance-report';
}
