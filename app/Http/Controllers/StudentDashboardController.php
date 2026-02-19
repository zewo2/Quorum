<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();

        $enrolledCourses = $user->enrollments()->with('course.subjects')->get();
        $courseCount = $enrolledCourses->count();

        $enrolledClassesCount = $enrolledCourses
            ->flatMap(function ($enrollment) {
                return $enrollment->course?->subjects ?? collect();
            })
            ->unique('id')
            ->count();

        $totalCredits = $enrolledCourses->sum(function ($enrollment) {
            return $enrollment->course->credits ?? 0;
        });

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->final_grade !== null);
        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('final_grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->final_grade / 20) * 4.0;
            }), 2)
            : 0;

        $activeEnrollments = $enrolledCourses->filter(fn($e) => $e->status === 'active')->count();
        $enrollmentIds = $enrolledCourses->pluck('id')->all();

        $attendanceRecords = empty($enrollmentIds)
            ? collect()
            : Attendance::whereIn('enrollment_id', $enrollmentIds)->get();

        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $lateCount = $attendanceRecords->where('status', 'late')->count();
        $totalSessions = $attendanceRecords->count();
        $attendanceRate = $totalSessions > 0
            ? round((($presentCount + $lateCount) / $totalSessions) * 100, 1)
            : 0;

        $courseIds = $enrolledCourses->pluck('course_id')->toArray();
        $todayDay = now()->format('l');

        $todaySchedule = Timetable::with('teacherSubject.subject', 'teacherSubject.teacher')
            ->where('day_of_week', $todayDay)
            ->whereHas('teacherSubject', function($query) use ($courseIds) {
                $query->whereHas('subject', function ($subjectQuery) use ($courseIds) {
                    $subjectQuery->whereHas('courses', function ($courseQuery) use ($courseIds) {
                        $courseQuery->whereIn('courses.id', $courseIds);
                    });
                });
            })
            ->orderBy('start_time')
            ->take(3)
            ->get();

        return view('dashboards.student.index', [
            'courseCount' => $courseCount,
            'enrolledClassesCount' => $enrolledClassesCount,
            'totalCredits' => $totalCredits,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa,
            'activeEnrollments' => $activeEnrollments,
            'attendanceRate' => $attendanceRate,
            'totalSessions' => $totalSessions,
            'enrolledCourses' => $enrolledCourses,
            'todaySchedule' => $todaySchedule
        ]);
    }

    public function schedule(): \Illuminate\View\View
    {
        return view('dashboards.student.schedule');
    }

    public function subjects(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()->with('course.subjects')->get();

        $currentEnrollment = $enrolledCourses->firstWhere('status', 'active') ?? $enrolledCourses->first();
        $currentCourse = $currentEnrollment?->course;

        $enrolledSubjects = $enrolledCourses
            ->flatMap(function ($enrollment) {
                return $enrollment->course?->subjects ?? collect();
            })
            ->unique('id')
            ->values();

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->final_grade !== null);
        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('final_grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->final_grade / 20) * 4.0;
            }), 2)
            : 0;

        return view('dashboards.student.subjects', [
            'enrolledCourses' => $enrolledCourses,
            'enrolledSubjects' => $enrolledSubjects,
            'currentCourse' => $currentCourse,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa
        ]);
    }

    public function grades(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()->with('course')->get();

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->final_grade !== null);

        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('final_grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->final_grade / 20) * 4.0;
            }), 2)
            : 0;

        $highestGrade = $enrollmentsWithGrades->count() > 0
            ? $enrollmentsWithGrades->max('final_grade')
            : 0;

        $lowestGrade = $enrollmentsWithGrades->count() > 0
            ? $enrollmentsWithGrades->min('final_grade')
            : 0;

        return view('dashboards.student.grades', [
            'enrolledCourses' => $enrolledCourses,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa,
            'highestGrade' => $highestGrade,
            'lowestGrade' => $lowestGrade
        ]);
    }

    public function attendance(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();

        $enrollments = $user->enrollments()->with('course')->get();
        $enrollmentIds = $enrollments->pluck('id')->all();

        $attendanceRecords = empty($enrollmentIds)
            ? collect()
            : Attendance::with('enrollment.course', 'teacher')
                ->whereIn('enrollment_id', $enrollmentIds)
                ->orderByDesc('date')
                ->orderByDesc('created_at')
                ->get();

        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $lateCount = $attendanceRecords->where('status', 'late')->count();
        $absentCount = $attendanceRecords->where('status', 'absent')->count();
        $totalSessions = $attendanceRecords->count();
        $attendanceRate = $totalSessions > 0
            ? round((($presentCount + $lateCount) / $totalSessions) * 100, 1)
            : 0;

        return view('dashboards.student.attendance', [
            'attendanceRecords' => $attendanceRecords,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'totalSessions' => $totalSessions,
            'attendanceRate' => $attendanceRate,
        ]);
    }

    public function exams(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();

        // Get enrolled subjects with all their exams
        $enrolledSubjects = $user->enrollments()
            ->with([
                'course.subjects' => function ($query) {
                    $query->with(['exams' => function ($examQuery) {
                        $examQuery->orderBy('exam_date')->orderBy('start_time');
                    }]);
                }
            ])
            ->get()
            ->pluck('course.subjects')
            ->flatten()
            ->unique('id')
            ->values();

        // Extract upcoming exams from enrolled subjects
        $upcomingExams = $enrolledSubjects
            ->pluck('exams')
            ->flatten()
            ->where('exam_date', '>=', now()->toDateString())
            ->sortBy(function ($exam) {
                return $exam->exam_date->timestamp;
            })
            ->values();

        return view('dashboards.student.exams', [
            'enrolledSubjects' => $enrolledSubjects,
            'upcomingExams' => $upcomingExams,
        ]);
    }
}
