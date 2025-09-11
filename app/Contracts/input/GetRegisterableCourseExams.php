<?php
namespace App\Contracts\input;
use Illuminate\Support\Collection;

interface GetRegisterableCourseExams
{
    public function getRegisterableCourseExams(): Collection;

}