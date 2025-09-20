<?php
namespace App\estudent\domain\ports\input;
use Illuminate\Support\Collection;

interface GetRegisterableCourseExams
{
    public function getRegisterableCourseExams(): Collection;

}