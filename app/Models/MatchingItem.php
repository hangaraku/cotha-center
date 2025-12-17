<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $matching_question_id
 * @property integer $matching_item_answer_id
 * @property string $text
 * @property string $img
 * @property string $created_at
 * @property string $updated_at
 * @property MatchingItem $matchingItem
 * @property MatchingQuestion $matchingQuestion
 */
class MatchingItem extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['matching_question_id', 'matching_item_answer_id', 'text', 'img', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function matchingItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\MatchingItem', 'matching_item_answer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function matchingQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\MatchingQuestion');
    }
}
