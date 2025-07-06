<?php
namespace App\Contracts\input\model;


class ExamRegistrationUpdateDTO
{
    public int $mark;

    public string $comment;
    public bool $hasAttended;

    public function __construct(array $data)
    {
        $this->mark = $data['mark'] ?? 5;
        $this->hasAttended = $data['hasAttended'] ?? false;
        $this->comment = $data['comment'] ??'';
    }
}