<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property int $classroom_type_id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property int $total_credit
 * @property string $created_at
 * @property string $updated_at
 * @property ClassroomLevel[] $classroomLevels
 * @property ClassroomSchedule[] $classroomSchedules
 * @property ClassroomTeacher[] $classroomTeachers
 * @property ClassroomType $classroomType
 */
class Classroom extends Model implements Sortable
{
    use SortableTrait;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    /**
     * @var array
     */
    protected $fillable = ['classroom_type_id', 'center_id', 'name', 'start_date', 'end_date', 'total_credit', 'is_active', 'created_at', 'updated_at'];

    /**
     * Scope to filter active classrooms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter inactive/hidden classrooms
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function classroomLevels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ClassroomLevel');
    }

    public function projects()
    {
        return $this->hasManyDeep(
            \App\Models\Module::class,
            [
                'classroom_levels',
                \App\Models\Level::class,
            ],
            [
                'classroom_id', // classroom_levels.classroom_id
                'id',           // levels.id
            ],
            [
                'id',           // classrooms.id
                'level_id',     // modules.level_id
            ]
        )
            ->where('classroom_levels.is_active', true);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'classroom_levels', 'classroom_id', 'level_id');
    }

    public function classroomSchedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ClassroomSchedule');
    }

    public function classroomTeachers()
    {
        return $this->belongsToMany(User::class, 'classroom_teachers');
    }

    public function teachers()
    {
        return $this->hasMany(ClassroomTeacher::class);
    }

    public function students()
    {
        return $this->hasMany(StudentClassroom::class);
    }

    public function studentClassrooms()
    {
        return $this->hasMany(StudentClassroom::class);
    }

    /**
     * Get the main level for this classroom.
     * Falls back to the first level if no main level is set.
     */
    public function mainLevel()
    {
        return $this->hasOneThrough(
            Level::class,
            ClassroomLevel::class,
            'classroom_id', // Foreign key on classroom_levels table
            'id',           // Foreign key on levels table
            'id',           // Local key on classrooms table
            'level_id'      // Local key on classroom_levels table
        )->where('classroom_levels.is_main_level', true);
    }

    /**
     * Get the main level name, with fallback to first level.
     */
    public function getMainLevelNameAttribute(): ?string
    {
        // First try to get the explicitly set main level
        $mainLevel = $this->classroomLevels()
            ->where('is_main_level', true)
            ->with('level')
            ->first();

        if ($mainLevel && $mainLevel->level) {
            return $mainLevel->level->name;
        }

        // Fall back to first level
        $firstLevel = $this->classroomLevels()->with('level')->first();

        return $firstLevel?->level?->name;
    }

    /**
     * Get the main level ID, with fallback to first level.
     */
    public function getMainLevelIdAttribute(): ?int
    {
        // First try to get the explicitly set main level
        $mainLevel = $this->classroomLevels()
            ->where('is_main_level', true)
            ->first();

        if ($mainLevel) {
            return $mainLevel->level_id;
        }

        // Fall back to first level
        $firstLevel = $this->classroomLevels()->first();

        return $firstLevel?->level_id;
    }

    public function sessions()
    {
        return $this->hasMany(ClassroomSession::class);
    }

    public function classroomType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ClassroomType');
    }
}
