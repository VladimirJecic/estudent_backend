<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="CourseSemesterReportDTO",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Matematika - 2024/2025 Zimski"),
 *     @OA\Property(property="attendanceSeries", type="array", @OA\Items(ref="#/components/schemas/ExamPeriodMetricPointDTO")),
 *     @OA\Property(property="passageSeries", type="array", @OA\Items(ref="#/components/schemas/ExamPeriodMetricPointDTO")),
 *     @OA\Property(property="averageGradesSeries", type="array", @OA\Items(ref="#/components/schemas/ExamPeriodMetricPointDTO")),
 *     @OA\Property(property="enrolledCount", type="integer", example=120),
 *     @OA\Property(property="passedCount", type="integer", example=95)
 * )
 */
class CourseSemesterReportDTO
{
    public string $title;
    /** @var ExamPeriodMetricPointDTO[] */
    public array $attendanceSeries;
    /** @var ExamPeriodMetricPointDTO[] */
    public array $passageSeries;
    /** @var ExamPeriodMetricPointDTO[] */
    public array $averageGradesSeries;
    public int $enrolledCount;
    public int $passedCount;

    public function __construct(
        string $title,
        array $attendanceSeries,
        array $passageSeries,
        array $averageGradesSeries,
        int $enrolledCount,
        int $passedCount
    ) {
        $this->title = $title;
        $this->attendanceSeries = $attendanceSeries;
        $this->passageSeries = $passageSeries;
        $this->averageGradesSeries = $averageGradesSeries;
        $this->enrolledCount = $enrolledCount;
        $this->passedCount = $passedCount;
    }
}
