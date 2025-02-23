<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'experience',
        'specialty',
    ];
    protected array $dates = ['deleted_at'];


    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_instructor', 'instructor_id', 'course_id');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            CourseStudent::class,
            CourseInstructor::class,
            'instructor_id',
            'course_id',
            'id',
            'course_id',
        );
    }
}
