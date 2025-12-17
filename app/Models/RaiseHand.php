<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $classroom_id
 * @property integer $unit_id
 * @property boolean $is_raised
 * @property string $raised_at
 * @property string $lowered_at
 * @property string $message
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Classroom $classroom
 * @property Unit $unit
 */
class RaiseHand extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'classroom_id',
        'unit_id',
        'is_raised',
        'raised_at',
        'lowered_at',
        'message'
    ];

    protected $casts = [
        'is_raised' => 'boolean',
        'raised_at' => 'datetime',
        'lowered_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
