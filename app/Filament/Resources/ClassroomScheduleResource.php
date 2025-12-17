<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomScheduleResource\Pages;
use App\Filament\Resources\ClassroomScheduleResource\RelationManagers;
use App\Models\ClassroomSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomScheduleResource extends Resource
{
    protected static ?string $model = ClassroomSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $label = 'Classroom Schedules';
    protected static ?string $navigationGroup = 'Classroom & Attendance';

   protected static bool $shouldRegisterNavigation = true;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->required(),
                Forms\Components\Select::make('day')
                    ->label('Day')
                    ->options([
                        'Sunday' => 'Sunday', 
                        'Monday' => 'Monday', 
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday'
                    ])
                    ->required()
                    ->default('Monday'),
             
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->seconds(false),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->seconds(false)
                    ->after('start_time')
                    ->validationMessages([
                        'after' => 'End time must be after start time.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name'),
                Tables\Columns\TextColumn::make('day'),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
             
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
            'index' => Pages\ListClassroomSchedules::route('/'),
            'create' => Pages\CreateClassroomSchedule::route('/create'),
            'edit' => Pages\EditClassroomSchedule::route('/{record}/edit'),
        ];
    }    
}
