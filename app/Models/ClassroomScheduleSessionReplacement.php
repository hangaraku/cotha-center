<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $classroom_teacher_id
 * @property integer $classroom_schedule_session_id
 * @property string $start_time
 * @property string $end_time
 * @property string $venue
 * @property string $created_at
 * @property string $updated_at
 * @property ClassroomScheduleSession $classroomScheduleSession
 * @property ClassroomTeacher $classroomTeacher
 */
class ClassroomScheduleSessionReplacement extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['classroom_teacher_id', 'classroom_schedule_session_id', 'start_time', 'end_time', 'venue', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroomScheduleSession(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ClassroomScheduleSession');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroomTeacher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ClassroomTeacher');
    }
}
