<?php
    // phpinfo()
    echo nl2br('Route::middleware("auth:api")->group(function(){
    Route::middleware("admin-auth")->group(function(){
        //User routes(admin)
        Route::resource("users",UserController::class )->only(["index","show","update","destroy"]);
        // User registration (admin)
        Route::post("register", [AuthController::class, "register"]);
        // ExamPeriod routes (admin)
        Route::resource("exam_periods", ExamPeriodController::class)->only(["store","destroy"]);
        // Course routes (admin)
         Route::resource("courses", CourseController::class)->only(["store","update","destroy"]);
        // CourseExam routes (admin)
        Route::resource("course-exams", CourseExamController::class)->only(["update","store", "destroy"]);

    });
        // User logout
        Route::post("logout", [AuthController::class, "logout"]);
        // ExamPeriod routes
        Route::resource("exam-periods", ExamPeriodController::class)->only(["index"]);
        // Course routes
        Route::resource("courses", CourseController::class)->only("index");
        // CourseExam routes
        Route::resource("course-exams", CourseExamController::class)->only(["passed","notPassed"]);
        ----------------------------------------------------------------------------------------------------------------
        Example Requests:

        POST /api/register
        Content-Type: application/json
        {
        "indexNum": "12345",
        "name": "John Doe",
        "password": "secretpassword",
        "confirmPassword": "secretpassword"
        }

        POST /api/login
        Content-Type: application/json
        {
        "indexNum": "12345",
        "password": "secretpassword"
        }


});')
    ?>