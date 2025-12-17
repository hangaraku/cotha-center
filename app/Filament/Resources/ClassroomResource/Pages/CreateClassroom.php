<?php

namespace App\Filament\Resources\ClassroomResource\Pages;

use App\Filament\Resources\ClassroomResource;
use App\Models\ClassroomLevel;
use Filament\Resources\Pages\CreateRecord;

class CreateClassroom extends CreateRecord
{
    protected static string $resource = ClassroomResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['center_id'] = auth()->user()->center_id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        if (isset($this->data['levels'])) {
            $levelIds = $this->data['levels'] ?? [];
            $mainLevelId = $this->data['main_level_id'] ?? null;

            $syncData = collect($levelIds)->mapWithKeys(function ($id) use ($mainLevelId) {
                return [$id => [
                    'is_active' => true,
                    'is_main_level' => $mainLevelId ? ($id == $mainLevelId) : false,
                ]];
            })->toArray();

            $record->levels()->sync($syncData);

            // If no main level was explicitly set, set the first level as main
            if (! $mainLevelId && ! empty($levelIds)) {
                ClassroomLevel::where('classroom_id', $record->id)
                    ->where('level_id', $levelIds[0])
                    ->update(['is_main_level' => true]);
            }
        }
    }
}
