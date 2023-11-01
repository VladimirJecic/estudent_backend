<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'name' => $this->name,
            'dateRegistrationStart'=> $this->dateRegisterStart,
            'dateRegistrationEnd'=> $this->dateRegisterEnd,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'exams' => CourseExamResource::collection($this->exams),
        ];
    }
}
