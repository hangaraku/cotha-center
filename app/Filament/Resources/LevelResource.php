<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Filament\Resources\LevelResource\RelationManagers;
use App\Filament\Resources\LevelResource\RelationManagers\ModulesRelationManager;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LevelResource extends Resource
{
    public static function canCreate(): bool
    {
       return true;
    }
    protected static ?string $model = Level::class;

    protected static ?string $navigationGroup = 'Level, Module, & Material';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $label = 'Levels';

    public static function getEloquentQuery(): Builder
    {
    return parent::getEloquentQuery()->whereBelongsTo(auth()->user()->center);
    }


   protected static bool $shouldRegisterNavigation = true;
public static function form(Form $form): Form
    {
        return $form->columns(2)
            ->schema([
                Forms\Components\Select::make('center_id')->relationship('center','name')
                ->default(Auth::user()->center->id)->disabled()->label("Center"),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\FileUpload::make('img_url')->label("Image")
                    ->required()
                    ->directory("level-images")
                    ->storeFileNamesIn("original_filename")
                    ->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('img_url')->label('Level Image'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description')->wrap()->formatStateUsing(fn (string $state) => __( preg_replace('/((\w+\s*){1,50}).*/', '$1...', strip_tags($state)))),  
            ])
            ->reorderable('order_number')
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
            ])
      
           ;
    }



    public static function getRelations(): array
    {
        return [
            ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
