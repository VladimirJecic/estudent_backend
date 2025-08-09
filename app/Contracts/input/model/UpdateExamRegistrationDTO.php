<?php
namespace App\Contracts\input\model;

/**
 * @OA\Schema(
 *     schema="ExamRegistrationUpdateDTO",
 *     type="object",
 *     required={"hasAttended"},
 *     @OA\Property(property="mark", type="integer", example=5),
 *     @OA\Property(property="hasAttended", type="boolean", example=true),
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
        $this->hasAttended = $data['hasAttended'] ?? false;
        $this->comment = $data['comment'] ??'';
    }
}