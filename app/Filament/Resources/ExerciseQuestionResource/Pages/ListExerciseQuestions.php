<?php

namespace App\Filament\Resources\ExerciseQuestionResource\Pages;

use App\Filament\Resources\ExerciseQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExerciseQuestions extends ListRecords
{
    protected static string $resource = ExerciseQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
