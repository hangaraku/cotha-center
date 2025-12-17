<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserExerciseQuestion
 * 
 * @property int $id
 * @property int $user_exercise_id
 * @property int $exercise_question_id
 * @property int|null $answer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ExerciseQuestion $exercise_question
 * @property UserExercise $user_exercise
 *
 * @package App\Models
 */
class UserExerciseQuestion extends Model
{
	protected $table = 'user_exercise_questions';

	protected $casts = [
		'user_exercise_id' => 'int',
		'exercise_question_id' => 'int',
		'answer_id' => 'int'
	];

	protected $fillable = [
		'user_exercise_id',
		'exercise_question_id',
		'answer_id'
	];

	public function exercise_question()
	{
		return $this->belongsTo(ExerciseQuestion::class);
	}



	public function user_exercise()
	{
		return $this->belongsTo(UserExercise::class);
	}

	public function multipleChoiceAnswer()
	{
		return $this->belongsTo(MultipleChoiceAnswer::class,"answer_id");
	}
}
