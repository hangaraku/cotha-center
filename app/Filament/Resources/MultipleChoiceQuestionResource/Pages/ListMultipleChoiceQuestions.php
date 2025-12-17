<?php

namespace App\Filament\Resources\MultipleChoiceQuestionResource\Pages;

use App\Filament\Resources\MultipleChoiceQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMultipleChoiceQuestions extends ListRecords
{
    protected static string $resource = MultipleChoiceQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
