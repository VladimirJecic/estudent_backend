<?php

namespace App\estudent\domain\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\estudent\domain\model\CourseExam;
use Illuminate\Database\Eloquent\Model;

class ExamPeriod extends Model
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