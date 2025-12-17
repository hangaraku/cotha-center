<?php

namespace App\Filament\Resources\LevelResource\RelationManagers;

use App\Filament\Resources\ModuleResource;
use App\Filament\Resources\UnitResource;
use App\Models\Level;
use App\Models\Module;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $shouldRegisterNavigation = false;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('level_id')->label("Level")
                    ->options(fn($livewire) => Level::where('id', $livewire->ownerRecord->id)->pluck("name", "id"))
                    ->default(fn($livewire) => Level::find($livewire->ownerRecord->id)->id)
                    ->disabled(true),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                ,
                Forms\Components\FileUpload::make('img_url')
                    ->label('Module Image')
                    ->directory('module-images')
                    ->storeFileNamesIn("original_filename"),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number'),

                Tables\Columns\ImageColumn::make('img_url')->label('Module Image'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description')->html()->formatStateUsing(fn(string $state) => __(preg_replace('/((\w+\s*){1,50}).*/', '$1...', strip_tags($state)))),
            ])
            ->reorderable('order_number')
            ->defaultSort('order_number')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Module $record): string => ModuleResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\Action::make('up')
                    ->action(fn(Module $record) => $record->moveOrderUp()),
                Tables\Actions\Action::make('down')
                    ->action(fn(Module $record) => $record->moveOrderDown()),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
