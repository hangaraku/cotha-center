<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Admin[] $admins
 * @property ClassroomTeacher[] $classroomTeachers
 * @property Student[] $students
 * @property UserClassroomScheduleSessionReplacement[] $userClassroomScheduleSessionReplacements
 * @property UserClassroomScheduleSession[] $userClassroomScheduleSessions
 * @property UserModule[] $userModules
 * @property UserReward[] $userRewards
 * @property UserUnit[] $userUnits
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
        /**
     * @var array
     */
    protected $fillable = ['name','center_id','gender', 'email', 'email_verified_at', 'password', 'remember_token', 'profile_picture', 'point', 'created_at', 'updated_at'];


    public function admins()
    {
        return $this->belongsToMany('App\Models\AdminRole','admins','user_id','role_id');
    }

    public function center(){
        return $this->belongsTo(Center::class);
    }
    public function studentClassrooms()
    {
        return $this->hasMany('App\Models\StudentClassroom');
    }
    public function exercises(){
        return $this->hasMany(UserExercise::class);
    }

    public function exerciseQuestion(){
        return $this->hasManyThrough(UserExerciseQuestion::class,UserExercise::class);
    }
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'student_classrooms');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classroomTeachers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ClassroomTeacher');
    }

    public function students()
    {
        return $this->hasOne('App\Models\Student');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userClassroomScheduleSessionReplacements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserClassroomScheduleSessionReplacement');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userClassroomScheduleSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserClassroomScheduleSession');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userModules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserModule');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRewards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserReward');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userUnits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserUnit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classroomSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ClassroomSession', 'teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function raiseHands(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\RaiseHand');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userProjects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserProject');
    }

    /**
     * Generate a URL-friendly slug from the user's name and ID
     */
    public function getSlugAttribute(): string
    {
        $slug = \Illuminate\Support\Str::slug($this->name);
        return $slug . '-' . $this->id;
    }
}
