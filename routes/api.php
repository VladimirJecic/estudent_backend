<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamPeriodController;
use App\Http\Controllers\CourseController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// User login
Route::post('login', [AuthController::class, 'login']);



Route::middleware('auth:api')->group(function(){
    Route::middleware('admin-auth')->group(function(){
        //User routes(admin)
        Route::resource('users',UserController::class )->only(['index','show','update','destroy']);
        // User registration (admin)
        Route::post('register', [AuthController::class, 'register']);
        // ExamPeriod routes (admin)
        Route::resource('exam_periods', ExamPeriodController::class)->only(['store','destroy']);
        // // Course routes (admin)
         Route::resource('courses', CourseController::class)->only(['store','update','destroy']);
        // CourseExam routes (admin)
        Route::resource('course-exams', CourseExamController::class)->only(['update','store', 'destroy']);

    });
        // User logout
        Route::post('logout', [AuthController::class, 'logout']);
        // ExamPeriod routes
        Route::resource('exam-periods', ExamPeriodController::class)->only(['index']);
        // Course routes
        Route::resource('courses', CourseController::class)->only('index');



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

