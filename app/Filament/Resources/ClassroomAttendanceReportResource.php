<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomAttendanceReportResource\Pages;
use App\Models\Classroom;
use App\Models\ClassroomSession;
use App\Models\ClassroomSessionAttendance;
use App\Models\StudentClassroom;
use App\Models\Center;
use App\Models\ClassroomTeacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ClassroomAttendanceReportResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Laporan Absensi Kelas';

    protected static ?string $modelLabel = 'Laporan Absensi Kelas';

    protected static ?string $pluralModelLabel = 'Laporan Absensi Kelas';

    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pilih Kelas')
                    ->schema([
                        Select::make('classroom_id')
                            ->label('Kelas')
                            ->options(function () {
                                $query = Classroom::active();
                                
                                // Only apply filters if user is authenticated
                                if (Auth::check()) {
                                    // Filter based on user role and center
                                    
                                    if (Auth::user()->hasRole('Teacher')) {
                                        // Teacher can only see their own classrooms
                                        $query->whereHas('teachers', function ($q) {
                                            $q->where('user_id', Auth::id());
                                        });
                                    } elseif (!Auth::user()->hasRole('super_admin')) {
                                        // Non-super admin users can only see classrooms from their center
                                        $query->where('center_id', Auth::user()->center_id);
                                    }
                                }
                                
                                return $query->pluck('name', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->placeholder('Pilih kelas untuk melihat laporan absensi'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('center.name')
                    ->label('Center')
                    ->sortable()
                    ->visible(function () {
                        // Only show center column for super admin
                        return Auth::check() && Auth::user()->hasRole('super_admin');
                    }),
                TextColumn::make('classroomType.name')
                    ->label('Tipe Kelas')
                    ->sortable(),
                TextColumn::make('students_count')
                    ->label('Jumlah Siswa')
                    ->counts('students')
                    ->sortable(),
                TextColumn::make('sessions_count')
                    ->label('Jumlah Sesi')
                    ->counts('sessions')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('center')
                    ->label('Center')
                    ->options(function () {
                        // Only apply filters if user is authenticated
                        if (Auth::check()) {
                            // Super admin can see all centers
                            if (Auth::user()->hasRole('super_admin')) {
                                return Center::pluck('name', 'id');
                            }
                            // Other users can only see their own center
                            return Center::where('id', Auth::user()->center_id)->pluck('name', 'id');
                        }
                        return Center::pluck('name', 'id');
                    })
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereIn('center_id', $data['values']);
                        }
                        return $query;
                    })
                    ->visible(function () {
                        // Only show center filter for super admin
                        return Auth::check() && Auth::user()->hasRole('super_admin');
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_attendance')
                    ->label('Lihat Absensi')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Classroom $record): string => "/admin/classroom-attendance-reports/{$record->id}/attendance-report")
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListClassroomAttendanceReports::route('/'),
            'attendance-report' => Pages\ClassroomAttendanceReport::route('/{record}/attendance-report'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->active();
        
        // Only apply filters if user is authenticated
        if (Auth::check()) {
            // Filter based on user role and center
          if (!Auth::user()->hasRole('super_admin')) {
                // Non-super admin users can only see classrooms from their center
                return $query->where('center_id', Auth::user()->center_id);
            }
        }
        
        return $query->where('center_id', Auth::user()->center_id);
    }
}
