<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomSessionAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_session_id',
        'student_id',
        'is_present',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(ClassroomSession::class, 'classroom_session_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
