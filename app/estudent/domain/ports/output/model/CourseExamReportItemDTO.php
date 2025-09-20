<?php

namespace App\estudent\domain\ports\output\model;

use App\estudent\domain\model\ExamRegistration;

class CourseExamReportItemDTO 
{
    public string $studentIndexNum;
    public string $studentName;
    public string $mark;

    public bool $hasAttended;

    public function __construct(ExamRegistration $examRegistration)
    {
        $this->studentIndexNum = $examRegistration->student->indexNum;
        $this->studentName = $examRegistration->student->name;
        $this->mark = $examRegistration->mark;
        $this->hasAttended = $examRegistration->hasAttended;
    }
}
