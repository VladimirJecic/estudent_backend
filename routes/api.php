<?php

use App\Http\Controllers\CourseExamReportController;
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
*/
/* swagger URL: http://localhost:8081/estudent/swagger-ui/index.html*/
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function(){
    Route::resource('exam-periods', ExamPeriodController::class)->only(['index']);
    Route::resource('exam-registrations', ExamRegistrationController::class)->only(['index','store']);
    Route::delete('/exam-registrations', [ExamRegistrationController::class,'destroy']);
    Route::get('course-exams/registable', [CourseExamController::class,'getRegistableCourseExams']);
    Route::get('course-exams/{examPeriod}', [CourseExamController::class, 'getRemainingCourseExams']);
    Route::get('exam-registrations/notGraded',[ExamRegistrationController::class,'notGraded']);
    Route::post('logout', [AuthController::class, 'logout']);
    
     Route::middleware('admin-auth')->group(function(){
        Route::post('register', [AuthController::class, 'register']);
        Route::get('exam-registrations/notGraded/all',[ExamRegistrationController::class,'notGraded_all']);
        Route::put('/exam-registrations', [ExamRegistrationController::class,'update']);
        Route::get( "/course-exam-reports/{courseId}/{examPeriodId}", [CourseExamReportController::class,'getReportForCourseExam']);
     });

 });

