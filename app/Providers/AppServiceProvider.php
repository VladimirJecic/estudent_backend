<?php

namespace App\Providers;
use App\Contracts\input\CourseExamService;
use App\Contracts\input\ExamPeriodService;
use App\Contracts\input\ExamRegistrationService;
use App\Contracts\input\GetRegisterableCourseExams;
use App\Contracts\input\GetRemainingCourseExams;
use App\Contracts\input\GetReportForCourseExam;
use App\Contracts\output\GenerateCourseExamReport;
use App\Adapters\reports\GenerateCourseExamReportImpl;
use App\Models\Course;
use App\Models\CourseExam;
use App\Services\ExamRegistrationServiceImpl;
use App\Services\GetNotGradedRegistrationsServiceImpl;
use App\Services\GetRegisterableCourseExamsImpl;
use App\Services\GetRemainingCourseExamsImpl;
use App\Contracts\input\GetNotGradedExamRegistrations;
use App\Services\GetReportForCourseExamImpl;
use Illuminate\Support\ServiceProvider;
use App\Services\ExamPeriodServiceImpl;
use App\Services\CourseExamServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExamRegistrationService::class, ExamRegistrationServiceImpl::class);
        $this->app->bind(GetNotGradedExamRegistrations::class, GetNotGradedRegistrationsServiceImpl::class);
        $this->app->bind(ExamPeriodService::class,ExamPeriodServiceImpl::class);
        $this->app->bind(CourseExamService::class, CourseExamServiceImpl::class);
        $this->app->bind(GetReportForCourseExam::class, GetReportForCourseExamImpl::class);
        $this->app->bind(GenerateCourseExamReport::class, GenerateCourseExamReportImpl::class);
        $this->app->bind(GetRegisterableCourseExams::class, GetRegisterableCourseExamsImpl::class);
        $this->app->bind(GetRemainingCourseExams::class, GetRemainingCourseExamsImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
