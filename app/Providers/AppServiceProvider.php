<?php

namespace App\Providers;
use App\estudent\domain\ports\input\CourseExamService;
use App\estudent\domain\ports\input\CourseInstanceService;
use App\estudent\domain\ports\input\ExamPeriodService;
use App\estudent\domain\ports\input\ExamRegistrationService;
use App\estudent\domain\ports\input\GetRegisterableCourseExams;
use App\estudent\domain\ports\input\GetRemainingCourseExams;
use App\estudent\domain\ports\input\GetReportForCourseExam;
use App\estudent\domain\ports\input\GetReportDataForCourseInstance;
use App\estudent\domain\ports\output\GenerateCourseExamReport;
use App\estudent\adapters\reports\GenerateCourseExamReportImpl;
use App\estudent\domain\model\Course;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\useCases\CourseInstanceServiceImpl;
use App\estudent\domain\useCases\ExamRegistrationServiceImpl;
use App\estudent\domain\useCases\GetNotGradedRegistrationsServiceImpl;
use App\estudent\domain\useCases\GetRegisterableCourseExamsImpl;
use App\estudent\domain\useCases\GetRemainingCourseExamsImpl;
use App\estudent\domain\ports\input\GetNotGradedExamRegistrations;
use App\estudent\domain\useCases\GetReportForCourseExamImpl;
use App\estudent\domain\useCases\GetReportDataForCourseInstanceImpl;
use Illuminate\Support\ServiceProvider;
use App\estudent\domain\useCases\ExamPeriodServiceImpl;
use App\estudent\domain\useCases\CourseExamServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExamRegistrationService::class, ExamRegistrationServiceImpl::class);
        $this->app->bind(ExamPeriodService::class,ExamPeriodServiceImpl::class);
        $this->app->bind(CourseExamService::class, CourseExamServiceImpl::class);
        $this->app->bind(CourseInstanceService::class, CourseInstanceServiceImpl::class);
        $this->app->bind(GetReportForCourseExam::class, GetReportForCourseExamImpl::class);
        $this->app->bind(GetReportDataForCourseInstance::class, GetReportDataForCourseInstanceImpl::class);
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
