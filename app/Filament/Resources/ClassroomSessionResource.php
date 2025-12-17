<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomSessionResource\Pages;
use App\Filament\Resources\ClassroomSessionResource\RelationManagers;
use App\Models\ClassroomSession;
use App\Models\Classroom;
use App\Models\ClassroomSchedule;
use App\Models\StudentClassroom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\BladeComponent;
use Illuminate\Support\Facades\Storage;

class ClassroomSessionResource extends Resource
{
    protected static ?string $model = ClassroomSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $label = 'Class Sessions';
    protected static ?string $navigationGroup = 'Classroom & Attendance';

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        
        // Get active classrooms where the user is a teacher
        $teacherClassrooms = Classroom::active()->whereHas('classroomTeachers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->pluck('name', 'id');

        return $form
            ->schema([
                Forms\Components\Select::make('classroom_id')
                    ->label('Classroom')
                    ->options($teacherClassrooms)
                    ->required()
                    ->reactive()
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('classroom_schedule_id', null);
                        }
                    }),

                Forms\Components\Select::make('type')
                    ->label('Session Type')
                    ->options([
                        'official' => 'Official (Tied to Schedule)',
                        'unofficial' => 'Unofficial (Off-work)',
                    ])
                    ->required()
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'unofficial') {
                            $set('classroom_schedule_id', null);
                        }
                    }),

                Forms\Components\Select::make('classroom_schedule_id')
                    ->label('Class Schedule (for Official sessions)')
                    ->options(function (callable $get) {
                        $classroomId = $get('classroom_id');
                        if (!$classroomId) return [];
                        
                        return ClassroomSchedule::where('classroom_id', $classroomId)
                            ->pluck('id', 'id')
                            ->map(function ($id) {
                                $schedule = ClassroomSchedule::find($id);
                                return "{$schedule->day} {$schedule->start_time} - {$schedule->end_time}";
                            });
                    })
                    ->visible(fn (callable $get) => $get('type') === 'official')
                    ->required(fn (callable $get) => $get('type') === 'official')
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $schedule = ClassroomSchedule::find($state);
                            if ($schedule) {
                                $set('start_time', $schedule->start_time);
                                $set('end_time', $schedule->end_time);
                            }
                        }
                    }),

                Forms\Components\DatePicker::make('session_date')
                    ->label('Session Date')
                    ->default(Carbon::today())
                    ->required()
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0),

                Forms\Components\TimePicker::make('start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->required()
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0),

                Forms\Components\TimePicker::make('end_time')
                    ->label('End Time')
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->required()
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0)
                    ->after('start_time')
                    ->rules(['after:start_time'])
                    ->validationMessages([
                        'after' => 'End time must be after start time.',
                    ]),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0),

                Forms\Components\FileUpload::make('attendance_proof_photo')
                    ->label('Foto Bukti Presensi')
                    ->helperText('Upload foto sebagai bukti presensi siswa. Foto ini akan membantu memverifikasi kehadiran siswa dalam sesi ini.')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('4:3')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('600')
                    ->maxSize(5048) // 5MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->directory('attendance-proofs')
                    ->visibility('public')
                    ->disabled(fn ($record) => $record && $record->status === 'completed' && $user->roles->where('name', 'super_admin')->count() === 0),

                Forms\Components\Section::make('Student Attendance')
                    ->schema([
                        Forms\Components\View::make('filament.components.attendance-manager')
                            ->viewData(fn ($record) => ['classroomSessionId' => $record?->id]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('Classroom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'official' => 'success',
                        'unofficial' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('session_date')
                    ->label('Date')
                    ->date(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Teacher'),
                Tables\Columns\TextColumn::make('attendance_proof_photo')
                    ->label('Bukti Presensi')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state) {
                            return new \Illuminate\Support\HtmlString(
                                '<a href="' . Storage::url($state) . '" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Lihat Foto</a>'
                            );
                        }
                        return 'Tidak ada foto';
                    })
                    ->html(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'official' => 'Official',
                        'unofficial' => 'Unofficial',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_progress')
                    ->label('View Progress')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn (ClassroomSession $record) => 
                        \App\Filament\Resources\ClassroomResource::getUrl('progress', ['classroom' => $record->classroom_id])
                    )
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('complete')
                    ->label('Complete Session')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ClassroomSession $record) => $record->status === 'active')
                    ->action(function (ClassroomSession $record) {
                        $record->update(['status' => 'completed']);
                        
                        // Note: Credit deduction will be handled when users are marked present
                        // This is for future implementation
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel Session')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ClassroomSession $record) => $record->status === 'active')
                    ->action(function (ClassroomSession $record) {
                        $record->update(['status' => 'cancelled']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('session_date', 'desc');
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
            'index' => Pages\ListClassroomSessions::route('/'),
            'create' => Pages\CreateClassroomSession::route('/create'),
            'edit' => Pages\EditClassroomSession::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery();
        
        // Only show sessions from active classrooms
        $query->whereHas('classroom', function ($q) {
            $q->active();
        });
        
        // Teachers can only see their own sessions
        if ($user && $user->roles->where('name', 'Teacher')->count() > 0) {
            return $query->where('teacher_id', $user->id);
        }
        
        // Super admins and other admins can see all sessions
        if ($user && ($user->roles->where('name', 'super_admin')->count() > 0 || $user->roles->where('name', 'Admin')->count() > 0)) {
            return $query;
        }
        
        // Default fallback - show all sessions for other roles
        return $query;
    }
}
