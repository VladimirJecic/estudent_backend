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
*/

// User login
Route::post('login', [AuthController::class, 'login']);


 Route::middleware('auth:api')->group(function(){
     Route::resource('exam-periods', ExamPeriodController::class)->only(['index']);
     Route::resource('exam-registrations', ExamRegistrationController::class)->only(['index','store']);
     Route::get('course-exams/{examPeriod}', [CourseExamController::class, 'getRemainingCourseExams']);
     Route::get('course-exams/registable', [CourseExamController::class,'getRegistableCourseExams']);
     Route::get('exam-registrations/notGraded',[ExamRegistrationController::class,'notGraded']);
     Route::post('logout', [AuthController::class, 'logout']);
    
     Route::middleware('admin-auth')->group(function(){
         Route::post('register', [AuthController::class, 'register']);
 
     });

 });


// // ExamRegistration routes(admin)
// Route::resource('exam-registrations', ExamRegistrationController::class)->only(['update']);


// Route::middleware('auth:api')->group(function () {

//         // // CourseExam routes (student)
//         // Route::post('course-exams', [CourseExamController::class, 'notPassed']);//prvo naci koji je trenutno rok u toku,pa naci sve 
//         // //courseve u tom roku sa left joinom preko coursa i studentu vratiti samo one ispite koji su imali para NULL u joinu, a takodje vratiti
//         // //i one koje je prijavio,ali nije jos uvek polozio
//         // // ExamRegistration routes(student)
//         // Route::resource('exam-registrations', ExamRegistrationController::class)->only(['show','store', 'destroy']);
// });

