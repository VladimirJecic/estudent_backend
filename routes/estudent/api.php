<?php

use Illuminate\Support\Facades\Route;
use App\estudent\controller\AuthController;
use App\estudent\controller\ExamPeriodController;
use App\estudent\controller\ExamRegistrationController;
use App\estudent\controller\CourseExamController;
use App\estudent\controller\CourseController;
use App\estudent\controller\SemesterController;
/*
*php artisan l5-swagger:generate
/* swagger URL: http://localhost:8001/estudent/swagger-ui/index.html */
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function(){
    Route::get('exam-periods', [ExamPeriodController::class, 'getExamPeriodsWithFilters']);
    Route::post('exam-registrations', [ExamRegistrationController::class, 'createExamRegistration']);
    Route::get('exam-registrations', [ExamRegistrationController::class, 'getExamRegistrationsWithFiltersForStudent']);
    Route::get('exam-registrations/passed', [ExamRegistrationController::class, 'getPassedExamRegistrationsForStudent']);
    Route::get('exam-registrations/current', [ExamRegistrationController::class, 'getCurrentExamRegistrationsForStudent']);
    Route::delete('exam-registrations/{examRegistrationId}', [ExamRegistrationController::class, 'deleteExamRegistration']);
    Route::get('course-exams/registerable-course-exams', [CourseExamController::class,'getRegisterableCourseExams']);
    Route::get('course-exams/remaining-course-exams', [CourseExamController::class, 'getRemainingCourseExams']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::prefix('admin')->middleware('admin-auth')->group(function(){
        Route::get('exam-registrations', [ExamRegistrationController::class, 'getExamRegistrationsByFiltersForAdmin']);
        Route::get('course-exams', [CourseExamController::class,'getCourseExamsByFilters']);
        Route::get('course-exam-reports/{courseExamId}', [CourseExamController::class,'getReportForCourseExam']);
        Route::get('course-instances', [CourseController::class, 'getCourseInstancesWithFilters']);
        Route::get('course-report-data/{courseInstanceId}', [CourseController::class, 'getReportDataForCourseInstance']);
        Route::get('semesters', [SemesterController::class, 'getSemesters']);
        Route::put('exam-registrations/{examRegistrationId}', [ExamRegistrationController::class, 'updateExamRegistration']);
    });

 });

