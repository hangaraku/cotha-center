<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatchingItemResource\Pages;
use App\Filament\Resources\MatchingItemResource\RelationManagers;
use App\Models\MatchingItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatchingItemResource extends Resource
{
    protected static ?string $model = MatchingItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('matching_question_id')
                    ->relationship('matchingQuestion', 'id')
                    ->required(),
                Forms\Components\TextInput::make('matching_item_answer_id')
                    ->required(),
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('img')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matchingQuestion.id'),
                Tables\Columns\TextColumn::make('matching_item_answer_id'),
                Tables\Columns\TextColumn::make('text'),
                Tables\Columns\TextColumn::make('img'),
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
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatchingItems::route('/'),
            'create' => Pages\CreateMatchingItem::route('/create'),
            'edit' => Pages\EditMatchingItem::route('/{record}/edit'),
        ];
    }    
}
