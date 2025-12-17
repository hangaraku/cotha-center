<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $classroom_schedule_id
 * @property integer $user_classroom_schedule_session_status_id
 * @property string $note
 * @property integer $credit_used
 * @property string $check_in_time
 * @property string $created_at
 * @property string $updated_at
 * @property ClassroomSchedule $classroomSchedule
 * @property UserClassroomScheduleSessionStatus $userClassroomScheduleSessionStatus
 * @property User $user
 */
class UserClassroomScheduleSessionReplacement extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'classroom_schedule_id', 'user_classroom_schedule_session_status_id', 'note', 'credit_used', 'check_in_time', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroomSchedule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ClassroomSchedule');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userClassroomScheduleSessionStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\UserClassroomScheduleSessionStatus');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
