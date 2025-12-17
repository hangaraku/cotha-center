<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserClassroomScheduleSessionReplacementResource\Pages;
use App\Filament\Resources\UserClassroomScheduleSessionReplacementResource\RelationManagers;
use App\Models\UserClassroomScheduleSessionReplacement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserClassroomScheduleSessionReplacementResource extends Resource
{
    protected static ?string $model = UserClassroomScheduleSessionReplacement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static bool $shouldRegisterNavigation = false;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('classroom_schedule_id')
                    ->relationship('classroomSchedule', 'id')
                    ->required(),
                Forms\Components\Select::make('user_classroom_schedule_session_status_id')
                    ->relationship('userClassroomScheduleSessionStatus', 'name')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('credit_used')
                    ->required(),
                Forms\Components\TextInput::make('check_in_time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('classroomSchedule.id'),
                Tables\Columns\TextColumn::make('userClassroomScheduleSessionStatus.name'),
                Tables\Columns\TextColumn::make('note'),
                Tables\Columns\TextColumn::make('credit_used'),
                Tables\Columns\TextColumn::make('check_in_time'),
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
            'index' => Pages\ListUserClassroomScheduleSessionReplacements::route('/'),
            'create' => Pages\CreateUserClassroomScheduleSessionReplacement::route('/create'),
            'edit' => Pages\EditUserClassroomScheduleSessionReplacement::route('/{record}/edit'),
        ];
    }    
}
