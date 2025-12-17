<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property integer $id
 * @property integer $multiple_choice_question_id
 * @property string $img
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 * @property MultipleChoiceQuestion $multipleChoiceQuestion
 */
class MultipleChoiceAnswer extends Model implements Sortable
{
    use SortableTrait;
    /**
     * @var array
     */
    protected $fillable = ['multiple_choice_question_id','is_correct_option', 'img', 'text', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ExerciseQuestion::class);
    }
}
