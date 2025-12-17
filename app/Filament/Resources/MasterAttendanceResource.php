<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterAttendanceResource\Pages;
use App\Models\Classroom;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MasterAttendanceResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Master Absensi';

    protected static ?string $modelLabel = 'Master Absensi';

    protected static ?string $pluralModelLabel = 'Master Absensi';

    protected static ?string $navigationGroup = 'Laporan';
    
    protected static ?string $slug = 'master-attendances';

    // Allow teachers, admins, and super admins to view this resource
    public static function canViewAny(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasRole(['super_admin', 'Teacher', 'panel_user']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterAttendance::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->active();
        
        if (Auth::check()) {
            if (Auth::user()->hasRole('Teacher')) {
                $query->whereHas('teachers', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } elseif (!Auth::user()->hasRole('super_admin')) {
                $query->where('center_id', Auth::user()->center_id);
            }
        }
        
        return $query;
    }
}
