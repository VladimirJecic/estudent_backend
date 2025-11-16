<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="ExamPeriodMetricPointDTO",
 *     type="object",
 *     @OA\Property(property="examPeriodId", type="integer", example=1),
 *     @OA\Property(property="examPeriodName", type="string", example="Februar-2025"),
 *     @OA\Property(property="value", type="number", format="float", example=85.5)
 * )
 */
class ExamPeriodMetricPointDTO
{
    public int $examPeriodId;
    public string $examPeriodName;
    public float $value;

    public function __construct(int $examPeriodId, string $examPeriodName, float $value)
    {
        $this->examPeriodId = $examPeriodId;
        $this->examPeriodName = $examPeriodName;
        $this->value = $value;
    }
}
