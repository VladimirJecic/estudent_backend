<?php

namespace App\estudent\domain\model;

use App\estudent\domain\model\enums\SemesterSeason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
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
