<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MultipleChoiceAnswerResource\Pages;
use App\Filament\Resources\MultipleChoiceAnswerResource\RelationManagers;
use App\Models\MultipleChoiceAnswer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MultipleChoiceAnswerResource extends Resource
{
    protected static ?string $model = MultipleChoiceAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('multiple_choice_question_id')
                    ->relationship('multipleChoiceQuestion', 'id')
                    ->required(),
                Forms\Components\TextInput::make('img')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('multipleChoiceQuestion.id'),
                Tables\Columns\TextColumn::make('img'),
                Tables\Columns\TextColumn::make('text'),
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
            'index' => Pages\ListMultipleChoiceAnswers::route('/'),
            'create' => Pages\CreateMultipleChoiceAnswer::route('/create'),
            'edit' => Pages\EditMultipleChoiceAnswer::route('/{record}/edit'),
        ];
    }    
}
