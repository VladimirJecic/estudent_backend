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
            'courseExam'=> new CourseExamResource($this->courseExam),
            'student'=>new UserResource($this->student),
            'signed_by' => $this->signedBy ? new UserResource($this->signedBy) : null,
            'mark' => $this->mark,
            'comment'=>$this->comment,
            "updated_at"=>$this->updated_at
        ];
    }
}
