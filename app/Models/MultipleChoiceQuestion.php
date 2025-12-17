<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $exercise_question_id
 * @property string $text
 * @property string $correct_option
 * @property integer $score
 * @property string $created_at
 * @property string $updated_at
 * @property MultipleChoiceAnswer[] $multipleChoiceAnswers
 * @property ExerciseQuestion $exerciseQuestion
 */
class MultipleChoiceQuestion extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['exercise_question_id', 'text', 'correct_option', 'score', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function multipleChoiceAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\MultipleChoiceAnswer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exerciseQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ExerciseQuestion');
    }
}
