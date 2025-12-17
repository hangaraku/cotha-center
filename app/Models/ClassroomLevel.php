<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $classroom_id
 * @property int $level_id
 * @property bool $is_active
 * @property Classroom $classroom
 * @property Level $level
 */
class ClassroomLevel extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['classroom_id', 'level_id', 'is_active', 'is_main_level'];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_main_level' => 'boolean',
    ];

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Classroom');
    }

    public function level(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Level');
    }
}
