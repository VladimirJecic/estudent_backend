<?php

namespace App\estudent\domain\model;

use Database\Factories\CourseExamFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\estudent\domain\model\CourseInstance;
use Illuminate\Database\Eloquent\Model;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\model\ExamRegistration;

class CourseExam extends Model
{   
    use HasFactory;

    protected static function newFactory()
    {
        return CourseExamFactory::new();
    }
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
