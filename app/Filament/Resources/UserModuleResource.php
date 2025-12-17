<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserModuleResource\Pages;
use App\Filament\Resources\UserModuleResource\RelationManagers;
use App\Models\UserModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserModuleResource extends Resource
{
    protected static ?string $model = UserModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('module_id')
                    ->relationship('module', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('user_module_score_id')
                    ->relationship('userModuleScore', 'name')
                    ->required(),
                Forms\Components\Select::make('user_module_status_id')
                    ->relationship('userModuleStatus', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('module.name'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('userModuleScore.name'),
                Tables\Columns\TextColumn::make('userModuleStatus.name'),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserModules::route('/'),
        ];
    }    
}
