<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CustomModel;


class CourseUser extends CustomModel
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
    ];

}