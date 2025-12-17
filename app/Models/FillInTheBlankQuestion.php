<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $exercise_question_id
 * @property string $question_text
 * @property string $question_img
 * @property string $created_at
 * @property string $updated_at
 * @property FillInTheBlankField[] $fillInTheBlankFields
 * @property ExerciseQuestion $exerciseQuestion
 */
class FillInTheBlankQuestion extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['exercise_question_id', 'question_text', 'question_img', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fillInTheBlankFields(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\FillInTheBlankField');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exerciseQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ExerciseQuestion');
    }
}
