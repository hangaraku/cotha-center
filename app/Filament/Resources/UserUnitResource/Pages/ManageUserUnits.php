<?php

namespace App\Filament\Resources\UserUnitResource\Pages;

use App\Filament\Resources\UserUnitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserUnits extends ManageRecords
{
    protected static string $resource = UserUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
