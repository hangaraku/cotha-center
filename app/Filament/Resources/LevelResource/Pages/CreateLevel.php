<?php

namespace App\Filament\Resources\LevelResource\Pages;

use App\Filament\Resources\LevelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLevel extends CreateRecord
{
    protected static string $resource = LevelResource::class;
  	protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['center_id'] = auth()->user()->center_id;

        return $data;
    }
}
