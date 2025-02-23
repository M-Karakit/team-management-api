<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseInstructor extends Pivot
{
    protected $table = 'course_instructor';

    protected $fillable = [
        'course_id',
        'instructor_id',
    ];
}
