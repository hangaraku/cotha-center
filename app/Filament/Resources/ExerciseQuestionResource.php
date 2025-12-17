<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseQuestionResource\Pages;
use App\Filament\Resources\ExerciseQuestionResource\RelationManagers;
use App\Filament\Resources\ExerciseQuestionResource\RelationManagers\MultipleChoiceQuestionAnswerRelationManager;
use App\Filament\Resources\ExerciseQuestionResource\RelationManagers\MultipleChoiceQuestionRelationManager;
use App\Models\ExerciseQuestion;
use App\Models\ExerciseType;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExerciseQuestionResource extends Resource
{
    protected static ?string $model = ExerciseQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('exercise_id')
                    ->relationship('exercise', 'name')
                    ->required(),
                Forms\Components\Select::make('exercise_type_id')
                    ->relationship('exerciseType', 'name')
                    ->required()->default(1)->disabled(),               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exercise.name'),
                Tables\Columns\TextColumn::make('exerciseType.name'),
                Tables\Columns\TextColumn::make('order_number'),
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
    
    public static function getRelations(): array
    {
        return [
            MultipleChoiceQuestionAnswerRelationManager::class
            // MultipleChoiceQuestionRelationManager::class,
            // MultipleChoiceQuestionAnswerRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExerciseQuestions::route('/'),
            'create' => Pages\CreateExerciseQuestion::route('/create'),
            'edit' => Pages\EditExerciseQuestion::route('/{record}/edit'),
        ];
    }    
}
