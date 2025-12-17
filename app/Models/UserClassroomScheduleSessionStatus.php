<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property UserClassroomScheduleSessionReplacement[] $userClassroomScheduleSessionReplacements
 * @property UserClassroomScheduleSession[] $userClassroomScheduleSessions
 */
class UserClassroomScheduleSessionStatus extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'created_at', 'updated_at'];

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
}
