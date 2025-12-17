<?php
// ViewClassroom page for read-only access to classroom details and relations

namespace App\Filament\Resources\ClassroomResource\Pages;

use App\Filament\Resources\ClassroomResource;
use Filament\Resources\Pages\ViewRecord;

class ViewClassroom extends ViewRecord
{
    protected static string $resource = ClassroomResource::class;

    public function getRelationManagers(): array
    {
        return static::getResource()::getRelations();
    }
} 