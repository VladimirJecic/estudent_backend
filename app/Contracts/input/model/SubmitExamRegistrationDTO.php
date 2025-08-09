<?php
namespace App\Contracts\input\model;

/**
 * @OA\Schema(
 *     schema="SubmitExamRegistrationDTO",
 *     type="object",
 *     required={"courseExamId", "studentId", "hasAttended"},
 *     @OA\Property(
 *         property="courseExamId",
 *         type="integer",
 *         example=12,
 *         description="ID of the CourseExam"
 *     ),
 *     @OA\Property(
 *         property="studentId",
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
        $this->courseExamId = $data['courseExamId'];
        $this->studentId = $data['studentId'];
    }
}