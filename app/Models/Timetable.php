<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_subject_id',
        'day_of_week',
        'class_date',
        'start_time',
        'end_time',
        'room',
        'building',
        'capacity',
    ];

    protected $casts = [
        'class_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }

    public function subject()
    {
        return $this->hasOneThrough(
            Subject::class,
            TeacherSubject::class,
            'id',
            'id',
            'teacher_subject_id',
            'subject_id'
        );
    }

    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Subject::class,
            'id',
            'id',
            'teacher_subject_id',
            'course_id'
        );
    }

    public function teacher()
    {
        return $this->hasOneThrough(
            User::class,
            TeacherSubject::class,
            'id',
            'id',
            'teacher_subject_id',
            'teacher_id'
        );
    }
}
