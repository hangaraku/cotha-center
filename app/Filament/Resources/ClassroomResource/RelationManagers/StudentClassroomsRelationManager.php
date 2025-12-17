<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\Classroom;
use App\Models\StudentClassroom;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StudentClassroomsRelationManager extends RelationManager
{
    protected static string $relationship = 'studentClassrooms';
    protected static ?string $title = 'Student List';
    protected static ?string $recordTitleAttribute = 'user_id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_ids')
                    ->label('Select Students')
                    ->multiple()
                    ->options(function (RelationManager $livewire) {
                        // Get students already enrolled in this classroom
                        $enrolledStudentIds = $livewire->ownerRecord->studentClassrooms->pluck('user_id');

                        // Get all students from the same center who are not already enrolled
                        return User::whereHas('students')
                            ->whereBelongsTo(Auth::user()->center)
                            ->whereNotIn('id', $enrolledStudentIds)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Select multiple students to add them all with the same credit and status'),

                Forms\Components\TextInput::make('credit_left')
                    ->label('Credit Left')
                    ->numeric()
                    ->default(fn ($livewire) => $livewire->ownerRecord->total_credit ?? 0)
                    ->required()
                    ->helperText('This credit amount will be applied to all selected students'),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required()
                    ->helperText('This status will be applied to all selected students'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adjusted_credit_left')
                    ->label('Credit Left')
                    ->getStateUsing(function ($record) {
                        // $record is StudentClassroom - use StudentClassroom ID, not user_id
                        $presentCount = \App\Models\ClassroomSessionAttendance::whereHas('session', function ($q) use ($record) {
                            $q->where('classroom_id', $record->classroom_id)
                                ->whereIn('type', ['official', 'unofficial']);
                        })->where('student_id', $record->id)
                            ->where('is_present', true)
                            ->count();

                        return $record->credit_left - $presentCount;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('absent_count')
                    ->label('Absent Count')
                    ->hidden()
                    ->getStateUsing(function ($record) {
                        // $record is StudentClassroom - use StudentClassroom ID, not user_id
                        $absentCount = \App\Models\ClassroomSessionAttendance::whereHas('session', function ($q) use ($record) {
                            $q->where('classroom_id', $record->classroom_id)
                                ->where('type', 'official');
                        })->where('student_id', $record->id)
                            ->where('is_present', false)
                            ->count();

                        return $absentCount;
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->hidden()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('engagement_score')
                    ->label('Engagement Score')
                    ->default('Not Set')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 8 => 'success',
                        $state >= 5 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Join Date')
                    ->hidden()
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Students')
                    ->modalHeading('Add Multiple Students')
                    ->modalDescription('Select multiple students to add them to this classroom with the same credit and status.')
                    ->action(function (array $data) {
                        $userIds = $data['user_ids'];
                        $creditLeft = $data['credit_left'];
                        $status = $data['status'];
                        $classroomId = $this->ownerRecord->id;

                        $createdCount = 0;
                        foreach ($userIds as $userId) {
                            // Check if student is already in this classroom
                            $existing = StudentClassroom::where('user_id', $userId)
                                ->where('classroom_id', $classroomId)
                                ->first();

                            if (! $existing) {
                                StudentClassroom::create([
                                    'user_id' => $userId,
                                    'classroom_id' => $classroomId,
                                    'credit_left' => $creditLeft,
                                    'status' => $status,
                                ]);
                                $createdCount++;
                            }
                        }

                        if ($createdCount > 0) {
                            Notification::make()
                                ->success()
                                ->title("Successfully added {$createdCount} student(s) to the classroom")
                                ->send();
                        } else {
                            Notification::make()
                                ->warning()
                                ->title('No new students were added')
                                ->body('All selected students are already in this classroom')
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->can('update_student::classroom'))
                    ->form([
                        Forms\Components\TextInput::make('credit_left')
                            ->label('Credit Left')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('engagement_score')
                            ->label('Learning Engagement Score (0-10)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->helperText('Rate student engagement from 0 (lowest) to 10 (highest)')
                            ->nullable(),
                    ]),
                Tables\Actions\Action::make('edit_engagement')
                    ->label('Set Engagement')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->modalHeading('Set Learning Engagement Score')
                    ->modalDescription('Rate this student\'s engagement level from 0 (lowest) to 10 (highest)')
                    ->fillForm(fn ($record) => ['engagement_score' => $record->engagement_score])
                    ->form([
                        Forms\Components\TextInput::make('engagement_score')
                            ->label('Learning Engagement Score (0-10)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->required()
                            ->helperText('Rate student engagement from 0 (lowest) to 10 (highest)'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update(['engagement_score' => $data['engagement_score']]);

                        Notification::make()
                            ->success()
                            ->title('Engagement score updated')
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
