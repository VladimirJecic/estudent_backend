<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\CourseInstance;
use App\Models\ExamRegistration;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'indexNum',
        'name',
        'email',
        'password',
        'role',
    ];
    public function courseIntances()
    {
        return $this->belongsToMany(CourseInstance::class,'course_users');
    }
    
    public function examRegistrations()
    {
        return $this->hasMany(ExamRegistration::class,'student_id','id');
    }

    public function signedRegistrations()
    {
        return $this->hasMany(ExamRegistration::class,'signed_by_id','id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
