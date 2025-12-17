<?php

namespace App\Filament\Resources\MatchingItemResource\Pages;

use App\Filament\Resources\MatchingItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatchingItems extends ListRecords
{
    protected static string $resource = MatchingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
