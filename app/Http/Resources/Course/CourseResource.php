<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Instructor\InstructorResource;
use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Log::info('Instructors loaded: ' . $this->relationLoaded('instructors'));  // Will log true/false
        Log::info('Students loaded: ' . $this->relationLoaded('students'));

        return [
            'Id' => $this->id,
            'Title' => $this->title,
            'Description' => $this->description,
            'startDate' => $this->start_date,
            'Instructors' => InstructorResource::collection($this->whenLoaded('instructors')),
            'Students' => StudentResource::collection($this->whenLoaded('students')),
        ];
    }
}
