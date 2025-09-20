<?php
namespace App\estudent\domain\ports\input\model;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="CourseExamFiltersDTO",
 *     type="object",
 *     @OA\Property(property="page", type="integer", example=1),
 *     @OA\Property(property="page-size", type="integer", example=10),
 *     @OA\Property(property="course-name", type="string", example="matematika"),
 *     @OA\Property(property="date-from", type="string", format="date", example="2025-06-01"),
 *     @OA\Property(property="date-to", type="string", format="date", example="2025-06-30")
 * )
 */
class CourseExamFilters
{
    public int $page;
    public int $pageSize;
    public ?string $courseName;

    public ?string $dateFrom;

    public ?string $dateTo;

    public function __construct(array $data)
    {
       $this->page = $data["page"] ?? 1;
       $this->pageSize = $data["page-size"] ?? 10;
       $this->courseName = $data["course-name"] ?? null;
       $this->dateFrom = isset($data['date-from']) ? Carbon::parse($data['date-from'])->toDateString() : null;
       $this->dateTo = isset($data['date-to']) ? Carbon::parse($data['date-to'])->toDateString() : null;
    }
}