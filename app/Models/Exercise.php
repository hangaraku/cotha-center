<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
/**
 * @property integer $id
 * @property integer $unit_id
 * @property string $name
 * @property integer $order_number
 * @property string $created_at
 * @property string $updated_at
 * @property ExerciseQuestion[] $exerciseQuestions
 * @property Unit $unit
 */
class Exercise extends Model implements Sortable
{
    use SortableTrait;
    /**
     * @var array
     */
    protected $fillable = ['unit_id', 'name', 'order_number', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exerciseQuestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ExerciseQuestion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
