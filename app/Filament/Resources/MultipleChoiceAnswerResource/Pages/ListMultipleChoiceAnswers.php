<?php

namespace App\Filament\Resources\MultipleChoiceAnswerResource\Pages;

use App\Filament\Resources\MultipleChoiceAnswerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMultipleChoiceAnswers extends ListRecords
{
    protected static string $resource = MultipleChoiceAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
