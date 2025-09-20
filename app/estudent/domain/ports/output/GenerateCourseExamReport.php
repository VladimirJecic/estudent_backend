<?php

namespace App\estudent\domain\ports\output;

use App\estudent\domain\ports\output\model\CourseExamReportDTO;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
interface GenerateCourseExamReport
{
    public function generateCourseExamReport(CourseExamReportDTO $reportDTO): BinaryFileResponse;
}