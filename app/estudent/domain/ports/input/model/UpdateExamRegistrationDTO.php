<?php
namespace App\estudent\domain\ports\input\model;

/**
 * @OA\Schema(
 *     schema="ExamRegistrationUpdateDTO",
 *     type="object",
 *     required={"has-attended"},
 *     @OA\Property(property="mark", type="integer", example=5),
 *     @OA\Property(property="has-attended", type="boolean", example=true),
 *     @OA\Property(property="comment", type="string", example=""),
 * )
 */
class UpdateExamRegistrationDTO
{
    public int $mark;
    public bool $hasAttended;
    public string $comment;

    public function __construct(array $data)
    {
        $this->mark = $data['mark'] ?? 5;
        $this->hasAttended = $data['has-attended'] ?? false;
        $this->comment = $data['comment'] ??'';
    }
}