<?php

namespace App\Http\Controllers\Api\V1\Course;

use App\Helpers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\AssignCourseToInstructorRequest;
use App\Http\Requests\Course\CourseRequest;
use App\Http\Requests\Course\UnassignCourseToInstructorRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponseTrait;
    protected CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
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
        $courses =  $this->courseService->listCourses($perPage);
        return $this->resourcePaginated(CourseResource::collection($courses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseRequest $request
     * @return JsonResponse
     */
    public function store(CourseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $course = $this->courseService->createCourse($data);
        return $this->successResponse(new CourseResource($course), 'Course Created Successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Course $course): JsonResponse
    {
        $course = $this->courseService->showCourse($course);
        return $this->successResponse(new CourseResource($course));
    }

    /**
     * Display a specific course with instructors.
     *
     * @param Course $course
     * @return JsonResponse
     * @throws Exception
     */
    public function showCourseWithInstructors(Course $course): JsonResponse
    {
        $course = $this->courseService->showCourseInstructors($course);
        return $this->successResponse(new CourseResource($course));
    }

    /**
     * Display a specific course with students.
     *
     * @param Course $course
     * @return JsonResponse
     * @throws Exception
     */
    public function showCourseWithStudents(Course $course): JsonResponse
    {
        $course = $this->courseService->showCourseStudents($course);
        return $this->successResponse(new CourseResource($course));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCourseRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $data = $request->validated();
        $course = $this->courseService->updateCourse($course, $data);
        return $this->successResponse(new CourseResource($course), 'Course Updated Successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function destroy(Course $course): JsonResponse
    {
        $this->courseService->deleteCourse($course);
        return $this->successResponse(null, 'Course Deleted Successfully', 200);
    }

    /**
     * Restore a trashed resource by id.
     *
     * @param $courseId
     * @return JsonResponse
     * @throws Exception
     */
    public function restore($courseId): JsonResponse
    {
        $course = $this->courseService->restoreCourse($courseId);
        return $this->successResponse(new CourseResource($course), 'Course Restored Successfully', 200);
    }

    /**
     * Display a listing of the trashed resources.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDeletedCourses(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $trashedCourses = $this->courseService->trashedCourses($perPage);
        return $this->resourcePaginated(new CourseResource($trashedCourses));
    }

    /**
     * Permanently delete a resource by its ID.
     *
     * @param $courseId
     * @return JsonResponse
     * @throws Exception
     */
    public function forceDelete($courseId): JsonResponse
    {
        $this->courseService->forceDeleteCourse($courseId);
        return $this->successResponse(null, 'Course Deleted Successfully', 200);
    }

    /**
     * Assign instructor to a specific course.
     *
     * @param AssignCourseToInstructorRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function assignCourse(AssignCourseToInstructorRequest $request, Course $course): JsonResponse
    {
        $data = $request->validated();
        $course = $this->courseService->assignCourseToInstructor($course, $data);
        return $this->successResponse(new CourseResource($course), 'Instructor assigned to course successfully', 200);
    }

    /**
     * Unassign instructor from a specific course.
     *
     * @param UnassignCourseToInstructorRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function unassignCourse(UnassignCourseToInstructorRequest $request, Course $course): JsonResponse
    {
        $data = $request->validated();
        $course = $this->courseService->unassignCourseToInstructor($course, $data);
        return $this->successResponse(new CourseResource($course), 'Instructor unassigned to course successfully');
    }
}
