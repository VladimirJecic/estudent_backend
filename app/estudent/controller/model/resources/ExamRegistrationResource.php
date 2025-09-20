<?php
/**
 * @OA\Schema(
 *     schema="ExamRegistration",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="courseExam", ref="#/components/schemas/CourseExam"),
 *     @OA\Property(property="student", ref="#/components/schemas/User"),
 *     @OA\Property(property="signedBy", ref="#/components/schemas/User"),
 *     @OA\Property(property="mark", type="integer", example=5),
 *     @OA\Property(property="hasAttended", type="boolean", example=true),
 *     @OA\Property(property="comment", type="string", example=""),
 *     @OA\Property(property="updatedAt", type="string", format="date-time", example="2025-09-12T12:00:00")
 * )
 */

namespace App\estudent\controller\model\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\estudent\controller\model\resources\CourseExamResource;
use App\estudent\controller\model\resources\UserResource;

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
            "id"=> $this->id,
            'courseExam'=> new CourseExamResource($this->courseExam),
            'student'=>new UserResource($this->student),
            'signedBy' => $this->signedBy ? new UserResource($this->signedBy) : null,
            'mark' => $this->mark,
            'hasAttended' => $this->hasAttended,
            'comment'=>$this->comment,
            "updatedAt"=>$this->updated_at
        ];
    }
}
