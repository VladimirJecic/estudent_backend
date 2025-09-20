<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="SubmitExamRegistrationDTO",
 *     type="object",
 *     required={"course-exam-id", "student-id"},
 *     @OA\Property(
 *         property="course-exam-id",
 *         type="integer",
 *         example=12,
 *         description="ID of the CourseExam"
 *     ),
 *     @OA\Property(
 *         property="student-id",
 *         type="integer",
 *         example=34,
 *         description="ID of the Student"
 *     ),
 * )
 */

class SubmitExamRegistrationDTO
{
    public int $courseExamId;
    public int $studentId;
    

    public function __construct(array $data)
    {
        $this->courseExamId = $data['course-exam-id'];
        $this->studentId = $data['student-id'];
    }
}