<?php

namespace App\Services;

use App\Helpers\ApiResponseTrait;
use App\Models\Course;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CourseService
{
    use ApiResponseTrait;

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function listCourses($perPage): LengthAwarePaginator
    {
        try {
            return Course::paginate($perPage);
        } catch (\Exception $e){
            Log::error('Error list Courses: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500 ));
        }
    }

    /**
     * @param Course $course
     * @return Course
     * @throws Exception
     */
    public function showCourseInstructors(Course $course): Course
    {
        try {
            return $course->load('instructors');
        } catch (ModelNotFoundException $e){
            Log::error('Course not found: ' . $e->getMessage());
            throw new Exception('Course not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Course:' . $e->getMessage());
            throw new Exception('Error Retrieving Course.');
        }
    }

    /**
     * @param Course $course
     * @return Course
     * @throws Exception
     */
    public function showCourseStudents(Course $course): Course
    {
        try {
            return $course->load('students');
        } catch (ModelNotFoundException $e){
            Log::error('Course not found: ' . $e->getMessage());
            throw new Exception('Course not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Course:' . $e->getMessage());
            throw new Exception('Error Retrieving Course.');
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createCourse(array $data): mixed
    {
        try {
            return Course::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'start_date' => $data['start_date'],
            ]);
        } catch (Exception $e){
            Log::error('Error creating Course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param Course $course
     * @return Course
     * @throws Exception
     */
    public function showCourse(Course $course): Course
    {
        try {
            return $course;
        } catch (ModelNotFoundException $e) {
            Log::error('Course not found: ' . $e->getMessage());
            throw new Exception('Course not found.');
        } catch (Exception $e) {
            Log::error('Error retrieving Course: ' . $e->getMessage());
            throw new Exception('Error retrieving Course.');
        }
    }

    /**
     * @param Course $course
     * @param array $data
     * @return Course
     * @throws HttpResponseException
     */
    public function updateCourse(Course $course, array $data): Course
    {
        try {
            $course->update(array_filter($data));
            return $course;
        } catch (Exception $e){
            Log::error('Error updating Course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param Course $course
     * @return void
     */
    public function deleteCourse(Course $course): void
    {
        try {
            $course->delete();
        } catch (Exception $e) {
            Log::error('Error deleting Course ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }


    /**
     * @param $courseId
     * @return Builder|Builder[]|Collection|Model|\Illuminate\Database\Query\Builder|\Illuminate\Database\Query\Builder[]|null
     * @throws Exception
     */
    public function restoreCourse($courseId): array|Model|Collection|Builder|\Illuminate\Database\Query\Builder|null
    {
        try {
            $course = Course::onlyTrashed()->findOrFail($courseId);
            $course->restore();
            return $course;
        } catch (ModelNotFoundException $e) {
            Log::error('Course not found: ' . $e->getMessage());
            throw new Exception('Course not found.');
        } catch (Exception $e) {
            Log::error('Error restoring Course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function trashedCourses($perPage): LengthAwarePaginator
    {
        try {
            return Course::onlyTrashed()->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error Retrieving Trashed Course ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param $courseId
     * @return void
     * @throws Exception
     */
    public function forceDeleteCourse($courseId): void
    {
        try {
            $course = Course::withTrashed()->findOrFail($courseId);
            $course->forceDelete();
        }catch (ModelNotFoundException $e) {
            Log::error('Course not found: ' . $e->getMessage());
            throw new Exception('Course not found.');
        } catch (Exception $e) {
            Log::error('Error force deleting Course ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param Course $course
     * @param array $data
     * @return Course
     */
    public function assignCourseToInstructor(Course $course, array $data): Course
    {
        try {
            foreach ($data['instructors'] as $instructor){
                $course->instructors()->syncWithoutDetaching($instructor['id']);
            }

            $course->load('instructors');
            return $course;
        } catch (Exception $e) {
            Log::error('Error assigning course to instructor: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong on the server', 500));
        }
    }

    /**
     * @param Course $course
     * @param array $data
     * @return Course
     */
    public function unassignCourseToInstructor(Course $course, array $data): Course
    {
        try {
            foreach ($data['instructors'] as $instructor){
                $course->instructors()->detach($instructor['id']);
            }

            $course->load('instructors');
            return $course;
        } catch (Exception $e) {
            Log::error('Error unassigning course to instructor: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong on the server', 500));
        }
    }
}
