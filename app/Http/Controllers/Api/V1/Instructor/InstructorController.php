<?php

namespace App\Http\Controllers\Api\V1\Instructor;

use App\Helpers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\AssignInstructorToCourseRequest;
use App\Http\Requests\Instructor\InstructorRequest;
use App\Http\Requests\Instructor\UnassignInstructorToCourseRequest;
use App\Http\Requests\Instructor\UpdateInstructorRequest;
use App\Http\Resources\Instructor\InsructorStudentResource;
use App\Http\Resources\Instructor\InstructorResource;
use App\Models\Instructor;
use App\Services\InstructorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    use ApiResponseTrait;

    protected InstructorService $instructorService;

    /**
     * @param InstructorService $instructorService
     */
    public function __construct(InstructorService $instructorService)
    {
        $this->instructorService = $instructorService;
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
        $instructors = $this->instructorService->listInstructors($perPage);
        return $this->resourcePaginated(InstructorResource::collection($instructors));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InstructorRequest $request
     * @return JsonResponse
     */
    public function store(InstructorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $instructor = $this->instructorService->createInstructor($data);
        return $this->successResponse(new InstructorResource($instructor), 'Instructor Created Successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Instructor $instructor
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Instructor $instructor): JsonResponse
    {
        $instructor = $this->instructorService->showInstructor($instructor);
        return $this->successResponse(new InstructorResource($instructor));
    }

    /**
     * Display a specific instructor with courses.
     *
     * @param Instructor $instructor
     * @return JsonResponse
     * @throws Exception
     */
    public function showInstructorWithCourses(Instructor $instructor): JsonResponse
    {
        $instructor = $this->instructorService->showInstructorCourses($instructor);
        return $this->successResponse(new InstructorResource($instructor));
    }

    /**
     * Display a specific instructor with students.
     *
     * @param Instructor $instructor
     * @return JsonResponse
     * @throws Exception
     */
    public function showInstructorWithStudents(Instructor $instructor): JsonResponse
    {
        $instructor = $this->instructorService->showInstructorStudents($instructor);
        return $this->successResponse(new InsructorStudentResource($instructor));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInstructorRequest $request
     * @param Instructor $instructor
     * @return JsonResponse
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor): JsonResponse
    {
        $data = $request->validated();
        $instructor = $this->instructorService->updateInstructor($instructor, $data);
        return $this->successResponse(new InstructorResource($instructor), 'Instructor Updated Successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Instructor $instructor
     * @return JsonResponse
     */
    public function destroy(Instructor $instructor): JsonResponse
    {
        $this->instructorService->deleteInstructor($instructor);
        return $this->successResponse(null, 'Instructor Deleted Successfully', 200);
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
        $instructor = $this->instructorService->restoreInstructor($id);
        return $this->successResponse(new InstructorResource($instructor), 'Instructor Restored Successfully', 200);
    }

    /**
     * Display a listing of the trashed resources.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDeletedInstructors(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $trashedCourses = $this->instructorService->trashedInstructors($perPage);
        return$this->resourcePaginated(new InstructorResource($trashedCourses));
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
        $this->instructorService->forceDeleteInstructor($id);
        return $this->successResponse(null, 'Instructor Deleted Successfully', 200);
    }

    /**
     * Assign course to a specific instructor.
     *
     * @param AssignInstructorToCourseRequest $request
     * @param Instructor $instructor
     * @return JsonResponse
     */
    public function assignInstructor(AssignInstructorToCourseRequest $request, Instructor $instructor): JsonResponse
    {
        $data = $request->validated();
        $assignInstructor = $this->instructorService->assignInstructorToCourse($instructor, $data);
        return $this->successResponse(new InstructorResource($assignInstructor), 'Instructor Assigned To Course Successfully', 200);
    }

    /**
     * Unassign course from a specific instructor.
     *
     * @param UnassignInstructorToCourseRequest $request
     * @param Instructor $instructor
     * @return JsonResponse
     */
    public function unassignInstructor(UnassignInstructorToCourseRequest $request, Instructor $instructor): JsonResponse
    {
        $data = $request->validated();
        $unassignInstructor = $this->instructorService->unassignInstructorToCourse($instructor, $data);
        return $this->successResponse(new InstructorResource($unassignInstructor), 'Instructor Unassigned To Course Successfully', 200);
    }
}
