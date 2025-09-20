<?php
namespace App\estudent\domain\ports\input;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
interface GetReportForCourseExam
{
    public function getReportForCourseExam(int $courseExamId): BinaryFileResponse;

}