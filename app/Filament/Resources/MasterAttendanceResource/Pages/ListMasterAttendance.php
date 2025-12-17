<?php

namespace App\Filament\Resources\MasterAttendanceResource\Pages;

use App\Filament\Resources\MasterAttendanceResource;
use App\Models\Classroom;
use App\Models\Center;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ListMasterAttendance extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MasterAttendanceResource::class;

    protected static string $view = 'filament.resources.master-attendance-resource.pages.list-master-attendance';

    public $centerId;
    public $classroomIds = [];
    public $classrooms;
    public $sessionsData = [];
    public $maxSessions = 0;
    public $selectedMonth = null;
    public $selectedYear = null;

    public function mount(): void
    {
        // Set default center based on user role
        if (Auth::check()) {
            if (Auth::user()->hasRole('Teacher')) {
                // For teachers, get center from their first classroom
                $classroom = Classroom::whereHas('teachers', function ($q) {
                    $q->where('user_id', Auth::id());
                })->first();
                $this->centerId = $classroom?->center_id;
            } elseif (!Auth::user()->hasRole('super_admin')) {
                // For non-super admin, use their center
                $this->centerId = Auth::user()->center_id;
            } else {
                // For super admin, use their center as default
                $this->centerId = Auth::user()->center_id;
            }
        }
        
        $this->classroomIds = [];
        $this->loadData();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pilih Kelas dan Filter')
                    ->schema([
                        Select::make('classroomIds')
                            ->label('Pilih Kelas')
                            ->multiple()
                            ->options(function () {
                                if (!$this->centerId) {
                                    return [];
                                }
                                
                                $query = Classroom::active()
                                    ->where('center_id', $this->centerId);
                                
                                // Filter by teacher if user is a teacher
                                if (Auth::user()->hasRole('Teacher')) {
                                    $query->whereHas('teachers', function ($q) {
                                        $q->where('user_id', Auth::id());
                                    });
                                }
                                
                                return $query->orderBy('name')->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->classroomIds = $state ?? [];
                                $this->loadData();
                            })
                            ->helperText('Pilih satu atau lebih kelas untuk melihat master absensi')
                            ->placeholder('Klik untuk memilih kelas')
                            ->columnSpanFull(),
                        
                        Select::make('selectedMonth')
                            ->label('Bulan')
                            ->options([
                                '' => 'Semua Bulan',
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ])
                            ->placeholder('Pilih Bulan')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedMonth = $state ?: null;
                                $this->loadData();
                            }),
                        
                        Select::make('selectedYear')
                            ->label('Tahun')
                            ->options(function () {
                                $currentYear = (int) date('Y');
                                $years = ['' => 'Semua Tahun'];
                                for ($i = $currentYear - 2; $i <= $currentYear + 1; $i++) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->placeholder('Pilih Tahun')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedYear = $state ?: null;
                                $this->loadData();
                            }),
                    ])
                    ->columns(3),
            ]);
    }

    public function getTitle(): string
    {
        return 'Master Absensi';
    }

    // Livewire method called when classroomIds is updated
    public function updatedClassroomIds($value)
    {
        // Ensure classroomIds is always an array
        $this->classroomIds = is_array($value) ? $value : ($value ? [$value] : []);
        $this->loadData();
    }

    public function loadData()
    {
        // Ensure classroomIds is an array
        if (!is_array($this->classroomIds)) {
            $this->classroomIds = $this->classroomIds ? [$this->classroomIds] : [];
        }

        if (empty($this->classroomIds)) {
            $this->classrooms = collect();
            $this->sessionsData = [];
            $this->maxSessions = 0;
            return;
        }

        // Load selected classrooms with security filtering
        $query = Classroom::with(['center', 'classroomType', 'sessions' => function($query) {
            $query->where('type', 'official')
                  ->orderBy('session_date')
                  ->orderBy('start_time');
        }])
        ->whereIn('id', $this->classroomIds);
        
        // Security: Ensure teachers can only see their own classes
        if (Auth::user()->hasRole('Teacher')) {
            $query->whereHas('teachers', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }
        
        $this->classrooms = $query->get();

        // Set center ID from first classroom if not set
        if ($this->classrooms->isNotEmpty() && !$this->centerId) {
            $this->centerId = $this->classrooms->first()->center_id;
        }

        // Prepare sessions data
        $this->sessionsData = [];
        $this->maxSessions = 0;

        foreach ($this->classrooms as $classroom) {
            // Get all sessions to calculate overall session numbers
            $allSessions = $classroom->sessions->groupBy(function($session) {
                return $session->session_date->format('Y-m-d');
            })->sortKeys();

            // Create a mapping of date => overall session number
            $overallSessionNumbers = [];
            $overallNumber = 1;
            foreach ($allSessions as $date => $dateSessions) {
                $overallSessionNumbers[$date] = $overallNumber;
                $overallNumber++;
            }

            // Filter sessions by month/year if selected
            $filteredSessions = $allSessions;
            if ($this->selectedMonth || $this->selectedYear) {
                $filteredSessions = $allSessions->filter(function($dateSessions, $date) {
                    $sessionDate = \Carbon\Carbon::parse($date);
                    
                    $monthMatch = !$this->selectedMonth || $sessionDate->format('m') === $this->selectedMonth;
                    $yearMatch = !$this->selectedYear || $sessionDate->format('Y') === (string) $this->selectedYear;
                    
                    return $monthMatch && $yearMatch;
                });
            }

            $classroomSessions = [];
            $displayNumber = 1; // Display number always starts from 1 for filtered view

            foreach ($filteredSessions as $date => $dateSessions) {
                $classroomSessions[$displayNumber] = [
                    'date' => $date,
                    'formatted_date' => \Carbon\Carbon::parse($date)->format('d/m/Y'),
                    'sessions' => $dateSessions,
                    'overall_session_number' => $overallSessionNumbers[$date] // Overall session number
                ];
                $displayNumber++;
            }

            $this->sessionsData[$classroom->id] = $classroomSessions;
            $this->maxSessions = max($this->maxSessions, count($classroomSessions));
        }
    }

    public function getSessionHeaders()
    {
        $headers = [];
        $isFiltered = $this->selectedMonth || $this->selectedYear;
        
        for ($i = 1; $i <= $this->maxSessions; $i++) {
            // If filtered, show just numbers. Otherwise show "Sesi N"
            $headers[] = $isFiltered ? (string) $i : "Sesi {$i}";
        }
        return $headers;
    }
    
    public function isFiltered()
    {
        return $this->selectedMonth || $this->selectedYear;
    }

    public function getClassroomSessionDate($classroomId, $sessionNumber)
    {
        return $this->sessionsData[$classroomId][$sessionNumber]['formatted_date'] ?? '-';
    }

    public function getClassroomSessionDetails($classroomId, $sessionNumber)
    {
        if (!isset($this->sessionsData[$classroomId][$sessionNumber])) {
            return null;
        }

        $sessionData = $this->sessionsData[$classroomId][$sessionNumber];
        return [
            'date' => $sessionData['formatted_date'],
            'sessions' => $sessionData['sessions'],
            'overall_session_number' => $sessionData['overall_session_number'] ?? null
        ];
    }
}
