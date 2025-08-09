<?php
namespace App\Contracts\input;

use App\Contracts\input\model\SubmitExamRegistrationDTO;
use App\Contracts\input\model\UpdateExamRegistrationDTO;
use App\Models\ExamRegistration;
use Illuminate\Support\Collection;
use App\Contracts\input\model\ExamRegistrationFilters;

interface ExamRegistrationService
{
    public function getAllExamRegistrationsWithFilters(ExamRegistrationFilters $filters): Collection;
    public function saveExamRegistration(SubmitExamRegistrationDTO $dto): ExamRegistration;
    public function updateExamRegistration(int $id, UpdateExamRegistrationDTO $dto): ExamRegistration;
    public function deleteExamRegistration(int $id): bool;
}