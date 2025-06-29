<?php

namespace App\ports\output;

use App\ports\output\model\CourseExamReportDTO;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
interface GenerateCourseExamReport
{
    public function generateCourseExamReport(CourseExamReportDTO $reportDTO): BinaryFileResponse;
}