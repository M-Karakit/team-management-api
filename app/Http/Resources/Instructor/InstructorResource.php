<?php

namespace App\Http\Resources\Instructor;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
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
            'Experience' => $this->experience,
            'Specialty' => $this->specialty,
            'Courses' => CourseResource::collection($this->whenLoaded('courses')),
        ];
    }
}
