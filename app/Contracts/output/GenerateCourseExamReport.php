<?php

namespace App\Contracts\output;

use App\Contracts\output\model\CourseExamReportDTO;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
interface GenerateCourseExamReport
{
    public function generateCourseExamReport(CourseExamReportDTO $reportDTO): BinaryFileResponse;
}