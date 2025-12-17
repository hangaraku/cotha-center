<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrueFalseQuestionResource\Pages;
use App\Filament\Resources\TrueFalseQuestionResource\RelationManagers;
use App\Models\TrueFalseQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrueFalseQuestionResource extends Resource
{
    protected static ?string $model = TrueFalseQuestion::class;

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
                Forms\Components\TextInput::make('img')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_correct')
                    ->required(),
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
                Tables\Columns\TextColumn::make('img'),
                Tables\Columns\IconColumn::make('is_correct')
                    ->boolean(),
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
            'index' => Pages\ListTrueFalseQuestions::route('/'),
            'create' => Pages\CreateTrueFalseQuestion::route('/create'),
            'edit' => Pages\EditTrueFalseQuestion::route('/{record}/edit'),
        ];
    }    
}
