<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="ExamPeriodFiltersDTO",
 *     type="object",
 *     @OA\Property(property="only-active", type="boolean", example=false)
 * )
 */
class ExamPeriodFilters
{
    public bool $onlyActive;

    public function __construct(array $data)
    {
        $this->onlyActive = $data["only-active"] ?? false;
    }
}
