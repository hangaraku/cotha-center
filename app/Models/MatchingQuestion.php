<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $exercise_question_id
 * @property string $text
 * @property string $img
 * @property string $created_at
 * @property string $updated_at
 * @property MatchingItem[] $matchingItems
 * @property ExerciseQuestion $exerciseQuestion
 */
class MatchingQuestion extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['exercise_question_id', 'text', 'img', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchingItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\MatchingItem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exerciseQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ExerciseQuestion');
    }
}
