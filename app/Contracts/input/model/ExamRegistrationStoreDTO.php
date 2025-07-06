<?php
namespace App\Contracts\input\model;


class ExamRegistrationStoreDTO
{
    public int $courseExamId;
    public int $studentId;
    public int $mark;
    public bool $hasAttended;

    public function __construct(array $data)
    {
        $this->courseExamId = $data['course_exam_id'];
        $this->studentId = $data['student_id'];
        $this->mark = $data['mark'] ?? 5;
        $this->hasAttended = $data['hasAttended'] ?? false;
    }
}