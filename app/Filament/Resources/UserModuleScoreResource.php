<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserModuleScoreResource\Pages;
use App\Filament\Resources\UserModuleScoreResource\RelationManagers;
use App\Models\UserModuleScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserModuleScoreResource extends Resource
{
    protected static ?string $model = UserModuleScore::class;
    protected static ?string $navigationGroup = 'Site Configuration';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $label = 'Module Scores';

   protected static bool $shouldRegisterNavigation = true;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ManageUserModuleScores::route('/'),
        ];
    }    
}
