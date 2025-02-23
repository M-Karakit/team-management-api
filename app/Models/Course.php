<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
    ];

    protected $dates = ['start_date', 'deleted_at'];


    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor', 'course_id', 'instructor_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
    }

    public function getStartDateAttribute($value): string
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function setStartDateAttribute($value): Carbon
    {
        return $this->attributes['start_date'] = Carbon::parse($value);
    }
}
