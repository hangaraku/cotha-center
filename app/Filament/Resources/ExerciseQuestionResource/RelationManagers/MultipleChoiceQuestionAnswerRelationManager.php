<?php

namespace App\Filament\Resources\ExerciseQuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class MultipleChoiceQuestionAnswerRelationManager extends RelationManager
{
    protected static string $relationship = 'multipleChoiceAnswers';

    protected static ?string $recordTitleAttribute = 'text';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('text')->label("Text Answer"),
            Forms\Components\FileUpload::make('Image Answer')
            ->directory('module-images')
            ->storeFileNamesIn("original_filename"),
            Forms\Components\Checkbox::make('is_correct_option')->label("Mark Answer As Correct")->default(false),


        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Option')->getStateUsing(
                    static function (stdClass $rowLoop, HasTable $livewire): string {
                        $alphabet = range('A', 'Z');
                        return (string) (
                            $alphabet[$rowLoop->iteration-1]
                        );
                    }),
                Tables\Columns\TextColumn::make('text')->label("Answer"),
                Tables\Columns\CheckboxColumn::make('is_correct_option')->label("Answer"),
            ])
            
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->reorderable("order_number");
    }    
}
