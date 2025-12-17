<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\ClassroomTeacher;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeachersRelationManager extends RelationManager
{
    protected static string $relationship = 'Teachers';

    protected static ?string $recordTitleAttribute = 'classroom_id';

   protected static bool $shouldRegisterNavigation = false;
public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_id')
                    ->relationship('classroom','name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->options(
                        
                        User::whereIn('id',Admin::where('role_id', AdminRole::where("name","Teacher")->first()->id)->pluck('user_id'))->pluck("name","id")
                    )
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label("Name"),
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
            ]);
    }    
}
