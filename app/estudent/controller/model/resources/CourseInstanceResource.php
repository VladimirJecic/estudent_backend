<?php
namespace App\estudent\controller\model\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CourseInstance",
 *     type="object",
 *     title="CourseInstance",
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="name", type="string", example="Matematika"),
 *     @OA\Property(property="semester", type="integer", example=2),
 *     @OA\Property(property="espb", type="integer", example=6),
 *     @OA\Property(property="participants", type="array", @OA\Items(type="string")) 
 * )
 */
class CourseInstanceResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'=> $this->id,
            'name' => $this->course->name,
            'semester' => (string) $this->semester,
            'espb' => $this->course->espb,
        ];
    }
}
