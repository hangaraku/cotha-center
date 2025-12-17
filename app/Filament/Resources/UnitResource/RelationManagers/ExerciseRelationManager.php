<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use App\Filament\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExerciseRelationManager extends RelationManager
{
    
    protected static string $relationship = 'exercises';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unit_id')->label("Unit")
                ->options(fn ($livewire) => Unit::where('id', $livewire->ownerRecord->id)->pluck("name","id"))
                ->default(fn ($livewire) => Unit::find($livewire->ownerRecord->id)->id)
                ->disabled(true),
                Forms\Components\TextInput::make('name')
                    ->label("Exercise Name")
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label("Exercise Name"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->url(fn (Exercise $record): string => ExerciseResource::getUrl('edit', $record)),
                Tables\Actions\DeleteAction::make()
                ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ]);
    }
}
