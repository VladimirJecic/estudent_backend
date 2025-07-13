<?php

namespace App\Adapters\reports;

use App\Contracts\output\GenerateCourseExamReport;
use App\Contracts\output\model\CourseExamReportDTO;
use App\Adapters\reports\model\ExcelTemplate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerateCourseExamReportImpl implements GenerateCourseExamReport
{
    public function generateCourseExamReport(CourseExamReportDTO $reportDTO): BinaryFileResponse
    {
        $fileName = 'izvestaj_' . Str::slug($reportDTO->courseExamName) . '_' . Str::slug($reportDTO->examPeriodName) . '.xlsx';
        $templatePath = config('reports.course_exam_template');
        $excelTemplate = (new ExcelTemplate($templatePath))->withFooter();
        $model = [
            'reportDTO' => $reportDTO,
        ];
        $download =  $excelTemplate->process($model, $fileName,3);
        return $download;
        
      
    }
}