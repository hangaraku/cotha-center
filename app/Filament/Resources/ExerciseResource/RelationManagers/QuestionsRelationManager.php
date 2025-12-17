<?php

namespace App\Filament\Resources\ExerciseResource\RelationManagers;

use App\Filament\Resources\ExerciseQuestionResource;
use App\Models\Exercise;
use App\Models\ExerciseQuestion;
use App\Models\ExerciseType;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Question\Question;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'exerciseQuestions';

    protected static ?string $recordTitleAttribute = 'exerciseType.name';

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Select::make('exercise_id')->label("Exercise")
                ->options(fn ($livewire) => Exercise::where('id', $livewire->ownerRecord->id)->pluck("name","id"))
                ->default(fn ($livewire) => Exercise::find($livewire->ownerRecord->id)->id)
                ->disabled(true)
                ->required(),
                Forms\Components\TextInput::make('score')->label("Score")->required()
             
                ->disabled(false),
                Forms\Components\Select::make('exercise_type_id')->label("Question Type")
                    ->relationship('exerciseType', 'name')
                    ->default(1)
                    ->required(),
                Forms\Components\RichEditor::make("question")->columnSpan(3)->required(),  
                Forms\Components\RichEditor::make("explanation")->columnSpan(3),                
              
            Repeater::make('answerlist')
            ->defaultItems(4)
            ->relationship('multipleChoiceAnswers')
            ->label("Multiple Choice Answer Option")
                ->grid(2)
            
            ->schema([
                Forms\Components\TextInput::make('text')->label("Text Answer"),
                Forms\Components\FileUpload::make('img')->label("Image Answer")
                ->directory('module-images')
                ->storeFileNamesIn("original_filename"),
                Forms\Components\Checkbox::make('is_correct_option')->label("Mark Answer As Correct")->default(false),


            ])->columnSpan(3)->required()->hidden(fn(Callable $get) => ($get('exercise_type_id') !== 1)),

          
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')->html()->wrap()->formatStateUsing(fn (string $state) => __( preg_replace('/((\w+\s*){1,50}).*/', '$1...', strip_tags($state)))),
                Tables\Columns\TextColumn::make('explanation')->html()->wrap()->limit(50)->formatStateUsing(fn ( $state = "") => __( preg_replace('/((\w+\s*){1,50}).*/', '$1...', strip_tags($state)))),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
            
    }
}
