<?php
namespace App\Contracts\input\model;


class ExamRegistrationFilters
{
    public bool $excludePassed;
    public bool $excludeFailed;
    public ?int $studentId;

    public function __construct(array $data)
    {
       $this->excludePassed = $data["excludePassed"] ?? false;
       $this->excludeFailed = $data["excludeFailed"] ?? false;
       $this->studentId = $data["studentId"] ?? null;
    }
}