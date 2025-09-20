<?php

use Illuminate\Support\Facades\Route;
use App\estudent\controller\AuthController;
use App\estudent\controller\ExamPeriodController;
use App\estudent\controller\ExamRegistrationController;
use App\estudent\controller\CourseExamController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*php artisan l5-swagger:generate
/* swagger URL: http://localhost:8081/estudent/swagger-ui/index.html*/
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function(){
    Route::get('exam-periods', [ExamPeriodController::class, 'getExamPeriods']);
    Route::get('exam-registrations', [ExamRegistrationController::class, 'getExamRegistrationsWithFiltersForStudent']);
    Route::post('exam-registrations', [ExamRegistrationController::class, 'createExamRegistration']);
    Route::delete('exam-registrations/{examRegistrationId}', [ExamRegistrationController::class, 'deleteExamRegistration']);
    Route::get('course-exams/registerable-course-exams', [CourseExamController::class,'getRegisterableCourseExams']);
    Route::get('course-exams/remaining-course-exams', [CourseExamController::class, 'getRemainingCourseExams']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::prefix('admin')->middleware('admin-auth')->group(function(){
        Route::get('exam-registrations', [ExamRegistrationController::class, 'getExamRegistrationsWithFilters']);
        Route::get('course-exams', [CourseExamController::class,'getCourseExams']);
        Route::get('course-exam-reports/{courseExamId}', [CourseExamController::class,'getReportForCourseExam']);
        Route::put('exam-registrations/{examRegistrationId}', [ExamRegistrationController::class, 'updateExamRegistration']);
    });

 });

