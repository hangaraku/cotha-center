<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserUnitResource\Pages;
use App\Filament\Resources\UserUnitResource\RelationManagers;
use App\Models\User;
use App\Models\UserUnit;
use Doctrine\DBAL\Schema\View;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserUnitResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = "User Progress";

    protected static ?string $slug = 'user-units';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TagsColumn::make('Unit Open')->view('tables.columns.all-user-unit')
                    
            ])
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
  
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserUnits::route('/'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('students');
    }
}
