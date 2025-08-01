<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CourseExam;


class ExamPeriod extends CustomModel
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dateRegisterStart',
        'dateRegisterEnd',
        'dateStart',
        'dateEnd',
        'name',
    ];
    public function exams()
    {
        return $this->hasMany(CourseExam::class, 'exam_period_id','id');
    }
}