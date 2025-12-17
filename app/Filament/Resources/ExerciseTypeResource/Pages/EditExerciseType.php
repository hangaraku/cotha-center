<?php

namespace App\Filament\Resources\ExerciseTypeResource\Pages;

use App\Filament\Resources\ExerciseTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExerciseType extends EditRecord
{
    protected static string $resource = ExerciseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
