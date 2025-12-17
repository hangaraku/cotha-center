<?php

namespace App\Filament\Resources\TrueFalseQuestionResource\Pages;

use App\Filament\Resources\TrueFalseQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrueFalseQuestions extends ListRecords
{
    protected static string $resource = TrueFalseQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
