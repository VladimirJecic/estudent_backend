<?php

namespace App\estudent\controller\model\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\estudent\controller\model\resources\CourseInstanceResource;
use App\estudent\controller\model\resources\ExamPeriodResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Json;

/**
 * @OA\Schema(
 *     schema="CourseExam",
 *     type="object",
 *     title="CourseExam",
 *     @OA\Property(property="examPeriod", ref="#/components/schemas/ExamPeriod"),
 *     @OA\Property(property="course", ref="#/components/schemas/CourseInstance"),
 *     @OA\Property(property="examDateTime", type="string", format="date-time", example="2025-06-20T10:00:00"),
 *     @OA\Property(property="hall", type="string", example="A1")
 * )
 */
class CourseExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'examPeriod'=> $this->examPeriod ? new ExamPeriodResource($this->examPeriod): null,
            'course' => new CourseInstanceResource($this->courseInstance),
            'examDateTime'=>$this->examDateTime
            ? Carbon::parse($this->examDateTime)->toISOString()
            : null,
            'hall'=>$this->hall,
        ];
    }
}
