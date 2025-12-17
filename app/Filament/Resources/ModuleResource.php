<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Filament\Resources\ModuleResource\RelationManagers;
use App\Filament\Resources\ModuleResource\RelationManagers\UnitsRelationManager;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static ?string $navigationGroup = 'Level, Module, & Material';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form->columns(2)
            ->schema([
                Forms\Components\Select::make('level_id')
                    ->relationship('level', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\FileUpload::make('img_url')
                    ->directory('module-images')
                    ->storeFileNamesIn("original_filename"),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level.name'),
                Tables\Columns\ImageColumn::make('img_url')->label('Module Image'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),          
            ])
            ->defaultSort('order_number')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            UnitsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }    
}
