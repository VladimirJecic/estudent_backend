<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseExam;


class ExamPeriod extends Model
{
    use HasFactory;
    public function exams()
    {
        return $this->hasMany(CourseExam::class, 'exam_period_id');
    }

    protected $fillable = [
        'name',
        'dateStart',
        'dateEnd',
    ];
}