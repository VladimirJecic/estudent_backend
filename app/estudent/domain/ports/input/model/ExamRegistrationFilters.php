<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="ExamRegistrationFiltersDTO",
 *     type="object",
 *     @OA\Property(property="search-text", type="string", example=""),
 *     @OA\Property(property="page", type="integer", example=1),
 *     @OA\Property(property="page-size", type="integer", example=10),
 *     @OA\Property(property="include-not-graded", type="boolean", example=false),
 *     @OA\Property(property="include-failed", type="boolean", example=false),
 *     @OA\Property(property="include-passed", type="boolean", example=false),
 *     @OA\Property(property="include-current", type="boolean", example=false),
 *     @OA\Property(property="student-id", type="integer", example=123),
 *     @OA\Property(property="exam-period-id", type="integer", example=1),
 *     @OA\Property(property="course-exam-id", type="integer", example=1)
 * )
 */
class ExamRegistrationFilters
{
    public int $page;
    public int $pageSize;
    public ?string $searchText;
    public bool $includeNotGraded;
    public bool $includeFailed;
    public bool $includePassed;
    public bool $includeCurrent;
    public ?int $studentId;
    public ?int $examPeriodId;
    public ?int $courseExamId;

    public function __construct(array $data = null)
    {
        $this->page = $data["page"] ?? 1;
        $this->pageSize = $data["page-size"] ?? 10;
        $this->searchText = $data["search-text"] ?? null;
        $this->includeNotGraded = $data["include-not-graded"] ?? false;
        $this->includeFailed = $data["include-failed"] ?? false;
        $this->includePassed = $data["include-passed"] ?? false;
        $this->includeCurrent = $data["include-current"] ?? false;
        $this->studentId = $data["student-id"] ?? null;
        $this->examPeriodId = $data["exam-period-id"] ?? null;
        $this->courseExamId = $data["course-exam-id"] ?? null;
    }
}