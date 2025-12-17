<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $reward_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property Reward $reward
 * @property User $user
 */
class UserReward extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'reward_id', 'status', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reward(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Reward');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
