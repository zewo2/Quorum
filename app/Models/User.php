<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'date_of_birth',
        'nif',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login' => 'datetime',
        ];
    }

    /**
     * Get user activities
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get performed activities
     */
    public function performedActivities()
    {
        return $this->hasMany(UserActivity::class, 'performed_by');
    }

    /**
     * Student enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Courses this student is enrolled in
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
            ->withPivot('status', 'final_grade', 'notes')
            ->withTimestamps();
    }

    /**
     * Subjects this teacher is assigned to
     */
    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, 'teacher_id');
    }

    /**
     * Subjects taught by this teacher (direct relation)
     */
    public function subjectsTaught()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id')
            ->withPivot('academic_year', 'semester', 'class_capacity', 'status')
            ->withTimestamps();
    }
}
