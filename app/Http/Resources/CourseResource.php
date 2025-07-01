<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Course",
 *     type="object",
 *     title="Course",
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="name", type="string", example="Matematika"),
 *     @OA\Property(property="semester", type="integer", example=2),
 *     @OA\Property(property="espb", type="integer", example=6),
 *     @OA\Property(property="participants", type="array", @OA\Items(type="string")) 
 * )
 */
class CourseResource extends JsonResource
{
    private $participants;

    /**
     * CourseResource constructor.
     *
     * @param mixed $resource
     * @param mixed $participants
     */
    public function __construct($resource, $participants = null)
    {
        parent::__construct($resource);
        $this->participants = $participants;
    }

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
            'name' => $this->name,
            'semester' => $this->semester,
            'espb' => $this->espb,
            'participants' => $this->participants ?? [],
        ];
    }
}
