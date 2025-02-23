<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Course\CourseController;
use App\Http\Controllers\Api\V1\Instructor\InstructorController;
use App\Http\Controllers\Api\V1\Student\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth/v1')->group(function (){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/current', [AuthController::class, 'current']);
});

Route::prefix('/v1')->group(function (){

    Route::apiResource('/courses', CourseController::class);
    Route::post('/courses/restore/{courseId}', [CourseController::class, 'restore']);
    Route::get('/trashed/courses', [CourseController::class, 'getDeletedCourses']);
    Route::get('/courses/{course}/instructors', [CourseController::class, 'showCourseWithInstructors']);
    Route::get('/courses/{course}/students', [CourseController::class, 'showCourseWithStudents']);
    Route::delete('/courses/force-delete/{courseId}', [CourseController::class, 'forceDelete']);
    Route::post('/courses/assign-course-instructor/{course}', [CourseController::class, 'assignCourse']);
    Route::post('/courses/unassign-course-instructor/{course}', [CourseController::class, 'unassignCourse']);

//----------------------------------------------------------------------------------------------------------------------

    Route::apiResource('/instructors', InstructorController::class);
    Route::post('/instructors/restore/{id}', [InstructorController::class, 'restore']);
    Route::get('/trashed/instructors', [InstructorController::class, 'getDeletedInstructors']);
    Route::get('/instructors/{instructor}/courses', [InstructorController::class, 'showInstructorWithCourses']);
    Route::get('/instructors/{instructor}/students', [InstructorController::class, 'showInstructorWithStudents']);
    Route::delete('/instructors/force-delete/{id}', [InstructorController::class, 'forceDelete']);
    Route::post('/assign-instructor-course/{instructor}', [InstructorController::class, 'assignInstructor']);
    Route::post('/unassign-instructor-course/{instructor}', [InstructorController::class, 'unassignInstructor']);

//----------------------------------------------------------------------------------------------------------------------

    Route::apiResource('/students', StudentController::class);
    Route::post('/students/restore/{id}', [StudentController::class, 'restore']);
    Route::get('/trashed/students', [StudentController::class, 'getDeletedStudents']);
    Route::get('/students/{student}/courses', [StudentController::class, 'showStudentWithCourses']);
    Route::delete('/students/force-delete/{id}', [StudentController::class, 'forceDelete']);
    Route::post('/students/assign-student-course/{student}', [StudentController::class, 'assignStudent']);
    Route::post('/students/unassign-student-course/{student}', [StudentController::class, 'unassignStudent']);
});





