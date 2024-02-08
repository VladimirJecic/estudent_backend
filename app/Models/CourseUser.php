<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Awobaz\Compoships\Database\Eloquent\Model;

class CourseUser extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
    ];

}