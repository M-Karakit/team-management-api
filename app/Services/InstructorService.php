<?php

namespace App\Services;

use App\Helpers\ApiResponseTrait;
use App\Models\Instructor;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class InstructorService
{
    use ApiResponseTrait;

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function listInstructors($perPage): LengthAwarePaginator
    {
        try {
            return Instructor::paginate($perPage);
        } catch (Exception $e){
            Log::error('Error List Instructors');
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    /**
     * @param Instructor $instructor
     * @return Instructor
     * @throws Exception
     */
    public function showInstructor(Instructor $instructor): Instructor
    {
        try {
            return $instructor;
        } catch (ModelNotFoundException $e){
            Log::error('Instructor not found: ' . $e->getMessage());
            throw new Exception('Instructor not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Instructor:' . $e->getMessage());
            throw new Exception('Error Retrieving Instructor.');
        }
    }

    /**
     * @param Instructor $instructor
     * @return Instructor
     * @throws Exception
     */
    public function showInstructorCourses(Instructor $instructor): Instructor
    {
        try {
            return $instructor->load('courses');
        } catch (ModelNotFoundException $e){
            Log::error('Instructor not found: ' . $e->getMessage());
            throw new Exception('Instructor not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Instructor:' . $e->getMessage());
            throw new Exception('Error Retrieving Instructor.');
        }
    }

    /**
     * @param Instructor $instructor
     * @return Instructor
     * @throws Exception
     */
    public function showInstructorStudents(Instructor $instructor): Instructor
    {
        try {
            return $instructor->load('students');
        } catch (ModelNotFoundException $e){
            Log::error('Instructor not found: ' . $e->getMessage());
            throw new Exception('Instructor not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Instructor:' . $e->getMessage());
            throw new Exception('Error Retrieving Instructor.');
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createInstructor(array $data): mixed
    {
        try {
            return Instructor::create($data);
        } catch (Exception $e){
            Log::error('Error Creating Instructor: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    public function updateInstructor(Instructor $instructor, array $data): Instructor
    {
        try {
            $instructor->update(array_filter($data));
            return $instructor;
        } catch (Exception $e){
            Log::error('Error Updating Instructor: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }
    public function deleteInstructor(Instructor $instructor): void
    {
        try {
            $instructor->delete();
        } catch (Exception $e){
            Log::error('Error Deleting Instructor: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    /**
     * @param int $id
     * @return array|Model|Collection|Builder|\Illuminate\Database\Query\Builder|null
     * @throws Exception
     */
    public function restoreInstructor(int $id): array|Model|Collection|Builder|\Illuminate\Database\Query\Builder|null
    {
        try {
            $instructor = Instructor::onlyTrashed()->findOrFail($id);
            $instructor->restore();
            return $instructor;
        } catch (ModelNotFoundException $e){
            Log::error('Instructor not found: ' . $e->getMessage());
            throw new Exception('Instructor not found.');
        } catch (Exception $e){
            Log::error('Error Restoring Instructor:' . $e->getMessage());
            throw new Exception('Error Restoring Instructor.');
        }
    }

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function trashedInstructors($perPage): LengthAwarePaginator
    {
        try {
            return Instructor::onlyTrashed()->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error Retrieving Trashed Instructors ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }


    /**
     * @param $id
     * @return void
     * @throws Exception
     */
    public function forceDeleteInstructor($id): void
    {
        try {
            $instructor = Instructor::withTrashed()->findOrFail($id);
            $instructor->forceDelete();
        } catch (ModelNotFoundException $e) {
            Log::error('Instructor not found: ' . $e->getMessage());
            throw new Exception('Instructor not found.');
        } catch (Exception $e) {
            Log::error('Error Force Deleting Instructor ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param Instructor $instructor
     * @param array $data
     * @return Instructor
     */
    public function assignInstructorToCourse(Instructor $instructor, array $data): Instructor
    {
        try {
            foreach ($data['courses'] as $course){
                $instructor->courses()->syncWithoutDetaching($course['id']);
            }

            $instructor->load('courses');
            return $instructor;
        } catch (Exception $e) {
            Log::error('Error assigning instructor to course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong on the server', 500));
        }
    }

    /**
     * @param Instructor $instructor
     * @param array $data
     * @return Instructor
     */
    public function unassignInstructorToCourse(Instructor $instructor, array $data): Instructor
    {
        try {
            foreach ($data['courses'] as $course){
                $instructor->courses()->detach($course['id']);
            }

            $instructor->load('courses');
            return $instructor;
        } catch (Exception $e) {
            Log::error('Error unassigning instructor to course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong on the server', 500));
        }
    }
}
