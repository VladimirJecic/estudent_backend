<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="CourseInstanceFiltersDTO",
 *     type="object",
 *     @OA\Property(property="page", type="integer", example=1),
 *     @OA\Property(property="page-size", type="integer", example=10),
 *     @OA\Property(property="search-text", type="string", example="matematika"),
 *     @OA\Property(property="semester-id", type="integer", example=1)
 * )
 */
class CourseInstanceFilters
{
    public int $page;
    public int $pageSize;
    public ?string $searchText;
    public ?int $semesterId;

    public function __construct(array $data)
    {
        $this->page = $data["page"] ?? 1;
        $this->pageSize = $data["page-size"] ?? 10;
        $this->searchText = $data["search-text"] ?? null;
        $this->semesterId = isset($data["semester-id"]) ? (int)$data["semester-id"] : null;
    }
}
