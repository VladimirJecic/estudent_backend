<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExamPeriodResource;

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
            'examPeriod'=> new ExamPeriodResource($this->examPeriod),
            'course' => new CourseResource($this->course),
            'examDateTime'=>$this->examDateTime,
            'hall'=>$this->hall,
        ];
    }
}
