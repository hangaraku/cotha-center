<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\ClassroomSchedule;
use App\Models\ClassroomSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sessions';

    protected static ?string $recordTitleAttribute = 'id';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['teacher_id'] = Auth::id();
        $data['status'] = 'active';
        
        return $data;
    }

    protected function beforeCreate(): void
    {
        // Check if there's already an active session for this classroom today
        $existingSession = \App\Models\ClassroomSession::where('classroom_id', $this->getOwnerRecord()->id)
            ->where('status', 'active')
            ->whereDate('session_date', $this->data['session_date'])
            ->first();

        if ($existingSession) {
            $this->halt('There is already an active session for this classroom today. Please complete or cancel the existing session first.');
        }
    }

    public function form(Form $form): Form
    {
        $user = Auth::user();
        
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Session Type')
                    ->options([
                        'official' => 'Official (Tied to Schedule)',
                        'unofficial' => 'Unofficial (Off-work)',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'unofficial') {
                            $set('classroom_schedule_id', null);
                        }
                    }),

                Forms\Components\Select::make('classroom_schedule_id')
                    ->label('Class Schedule (for Official sessions)')
                    ->options(function (callable $get) {
                        $classroomId = $this->getOwnerRecord()->id;
                        
                        return ClassroomSchedule::where('classroom_id', $classroomId)
                            ->pluck('id', 'id')
                            ->map(function ($id) {
                                $schedule = ClassroomSchedule::find($id);
                                return "{$schedule->day} {$schedule->start_time} - {$schedule->end_time}";
                            });
                    })
                    ->visible(fn (callable $get) => $get('type') === 'official')
                    ->required(fn (callable $get) => $get('type') === 'official')
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
                    ->required(),

                Forms\Components\TimePicker::make('start_time')
                    ->label('Start Time')
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->required(),

                Forms\Components\TimePicker::make('end_time')
                    ->label('End Time')
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->required()
                    ->after('start_time')
                    ->rules(['after:start_time'])
                    ->validationMessages([
                        'after' => 'End time must be after start time.',
                    ]),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3),

                Forms\Components\FileUpload::make('attendance_proof_photo')
                    ->label('Foto Bukti Presensi (Opsional)')
                    ->helperText('Upload foto sebagai bukti presensi siswa. Foto ini akan membantu memverifikasi kehadiran siswa dalam sesi ini.')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('4:3')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('600')
                    ->maxSize(2048) // 2MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->directory('attendance-proofs')
                    ->visibility('public'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30),
                Tables\Columns\TextColumn::make('attendance_proof_photo')
                    ->label('Bukti Presensi')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state) {
                            return new \Illuminate\Support\HtmlString(
                                '<a href="' . \Illuminate\Support\Facades\Storage::url($state) . '" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Lihat Foto</a>'
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
                Tables\Filters\Filter::make('today')
                    ->label('Today Only')
                    ->query(fn ($query) => $query->whereDate('session_date', Carbon::today())),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Session')
                    ->icon('heroicon-o-plus-circle'),
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
}
