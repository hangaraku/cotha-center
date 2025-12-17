<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property integer $id
 * @property integer $level_id
 * @property string $name
 * @property string $description
 * @property string $img_url
 * @property integer $order_number
 * @property string $created_at
 * @property string $updated_at
 * @property Level $level
 * @property Unit[] $units
 * @property UserModule[] $userModules
 */
class Module extends Model implements Sortable
{
    use SortableTrait;
    /**
     * @var array
     */
    protected $fillable = ['level_id', 'name', 'description', 'img_url', 'order_number', 'created_at', 'updated_at'];
    public function buildSortQuery()
    {
        return static::query()->where('level_id', $this->level_id);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Level');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function units(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Unit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userModules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserModule');
    }
}
