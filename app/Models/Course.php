<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'department',
        'year',
        'semester',
        'capacity',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'year' => 'integer',
        'semester' => 'integer',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subject')
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
            ->withPivot('status', 'final_grade', 'notes')
            ->withTimestamps();
    }
}
