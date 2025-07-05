<?php

    namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CourseExam;
use App\Models\User;

class ExamRegistration extends CustomModel
{
    use HasFactory;

    protected $fillable = [
        'course_exam_id',
        'student_id',
        'signed_by_id',
        'mark',
        'comment',
        'hasAttended',
    ];

    protected $attributes = [
        'hasAttended' => false,
        'mark' => 5,
        'comment' => '',
        'signed_by_id' => null,
    ];

    public function courseExam()
    {
        return $this->belongsTo(CourseExam::class,"course_exam_id","id");
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by_id');
    }
}
