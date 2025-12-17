<?php

namespace App\Filament\Resources\ClassroomResource\Pages;

use App\Filament\Resources\ClassroomResource;
use App\Models\ClassroomLevel;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroom extends EditRecord
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('See Student Progress')
                ->label('See Student Progress')
                ->url(fn () => ClassroomResource::getUrl('progress', ['classroom' => $this->record])
                )
                ->icon('heroicon-o-chart-bar')
                ->color('success'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load the current main level ID for the form
        $mainLevel = ClassroomLevel::where('classroom_id', $this->record->id)
            ->where('is_main_level', true)
            ->first();

        $data['main_level_id'] = $mainLevel?->level_id;

        return $data;
    }

    protected function afterSave(): void
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
