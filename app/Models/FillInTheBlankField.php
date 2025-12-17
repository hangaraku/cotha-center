<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $fill_in_the_blank_question_id
 * @property integer $order_number
 * @property string $answer
 * @property integer $score
 * @property string $created_at
 * @property string $updated_at
 * @property FillInTheBlankQuestion $fillInTheBlankQuestion
 */
class FillInTheBlankField extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['fill_in_the_blank_question_id', 'order_number', 'answer', 'score', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fillInTheBlankQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\FillInTheBlankQuestion');
    }
}
