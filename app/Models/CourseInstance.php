<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\CourseExam;



class CourseInstance extends CustomModel
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'semester_id',
    ];
    public function participants()
    {
        return $this->belongsToMany(User::class,'course_users');
    }

    public function courseExams()
    {
        return $this->hasMany(CourseExam::class);
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id','id');
    }
    public function course() 
    {
        return $this->belongsTo(Course::class,'course_id', 'id' );
    }

}