<?php

namespace App\Filament\Resources\UserModuleStatusResource\Pages;

use App\Filament\Resources\UserModuleStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserModuleStatuses extends ManageRecords
{
    protected static string $resource = UserModuleStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
