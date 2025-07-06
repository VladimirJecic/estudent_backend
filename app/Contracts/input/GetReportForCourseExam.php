<?php
namespace App\Contracts\input;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
interface GetReportForCourseExam
{
    public function getReportForCourseExam(int $courseExamId): BinaryFileResponse;

}