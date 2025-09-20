<?php

namespace App\estudent\domain\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\estudent\domain\model\CustomModel;
use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_instance_id',
    ];

}