<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MultipleChoiceQuestionResource\Pages;
use App\Filament\Resources\MultipleChoiceQuestionResource\RelationManagers;
use App\Models\MultipleChoiceQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MultipleChoiceQuestionResource extends Resource
{
    protected static ?string $model = MultipleChoiceQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('exercise_question_id')
                    ->relationship('exerciseQuestion', 'id')
                    ->required(),
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('correct_option')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('score')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exerciseQuestion.id'),
                Tables\Columns\TextColumn::make('text'),
                Tables\Columns\TextColumn::make('correct_option'),
                Tables\Columns\TextColumn::make('score'),
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
            'index' => Pages\ListMultipleChoiceQuestions::route('/'),
            'create' => Pages\CreateMultipleChoiceQuestion::route('/create'),
            'edit' => Pages\EditMultipleChoiceQuestion::route('/{record}/edit'),
        ];
    }    
}
