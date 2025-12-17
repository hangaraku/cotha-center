<?php

namespace App\Filament\Resources\ExerciseQuestionResource\Pages;

use App\Filament\Resources\ExerciseQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExerciseQuestion extends EditRecord
{
    protected static string $resource = ExerciseQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
