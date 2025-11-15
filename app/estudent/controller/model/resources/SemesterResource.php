<?php

namespace App\estudent\controller\model\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Semester",
 *     type="object",
 *     title="Semester",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="academic_year", type="string", example="2024/2025"),
 *     @OA\Property(property="season", type="string", example="Letnji"),
 *     @OA\Property(property="title", type="string", example="2024/2025 Letnji")
 * )
 */
class SemesterResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'academic_year' => $this->academic_year,
            'season' => $this->season->name,
            'title' => (string) $this->resource,
        ];
    }
}
