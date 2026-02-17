<?php
namespace App\estudent\domain\ports\input;

use App\estudent\domain\ports\input\model\SubmitExamRegistrationDTO;
use App\estudent\domain\ports\input\model\UpdateExamRegistrationDTO;
use App\estudent\domain\model\ExamRegistration;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\estudent\domain\ports\input\model\ExamRegistrationFilters;

interface ExamRegistrationService
{
    public function getAllExamRegistrationsByFilters(ExamRegistrationFilters $filters): LengthAwarePaginator;
    public function getCurrentExamRegistrations(): LengthAwarePaginator;
    public function createExamRegistration(SubmitExamRegistrationDTO $dto): ExamRegistration;
    public function updateExamRegistration(int $id, UpdateExamRegistrationDTO $dto): ExamRegistration;
    public function deleteExamRegistration(int $id): bool;
}