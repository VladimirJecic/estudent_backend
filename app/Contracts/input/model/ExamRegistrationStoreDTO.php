<?php
namespace App\Contracts\input\model;

/**
 * @OA\Schema(
 *     schema="ExamRegistrationStoreDTO",
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
 *     @OA\Property(
 *         property="mark",
 *         type="integer",
 *         example=5,
 *         description="Mark to assign (optional, defaults to 5)"
 *     ),
 *     @OA\Property(
 *         property="hasAttended",
 *         type="boolean",
 *         example=true,
 *         description="Indicates whether the student attended the exam"
 *     )
 * )
 */

class ExamRegistrationStoreDTO
{
    public int $courseExamId;
    public int $studentId;
    public int $mark;
    public bool $hasAttended;

    public function __construct(array $data)
    {
        $this->courseExamId = $data['courseExamId'];
        $this->studentId = $data['studentId'];
        $this->mark = $data['mark'] ?? 5;
        $this->hasAttended = $data['hasAttended'] ?? false;
    }
}