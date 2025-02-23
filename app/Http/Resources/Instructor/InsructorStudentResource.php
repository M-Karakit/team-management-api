<?php

namespace App\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsructorStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Students' => $this->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'course_id' => $student->course_id,
                    'student_id' => $student->student_id,
                    'laravel_throw_key' => $student->student_id,
                ];
            }),
        ];
    }
}
