<?php

namespace App\Filament\Resources\ExerciseQuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MultipleChoiceQuestionRelationManager extends RelationManager
{
    protected static string $relationship = 'multipleChoiceQuestions';

    protected static ?string $recordTitleAttribute = 'exercise_id';

    public function form(Form $form): Form
    {
        return $form->columns(2)
            ->schema([
                Forms\Components\TextInput::make('text')->label('Question')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('score')->label('Score')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('correct_option')->label('Answer')
                    ->required()
                    ->maxLength(255),
                    Repeater::make('Option')->columns(2)->columnSpan(2)
                    ->relationship("multipleChoiceAnswers")
                    ->schema([

                        Forms\Components\FileUpload::make('img')
                        ->directory('module-images')
                        ->storeFileNamesIn("original_filename")
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('text')
                        ->maxLength(255),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('text')->label('Question'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
