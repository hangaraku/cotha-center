<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers\ClassroomSchedulesRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\ProjectsRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\SessionsRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\StudentClassroomsRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\TeachersRelationManager;
use App\Models\Classroom;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $label = 'Classrooms';

    protected static ?string $navigationGroup = 'Classroom';

    protected static bool $shouldRegisterNavigation = true;

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = parent::getEloquentQuery();

        // For all roles, filter by center first (including super admin)
        $query = $query->whereBelongsTo($user->center);

        // For non-super_admin users, always show only active classrooms
        // Super admins will use the table filter to control visibility
        if (! $user->hasRole('super_admin')) {
            $query = $query->active();
        }

        if ($user instanceof \App\Models\User && $user->hasRole('Teacher')) {
            // Only show classrooms where this user is a teacher (within their center)
            return $query->whereHas('classroomTeachers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // For other roles (including super admin), return the center-filtered query
        return $query;
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('view_any_classroom');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('classroom_type_id')
                    ->relationship('classroomType', 'name')
                    ->required(),
                Forms\Components\Select::make('levels')
                    ->multiple()
                    ->relationship('levels', 'name')
                    ->label('Course Levels')
                    ->options(Level::where('center_id', Auth::user()->center_id)->get()->pluck('name', 'id'))
                    ->preload(true)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('main_level_id', null)),
                Forms\Components\Select::make('main_level_id')
                    ->label('Main Level (for Certificate)')
                    ->helperText('Select the main level for this classroom. This will be used for certificate naming.')
                    ->options(function (Forms\Get $get) {
                        $selectedLevels = $get('levels') ?? [];
                        if (empty($selectedLevels)) {
                            return [];
                        }

                        return Level::whereIn('id', $selectedLevels)->pluck('name', 'id');
                    })
                    ->placeholder('First level will be used as default')
                    ->nullable()
                    ->visible(fn (Forms\Get $get) => ! empty($get('levels'))),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\TextInput::make('total_credit')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Hide this classroom from students and teachers')
                    ->default(true)
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),
                Forms\Components\Select::make('classroomTeachers')
                    ->multiple()
                    ->relationship('classroomTeachers', 'name', function (Builder $query) {
                        $query->whereBelongsTo(Auth::user()->center)
                            ->role('Teacher');
                    })
                    ->required()
                    ->label('Teacher')
                    ->preload(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(name: 'classroomType.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('start_date'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->is_active ? 'Active' : 'Hidden'),
                // Remove end_date column
                // Show remaining credit: total_credit - official session count
                Tables\Columns\TextColumn::make('remaining_credit')
                    ->label('Remaining Credit')
                    ->getStateUsing(function ($record) {
                        $officialCount = $record->sessions()->where('type', 'official')->count();

                        return $record->total_credit - $officialCount;
                    }),
                // Add end_date column showing unofficial session count
                Tables\Columns\TextColumn::make('unofficial_sessions')
                    ->label('Unofficial Sessions')
                    ->getStateUsing(function ($record) {
                        return $record->sessions()->where('type', 'unofficial')->count();
                    }),
                // Mentor column, only for super admin
                Tables\Columns\TextColumn::make('mentors')
                    ->label('Mentor')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->getStateUsing(function ($record) {
                        return $record->teachers->map(fn ($t) => $t->user->name)->implode(', ');
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active Only',
                        '0' => 'Hidden Only',
                    ])
                    ->default('1')
                    ->placeholder('All Classrooms')
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->is_active ? 'Hide' : 'Show')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->action(function ($record) {
                        $record->update(['is_active' => ! $record->is_active]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->is_active ? 'Hide Classroom' : 'Show Classroom')
                    ->modalDescription(fn ($record) => $record->is_active
                        ? "Are you sure you want to hide '{$record->name}'? Students and teachers won't be able to see it."
                        : "Are you sure you want to show '{$record->name}'? Students and teachers will be able to see it again."
                    ),
                Tables\Actions\Action::make('session_action')
                    ->label(fn (Classroom $record) => $record->sessions()
                        ->where('status', 'active')
                        ->whereDate('session_date', now()->toDateString())
                        ->exists()
                            ? 'View Today Session'
                            : 'Create Session'
                    )
                    ->icon(fn (Classroom $record) => $record->sessions()
                        ->where('status', 'active')
                        ->whereDate('session_date', now()->toDateString())
                        ->exists()
                            ? 'heroicon-o-eye'
                            : 'heroicon-o-plus-circle'
                    )
                    ->color(fn (Classroom $record) => $record->sessions()
                        ->where('status', 'active')
                        ->whereDate('session_date', now()->toDateString())
                        ->exists()
                            ? 'info'
                            : 'primary'
                    )
                    ->url(fn (Classroom $record) => ($session = $record->sessions()
                        ->where('status', 'active')
                        ->whereDate('session_date', now()->toDateString())
                        ->first())
                            ? \App\Filament\Resources\ClassroomSessionResource::getUrl('edit', ['record' => $session->id])
                            : \App\Filament\Resources\ClassroomSessionResource::getUrl('create', ['classroom_id' => $record->id])
                    ),
                Tables\Actions\Action::make('See Student Progress')
                    ->label('See Student Progress')
                    ->url(fn (Classroom $record) => static::getUrl('progress', ['classroom' => $record])
                    )
                    ->icon('heroicon-o-chart-bar')
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])

            ->reorderable('order_number')
            ->defaultSort('order_number');
    }

    public static function getRelations(): array
    {
        return [
            ProjectsRelationManager::class,
            StudentClassroomsRelationManager::class,
            SessionsRelationManager::class,
            ClassroomSchedulesRelationManager::class,
            TeachersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'progress' => Pages\ProgressControl::route('/progress/{classroom}'),
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'view' => Pages\ViewClassroom::route('/{record}'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }
}
