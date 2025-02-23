<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Instructor\InstructorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Name' => $this->name,
            'Email' => $this->email,
            'Courses' => CourseResource::collection($this->whenLoaded('courses')),
            'Instructors' => InstructorResource::collection($this->whenLoaded('instructors')),
        ];
    }
}
