<?php

namespace App\Filament\Resources\SortingItemResource\Pages;

use App\Filament\Resources\SortingItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSortingItem extends EditRecord
{
    protected static string $resource = SortingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
