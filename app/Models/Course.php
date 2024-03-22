<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Awobaz\Compoships\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CourseExam;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'semester',
        'espb',
    ];
    public function participants()
    {
        return $this->belongsToMany(User::class);
    }

    public function courseExams()
    {
        return $this->hasMany(CourseExam::class, 'course_id');
    }

}