<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserExercise
 * 
 * @property int $id
 * @property int $user_id
 * @property int $exercise_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Exercise $exercise
 * @property User $user
 * @property Collection|UserExerciseQuestion[] $user_exercise_questions
 *
 * @package App\Models
 */
class UserExercise extends Model
{
	protected $table = 'user_exercises';

	protected $casts = [
		'user_id' => 'int',
		'exercise_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'exercise_id'
	];

	public function exercise()
	{
		return $this->belongsTo(Exercise::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function user_exercise_questions()
	{
		return $this->hasMany(UserExerciseQuestion::class);
	}
}
