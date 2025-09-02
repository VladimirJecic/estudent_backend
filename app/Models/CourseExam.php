<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CourseInstance;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExamPeriod;
use App\Models\ExamRegistration;

class CourseExam extends Model
{   
    use HasFactory;
    protected $fillable = [
        'course_instance_id',
        'exam_period_id',
        'examDateTime',
        'hall',
    ];
    public function courseInstance()
    {
        return $this->belongsTo(CourseInstance::class, 'course_instance_id','id');
    }

    public function examPeriod()
    {
        return $this->belongsTo(ExamPeriod::class, 'exam_period_id','id');
    }

    public function examRegistrations()
    {
        return $this->hasMany(ExamRegistration::class);

    }
}
