<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamPeriodController;
use App\Http\Controllers\ExamRegistrationController;
use App\Http\Controllers\CourseExamController;
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
    Route::resource('exam-periods', ExamPeriodController::class)->only(['index']);
    Route::resource('exam-registrations', ExamRegistrationController::class)->only(['index','store','destroy']);
    Route::get('course-exams/registerable-course-exams', [CourseExamController::class,'getRegisterableCourseExams']);
    Route::get('course-exams/remaining-course-exams', [CourseExamController::class, 'getRemainingCourseExams']);
    Route::get('exam-registrations/not-graded',[ExamRegistrationController::class,'getNotGradedForStudent']);
    Route::post('logout', [AuthController::class, 'logout']);
    
     Route::middleware('admin-auth')->group(function(){
         Route::get('course-exams', [CourseExamController::class,'index']);
         Route::get( "course-exam-reports/{courseExamId}", [CourseExamController::class,'getReportForCourseExam']);
         Route::get('exam-registrations/not-graded/all',[ExamRegistrationController::class,'getAllNotGradedForAdmin']);
         Route::put('exam-registrations/{examRegistrationId}', [ExamRegistrationController::class, 'update']);
     });

 });

