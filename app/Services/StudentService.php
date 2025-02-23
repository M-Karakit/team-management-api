<?php

namespace App\Services;

use App\Helpers\ApiResponseTrait;
use App\Models\Student;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class StudentService
{
    use ApiResponseTrait;

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function listStudents($perPage): LengthAwarePaginator
    {
        try {
            return Student::paginate($perPage);
        } catch (Exception $e){
            Log::error('Error List Students: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    /**
     * @param Student $student
     * @return Student
     * @throws Exception
     */
    public function showStudentCourses(Student $student): Student
    {
        try {
            return $student->load('courses');
        } catch (ModelNotFoundException $e){
            Log::error('Student not found: ' . $e->getMessage());
            throw new Exception('Student not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Student:' . $e->getMessage());
            throw new Exception('Error Retrieving Student.');
        }
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createStudent(array $data): mixed
    {
        try {
            return Student::create($data);
        } catch (Exception $e){
            Log::error('Error Creating Student: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    /**
     * @param Student $student
     * @return Student
     * @throws Exception
     */
    public function showStudent(Student $student): Student
    {
        try {
            return $student;
        } catch (ModelNotFoundException $e){
            Log::error('Student not found: ' . $e->getMessage());
            throw new Exception('Student not found.');
        } catch (Exception $e){
            Log::error('Error Retrieving Student:' . $e->getMessage());
            throw new Exception('Error Retrieving Student.');
        }
    }

    /**
     * @param Student $student
     * @param array $data
     * @return Student
     */
    public function updateStudent(Student $student, array $data): Student
    {
        try {
            $student->update(array_filter($data));
            return $student;
        } catch (Exception $e){
            Log::error('Error Updating Student: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    /**
     * @param Student $student
     * @return void
     */
    public function deleteStudent(Student $student): void
    {
        try {
            $student->delete();
        } catch (Exception $e){
            Log::error('Error Deleting Student: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with server', 500));
        }
    }

    /**
     * @param $id
     * @return Model|array|Collection|Builder|\Illuminate\Database\Query\Builder|null
     * @throws Exception
     */
    public function restoreStudent($id): Model|array|Collection|Builder|\Illuminate\Database\Query\Builder|null
    {
        try {
            $student = Student::onlyTrashed()->findOrFail($id);
            $student->restore();
            return $student;
        } catch (ModelNotFoundException $e){
            Log::error('Student not found: ' . $e->getMessage());
            throw new Exception('Student not found.');
        } catch (Exception $e){
            Log::error('Error Restoring Student:' . $e->getMessage());
            throw new Exception('Error Restoring Student.');
        }
    }

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function trashedStudents($perPage): LengthAwarePaginator
    {
        try {
            return Student::onlyTrashed()->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error Retrieving Trashed Students ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|\Illuminate\Database\Query\Builder|\Illuminate\Database\Query\Builder[]|null
     * @throws Exception
     */
    public function forceDeleteStudent($id): array|Model|Collection|Builder|\Illuminate\Database\Query\Builder|null
    {
        try {
            $student = Student::withTrashed()->findOrFail($id);
            $student->forceDelete();
            return $student;
        } catch (ModelNotFoundException $e) {
            Log::error('Students not found: ' . $e->getMessage());
            throw new Exception('Students not found.');
        } catch (Exception $e) {
            Log::error('Error Force Deleting Students ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'there is something wrong in server', 500));
        }
    }

    /**
     * @param Student $student
     * @param array $data
     * @return Student
     */
    public function assignStudentToCourse(Student $student ,array $data): Student
    {
        try {
            foreach ($data['courses'] as $course){
                $student->courses()->syncWithoutDetaching($course['id']);
            }

            $student->load('courses');
            return $student;
        } catch (Exception $e) {
            Log::error('Error Assigning Student to Course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong on the server', 500));
        }
    }

    /**
     * @param Student $student
     * @param array $data
     * @return Student
     */
    public function unassignStudentToCourse(Student $student, array $data): Student
    {
        try {
            foreach ($data['courses'] as $course){
                $student->courses()->detach($course['id']);
            }

            $student->load('courses');
            return $student;
        } catch (Exception $e) {
            Log::error('Error Unassigning Student to Course: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong on the server', 500));
        }
    }
}
