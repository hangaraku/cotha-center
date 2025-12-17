<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomScheduleSessionReplacementResource\Pages;
use App\Filament\Resources\ClassroomScheduleSessionReplacementResource\RelationManagers;
use App\Models\ClassroomScheduleSessionReplacement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomScheduleSessionReplacementResource extends Resource
{
    protected static ?string $model = ClassroomScheduleSessionReplacement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_teacher_id')
                    ->relationship('classroomTeacher', 'id')
                    ->required(),
                Forms\Components\Select::make('classroom_schedule_session_id')
                    ->relationship('classroomScheduleSession', 'id')
                    ->required(),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
                Forms\Components\TextInput::make('venue')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classroomTeacher.id'),
                Tables\Columns\TextColumn::make('classroomScheduleSession.id'),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('venue'),
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
            'index' => Pages\ListClassroomScheduleSessionReplacements::route('/'),
            'create' => Pages\CreateClassroomScheduleSessionReplacement::route('/create'),
            'edit' => Pages\EditClassroomScheduleSessionReplacement::route('/{record}/edit'),
        ];
    }    
}
