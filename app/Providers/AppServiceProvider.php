<?php

namespace App\Providers;
use App\ports\output\GenerateCourseExamReport;
use External\Reports\GenerateCourseExamReportImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GenerateCourseExamReport::class, GenerateCourseExamReportImpl::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
