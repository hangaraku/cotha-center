<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $exercise_question_id
 * @property string $text
 * @property string $img
 * @property boolean $is_correct
 * @property integer $score
 * @property string $created_at
 * @property string $updated_at
 * @property ExerciseQuestion $exerciseQuestion
 */
class TrueFalseQuestion extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['exercise_question_id', 'text', 'img', 'is_correct', 'score', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exerciseQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ExerciseQuestion');
    }
}
