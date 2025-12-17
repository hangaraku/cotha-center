<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $classroom_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 * @property ClassroomScheduleSession[] $classroomScheduleSessions
 * @property Classroom $classroom
 * @property UserClassroomScheduleSessionReplacement[] $userClassroomScheduleSessionReplacements
 * @property UserClassroomScheduleSession[] $userClassroomScheduleSessions
 */
class ClassroomSchedule extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['classroom_id', 'day', 'start_time', 'end_time', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classroomScheduleSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ClassroomScheduleSession');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Classroom');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userClassroomScheduleSessionReplacements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserClassroomScheduleSessionReplacement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userClassroomScheduleSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserClassroomScheduleSession');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ClassroomSession');
    }
}
