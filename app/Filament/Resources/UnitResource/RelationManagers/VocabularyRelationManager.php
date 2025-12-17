<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VocabularyRelationManager extends RelationManager
{
    protected static string $relationship = 'vocabularies';

    protected static ?string $recordTitleAttribute = 'word';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unit_id')->label("Unit")
                ->options(fn ($livewire) => Unit::where('id', $livewire->ownerRecord->id)->pluck("name","id"))
                ->default(fn ($livewire) => Unit::find($livewire->ownerRecord->id)->id)
                ->disabled(true),
                Forms\Components\TextInput::make('word')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('meaning')
                    ->required()
                    ,
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('word'),
                Tables\Columns\TextColumn::make('meaning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

            ]);
    }
}
