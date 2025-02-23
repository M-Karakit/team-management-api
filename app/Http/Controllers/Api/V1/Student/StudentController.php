<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Helpers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\AssignStudentToCourse;
use App\Http\Requests\Student\StudentRequest;
use App\Http\Requests\Student\UnassignStudentToCourse;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Resources\Student\StudentResource;
use App\Models\Student;
use App\Services\StudentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use ApiResponseTrait;

    protected StudentService $studentService;

    /**
     * @param StudentService $studentService
     */
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
        $this->middleware('check_auth');
        $this->middleware('is_admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $students = $this->studentService->listStudents($perPage);
        return $this->resourcePaginated(StudentResource::collection($students));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudentRequest $request
     * @return JsonResponse
     */
    public function store(StudentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $student = $this->studentService->createStudent($data);
        return $this->successResponse(new StudentResource($student), 'Student Created Successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Student $student): JsonResponse
    {
        $student = $this->studentService->showStudent($student);
        return $this->successResponse(new StudentResource($student));
    }

    /**
     *Display a specific student with courses.
     *
     * @param Student $student
     * @return JsonResponse
     * @throws Exception
     */
    public function showStudentWithCourses(Student $student): JsonResponse
    {
        $student = $this->studentService->showStudentCourses($student);
        return $this->successResponse(new StudentResource($student));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param Student $student
     * @return JsonResponse
     */
    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        $data = $request->validated();
        $student = $this->studentService->updateStudent($student, $data);
        return $this->successResponse(new StudentResource($student), 'Student Updated Successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function destroy(Student $student): JsonResponse
    {
        $this->studentService->deleteStudent($student);
        return $this->successResponse(null, 'Student Deleted Successfully', 200);
    }

    /**
     * Restore a trashed resource by id.
     *
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function restore($id): JsonResponse
    {
        $student = $this->studentService->restoreStudent($id);
        return $this->successResponse(new StudentResource($student), 'Student Restored Successfully', 200);
    }

    /**
     * Display a listing of the trashed resources.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDeletedStudents(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $students = $this->studentService->trashedStudents($perPage);
        return $this->resourcePaginated(StudentResource::collection($students), 'Trashed Students', 200);
    }

    /**
     * Permanently delete a resource by its ID.
     *
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function forceDelete($id): JsonResponse
    {
        $this->studentService->forceDeleteStudent($id);
        return $this->successResponse(null, 'Student Deleted Successfully', 200);
    }

    /**
     * Assign course to a specific student.
     *
     * @param AssignStudentToCourse $request
     * @param Student $student
     * @return JsonResponse
     */
    public function assignStudent(AssignStudentToCourse $request, Student $student): JsonResponse
    {
        $data = $request->validated();
        $student = $this->studentService->assignStudentToCourse($student, $data);
        return $this->successResponse(new StudentResource($student), 'Student Assigned To Course Successfully', 200);
    }

    /**
     * Unassign course from a specific student.
     *
     * @param UnassignStudentToCourse $request
     * @param Student $student
     * @return JsonResponse
     */
    public function unassignStudent(UnassignStudentToCourse $request, Student $student): JsonResponse
    {
        $data = $request->validated();
        $student = $this->studentService->unassignStudentToCourse($student, $data);
        return $this->successResponse(new StudentResource($student), 'Student Unassigned to Course Successfully', 200);
    }
}
