<?php
namespace App\estudent\domain\ports\output\model;

use App\estudent\domain\model\CourseExam;


class CourseExamReportDTO
{
    public string $courseExamName;
    public string $examPeriodName;
    /** @var CourseExamReportItemDTO[] */
    public array $reportItemList;
    public string $attendancePercentage;
    public string $averageScore;

    public function __construct(CourseExam $courseExam)
    {
        $this->courseExamName = $courseExam->courseInstance->course->name;
        $this->examPeriodName = $courseExam->examPeriod->name;
        $this->reportItemList = [];
        $this->attendancePercentage = "/";
        $this->averageScore = "/";

    }
}
