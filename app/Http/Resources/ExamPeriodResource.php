<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ExamPeriod",
 *     type="object",
 *     title="ExamPeriod",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="June 2025"),
 *     @OA\Property(property="dateRegisterStart", type="string", format="date", example="2025-05-01"),
 *     @OA\Property(property="dateRegisterEnd", type="string", format="date", example="2025-05-31"),
 *     @OA\Property(property="dateStart", type="string", format="date", example="2025-06-15"),
 *     @OA\Property(property="dateEnd", type="string", format="date", example="2025-06-20")
 * )
 */
class ExamPeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dateRegisterStart'=> $this->dateRegisterStart,
            'dateRegisterEnd'=> $this->dateRegisterEnd,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'exams' => CourseExamResource::collection($this->whenLoaded('exams')),
        ];
    }
}
