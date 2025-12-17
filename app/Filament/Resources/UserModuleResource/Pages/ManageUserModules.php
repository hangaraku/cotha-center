<?php

namespace App\Filament\Resources\UserModuleResource\Pages;

use App\Filament\Resources\UserModuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserModules extends ManageRecords
{
    protected static string $resource = UserModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
