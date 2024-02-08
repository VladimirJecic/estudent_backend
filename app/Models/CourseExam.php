<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\ExamPeriod;
use App\Models\ExamRegistration;
use Awobaz\Compoships\Database\Eloquent\Model;

class CourseExam extends Model
{   
    use HasFactory;
    public $incrementing = false;
    protected $casts = [
        'course_id' => 'integer',
        'exam_period_id' => 'integer',
    ];
    protected $primaryKey = ['course_id', 'exam_period_id'];
    protected $fillable = [
        'course_id',
        'exam_period_id',
        'examDateTime',
        'hall',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id','id');
    }

    public function examPeriod()
    {
        return $this->belongsTo(ExamPeriod::class, 'exam_period_id','id');
    }

    public function examRegistrations()
    {
        return $this->hasMany(ExamRegistration::class,['course_id','exam_period_id'],['course_id','exam_period_id']);

    }
}
