<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomSchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'ClassroomSchedules';

    protected static ?string $recordTitleAttribute = 'classroom_id';

   protected static bool $shouldRegisterNavigation = false;
public function form(Form $form): Form
    {
        return $form
        ->schema([
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
                ->after('start_time'),
        ])
        ->validationMessages([
            'end_time.after' => 'End time must be after start time.',
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('day'),
            Tables\Columns\TextColumn::make('start_time'),
            Tables\Columns\TextColumn::make('end_time'),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['classroom_id'] = $this->ownerRecord->id;
                    return $data;
                }),
        ]);
    }    
}
