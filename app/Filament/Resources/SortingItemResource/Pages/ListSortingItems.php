<?php

namespace App\Filament\Resources\SortingItemResource\Pages;

use App\Filament\Resources\SortingItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSortingItems extends ListRecords
{
    protected static string $resource = SortingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
