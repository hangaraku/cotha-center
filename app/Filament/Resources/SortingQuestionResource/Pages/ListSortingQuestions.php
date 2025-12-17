<?php

namespace App\Filament\Resources\SortingQuestionResource\Pages;

use App\Filament\Resources\SortingQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSortingQuestions extends ListRecords
{
    protected static string $resource = SortingQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
