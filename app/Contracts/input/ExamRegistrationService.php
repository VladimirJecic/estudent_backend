<?php
namespace App\Contracts\input;

use App\Contracts\input\model\ExamRegistrationStoreDTO;
use App\Contracts\input\model\ExamRegistrationUpdateDTO;
use App\Models\ExamRegistration;
use Illuminate\Support\Collection;
use App\Contracts\input\model\ExamRegistrationFilters;

interface ExamRegistrationService
{
    public function getAllExamRegistrationsWithFilters(ExamRegistrationFilters $filters): Collection;
    public function saveExamRegistration(ExamRegistrationStoreDTO $dto): ExamRegistration;
    public function updateExamRegistration(int $id, ExamRegistrationUpdateDTO $dto): ExamRegistration;
    public function deleteExamRegistration(int $id): ExamRegistration;
}