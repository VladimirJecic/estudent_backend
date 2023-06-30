<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\ExamPeriod;
use App\Models\ExamRegistration;

class CourseExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'exam_period_id',
        'examDateTime',
        'hall',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function examPeriod()
    {
        return $this->belongsTo(ExamPeriod::class, 'exam_period_id');
    }

    public function examRegistrations()
    {
        return $this->hasMany(ExamRegistration::class)->where([
            ['course_id', '=', $this->course_id],
            ['exam_period_id', '=', $this->exam_period_id],
        ]);
    }
}
