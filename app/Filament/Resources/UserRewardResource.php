<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRewardResource\Pages;
use App\Filament\Resources\UserRewardResource\RelationManagers;
use App\Models\UserReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRewardResource extends Resource
{
    protected static ?string $model = UserReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Reward';
    protected static ?string $label = 'Student Orders';
    protected static bool $shouldRegisterNavigation = true;
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('reward_id')
                    ->relationship('reward', 'name')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.students.school')
                    ->label('School')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Claimed',
                        2 => 'Cancelled',
                    ])
                    ->disabled(fn ($record) => $record->status == 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Claimed',
                        2 => 'Cancelled',
                    ]),
                // Filter by student school (select list, case-insensitive)
                SelectFilter::make('student_school')
                    ->label('School')
                    ->options(fn () => \App\Models\Student::query()
                        ->when(auth()->user() && isset(auth()->user()->center_id), fn ($q) => $q->whereHas('user', fn ($q2) => $q2->where('center_id', auth()->user()->center_id)))
                        ->get()
                        ->pluck('school')
                        ->unique(fn ($v) => mb_strtolower($v))
                        ->sortBy(fn ($v) => mb_strtolower($v))
                        ->mapWithKeys(fn ($v) => [$v => $v])
                        ->toArray()
                    )
                    ->query(function (Builder $query, $value = null): Builder {
                        if (empty($value)) {
                            return $query;
                        }

                        $v = mb_strtolower($value);

                        return $query->whereHas('user', function (Builder $q) use ($v): void {
                            $q->whereHas('students', function (Builder $q2) use ($v): void {
                                $q2->whereRaw('LOWER(school) = ?', [$v]);
                            });
                        });
                    }),
                // Reward filter only shows rewards that have at least one order
                SelectFilter::make('reward_id')
                    ->label('Reward')
                    ->options(fn () => \App\Models\Reward::whereHas('userRewards')->orderBy('name')->pluck('name', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('buy')
                    ->label('Buy')
                    ->icon('heroicon-o-shopping-cart')
                    ->url(fn ($record) => $record->reward->shop_url, true)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status != 2),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->status != 2),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export to CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(\App\Filament\Exporters\UserRewardExporter::class),
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

    /**
     * Restrict the resource's base query to the logged-in user's center.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user && isset($user->center_id)) {
            return $query->whereHas('user', function (Builder $q) use ($user): void {
                $q->where('center_id', $user->center_id);
            });
        }

        return $query;
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserRewards::route('/'),
            'create' => Pages\CreateUserReward::route('/create'),
            'edit' => Pages\EditUserReward::route('/{record}/edit'),
        ];
    }    
}
