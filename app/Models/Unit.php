<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property integer $id
 * @property integer $module_id
 * @property string $name
 * @property string $description
 * @property string $img_url
 * @property integer $point
 * @property integer $order_number
 * @property string $created_at
 * @property string $updated_at
 * @property Exercise[] $exercises
 * @property Topic[] $topics
 * @property Module $module
 * @property UserUnit[] $userUnits
 * @property Vocabulary[] $vocabularies
 */
class Unit extends Model implements Sortable
{
    use SortableTrait;
    /**
     * @var array
     */
    protected $fillable = ['module_id', 'name', 'description', 'img_url', 'point', 'order_number', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Exercise');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Topic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Module');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userUnits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserUnit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vocabularies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Vocabulary');
    }
}
