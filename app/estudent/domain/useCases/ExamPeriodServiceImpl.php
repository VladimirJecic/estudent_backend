<?php 
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\ExamPeriodService;
use App\estudent\domain\ports\input\model\ExamPeriodFilters;
use App\estudent\domain\model\ExamPeriod;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExamPeriodServiceImpl implements ExamPeriodService
{
    public function getAllExamPeriodsWithFilters(ExamPeriodFilters $examPeriodFilters): Collection
    {
        if($examPeriodFilters->onlyActive)
            return  $this->active();
        else{
            return $this->all();
        }
    }


    public function active(): Collection
    {
        $currentDate = Carbon::now();
        $examPeriods = ExamPeriod::with('exams')->where('dateRegisterStart', '<=', $currentDate)
        ->where('dateEnd', '>=', $currentDate)
        ->get();
     
        return $examPeriods;
    }
    private function all(): Collection{
         $examPeriods = ExamPeriod::with('exams')->get();
        return $examPeriods;
    }
    public function registerable(): Collection
    {
        $currentDate = Carbon::now();
        $examPeriods = ExamPeriod::with('exams')->where('dateRegisterStart', '<=', $currentDate)
        ->where('dateRegisterEnd', '>=', $currentDate)
        ->get();
     
        return $examPeriods;
    }
}