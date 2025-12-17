<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Filament\Pages\ProgressControl;
use App\Filament\Resources\ClassroomResource;
use App\Models\Module;
use App\Tables\Columns\StudentProgressViewer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ViewColumn;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Livewire;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }




    public function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('name')->label("Project")->searchable(),
                    Tables\Columns\TextColumn::make('units_count')->counts("units")->label("Units Count"),
                    Tables\Columns\TextColumn::make('level.name')->label('Level'),
             
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level_id')
                    ->label('Level')
                    ->relationship('level', 'name')
            ])
            ->headerActions([
                Tables\Actions\Action::make("See Student Progress")
                    ->label("See Student Progress")
                    ->url(fn (RelationManager $livewire) => 
                        ClassroomResource::getUrl('progress', ['classroom' => $livewire->ownerRecord])
                    )
                    ->icon('heroicon-o-chart-bar')
            ])
            ->actions([
                // Removed individual "See Student Progress" actions
            ])
            ->bulkActions([
            ]);
    }    

 
}
