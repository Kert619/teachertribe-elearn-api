<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PhaseController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\RoleContoller;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ValidateLevelController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function(){

    // Public Routes
    Route::post('/login',[AuthController::class,'login']);

    // Protected Routes
    Route::middleware(['auth:sanctum'])->group(function(){
      Route::get('/me', [AuthController::class, 'me']);


      
      Route::post('/logout', [AuthController::class, 'logout']);
      Route::post('/validate-level/{level}', ValidateLevelController::class);

      // CLASSROOM CONTROLLER
      Route::post('/classrooms/{classroom}/assign-courses',[ClassroomController::class,'assignCourses']);
      Route::get('/classrooms/get-by-name',[ClassroomController::class, 'getByName']);
      Route::post('/classrooms/{classroom}/assign-students', [ClassroomController::class, 'assignStudents']);

      // COURSE CONTROLLER
      Route::get('/courses/get-by-name', [CourseController::class, 'getByName']);

      // LEVEL CONTROLLER
      Route::get('/levels/get-by-course-phase-level', [LevelController::class, 'getByCoursePhaseLevel']);
  
      Route::apiResource('/roles', RoleContoller::class);
      Route::apiResource('/permissions', PermissionController::class);
      Route::apiResource('/users', UserController::class);
      Route::apiResource('/levels', LevelController::class);
      Route::apiResource('/courses', CourseController::class);
      Route::apiResource('/phases', PhaseController::class);
      Route::apiResource('/classrooms', ClassroomController::class);
      Route::apiResource('/quizzes', QuizController::class);
      Route::apiResource('/questions', QuestionController::class);
      Route::apiResource('/answers', AnswerController::class);
    });

});
