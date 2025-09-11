<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

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
