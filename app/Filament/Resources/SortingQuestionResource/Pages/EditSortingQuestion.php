<?php

namespace App\Filament\Resources\SortingQuestionResource\Pages;

use App\Filament\Resources\SortingQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSortingQuestion extends EditRecord
{
    protected static string $resource = SortingQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
