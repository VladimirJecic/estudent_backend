<?php

namespace External\reports;

use App\ports\output\GenerateCourseExamReport;
use App\ports\output\model\CourseExamReportDTO;
use External\Reports\model\ExcelTemplate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerateCourseExamReportImpl implements GenerateCourseExamReport
{
    public function generateCourseExamReport(CourseExamReportDTO $reportDTO): BinaryFileResponse
    {
        $fileName = 'izvestaj_' . Str::slug($reportDTO->courseExamName) . '_' . Str::slug($reportDTO->examPeriodName) . '.xlsx';
        $excelTemplate = (new ExcelTemplate("templates.course_exam_report"))->withFooter();
        $model = [
            'reportDTO' => $reportDTO,
        ];
        $download =  $excelTemplate->process($model, $fileName,3);
        return $download;
        
      
    }
}