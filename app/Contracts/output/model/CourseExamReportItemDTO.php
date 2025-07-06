<?php

namespace App\Contracts\output\model;

use App\Models\ExamRegistration;

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
