<?php
namespace App\Contracts\input;
use Illuminate\Support\Collection;

interface GetNotGradedExamRegistrations
{
    public function getNotGradedExamRegistrations(): Collection;

    public function getNotGradedExamRegistrationsForStudentId(int $studentId): Collection;
}