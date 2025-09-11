<?php 
namespace App\Services;

use App\Contracts\input\ExamPeriodService;
use App\Models\ExamPeriod;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExamPeriodServiceImpl implements ExamPeriodService
{
    public function getAllExamPeriods(bool $onlyActive):Collection
    {
        if($onlyActive)
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