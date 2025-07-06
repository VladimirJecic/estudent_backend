<?php
namespace App\Contracts\input;
use Illuminate\Support\Collection;

interface GetNotGradedExamRegistrations
{
    public function getAllForAdmin(): Collection;

    public function getAllForStudentId(int $studentId): Collection;
}