<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $module_id
 * @property integer $user_id
 * @property integer $user_module_score_id
 * @property integer $user_module_status_id
 * @property string $created_at
 * @property string $updated_at
 * @property Module $module
 * @property User $user
 * @property UserModuleScore $userModuleScore
 * @property UserModuleStatus $userModuleStatus
 */
class UserModule extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['module_id', 'user_id', 'user_module_score_id', 'user_module_status_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Module');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userModuleScore(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\UserModuleScore');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userModuleStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\UserModuleStatus');
    }
}
