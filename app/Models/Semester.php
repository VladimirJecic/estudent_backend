<?php

namespace App\Models;

use App\Enums\SemesterSeason;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends CustomModel
{
    use HasFactory;

    protected $fillable = [
        'season',
        'academic_year',
    ];

    protected $casts = [
        'season' => SemesterSeason::class,
    ];

    /**
     * Relationships
     */
    public function courseInstances()
    {
        return $this->hasMany(CourseInstance::class);
    }
    public function __toString(): string
    {
      return $this->academic_year.' '.$this->season->getName();
    }


}
