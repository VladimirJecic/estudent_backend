<?php
namespace App\estudent\domain\ports\input;

use App\estudent\domain\ports\input\model\CourseSemesterReportDTO;

interface GetReportDataForCourseInstance
{
    public function getReportDataForCourseInstance(int $courseInstanceId): CourseSemesterReportDTO;
}
