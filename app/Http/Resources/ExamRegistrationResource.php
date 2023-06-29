<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CourseExamResource;
use App\Http\Resources\UserResource;


class ExamRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'exam'=> new CourseExamResource($this->courseExam),
            'student'=>new UserResource($this->student),
            'signed_by' => $this->signed_by ? new UserResource($this->signed_by) : null,
            'mark' => $this->mark,
            'comment'=>$this->comment,
        ];
    }
}
