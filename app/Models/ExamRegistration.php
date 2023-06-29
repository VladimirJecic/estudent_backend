<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseExam;
use App\Models\User;

class ExamRegistration extends Model
{
    use HasFactory;

    public function courseExam()
    {
        return $this->belongsTo(CourseExam::class)->where([
            ['course_id', '=', $this->course_id],
            ['exam_period_id', '=', $this->exam_period_id],
        ]);
    }

    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by_id');
    }
    
    
}
