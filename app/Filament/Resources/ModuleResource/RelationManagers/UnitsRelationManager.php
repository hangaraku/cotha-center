<?php

namespace App\Filament\Resources\ModuleResource\RelationManagers;

use App\Filament\Resources\UnitResource;
use App\Models\Module;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    protected static ?string $recordTitleAttribute = 'module_id';

   protected static bool $shouldRegisterNavigation = false;
public function form(Form $form): Form
    {
        return $form->columns(2)
        ->schema([
            Forms\Components\Select::make('module_id')->label("Module")
            ->options(fn ($livewire) => Module::where('id', $livewire->ownerRecord->id)->pluck("name","id"))
            ->default(fn ($livewire) => Module::find($livewire->ownerRecord->id)->id)
            ->disabled(true),
            
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\RichEditor::make('description')->label("Topic")
                ->required()
                ->columnSpan(2),
  
            Forms\Components\TextInput::make('point')
                ->required(),
            Forms\Components\TextInput::make('img_url')->label("Youtube Link")
                ->required()
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
 
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('description')->wrap()->label("Topic")->formatStateUsing(fn (string $state) => __( preg_replace('/((\w+\s*){1,50}).*/', '$1...', strip_tags($state)))),
            Tables\Columns\TextColumn::make('point'),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\Action::make("Open in Youtube")
            ->url(fn ($record) => $record->img_url, true),
            Tables\Actions\EditAction::make()
            ->url(fn (Unit $record): string => UnitResource::getUrl('edit', ['record'=>$record])),
            
            Tables\Actions\DeleteAction::make(),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
        ])
 
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->reorderable("order_number")
        ->defaultSort("order_number");
    }    
}
