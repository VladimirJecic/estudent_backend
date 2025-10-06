<?php

namespace App\estudent\domain\model;

use Database\Factories\CourseFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return CourseFactory::new();
    }

    protected $fillable = [
        'name',
        'espb',
    ];
    protected $casts = [
        'espb' => 'integer',
    ];
    /**
     * Instances of this course across semesters.
     */
    public function instances(): HasMany
    {
        return $this->hasMany(CourseInstance::class);
    }

}
