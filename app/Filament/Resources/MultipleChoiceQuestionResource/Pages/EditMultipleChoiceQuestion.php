<?php

namespace App\Filament\Resources\MultipleChoiceQuestionResource\Pages;

use App\Filament\Resources\MultipleChoiceQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMultipleChoiceQuestion extends EditRecord
{
    protected static string $resource = MultipleChoiceQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
