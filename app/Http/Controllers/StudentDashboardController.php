<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();

        $enrolledCourses = $user->enrollments()->with('course')->get();
        $courseCount = $enrolledCourses->count();

        $totalCredits = $enrolledCourses->sum(function ($enrollment) {
            return $enrollment->course->credits ?? 0;
        });

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->grade !== null);
        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->grade / 20) * 4.0;
            }), 2)
            : 0;

        $activeEnrollments = $enrolledCourses->filter(fn($e) => $e->status === 'active')->count();

        $courseIds = $enrolledCourses->pluck('course_id')->toArray();
        $todayDay = now()->format('l');

        $todaySchedule = Timetable::with('teacherSubject.subject', 'teacherSubject.teacher')
            ->where('day_of_week', $todayDay)
            ->whereHas('teacherSubject', function($query) use ($courseIds) {
                $query->whereIn('subject_id', function($subQuery) use ($courseIds) {
                    $subQuery->select('subject_id')
                        ->from('subjects')
                        ->whereIn('course_id', $courseIds);
                });
            })
            ->orderBy('start_time')
            ->take(3)
            ->get();

        return view('dashboards.student.index', [
            'courseCount' => $courseCount,
            'totalCredits' => $totalCredits,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa,
            'activeEnrollments' => $activeEnrollments,
            'enrolledCourses' => $enrolledCourses,
            'todaySchedule' => $todaySchedule
        ]);
    }

    public function schedule(Request $request): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();

        $statusFilter = $request->input('status', 'all');
        $viewMode = $request->input('view', 'detailed');

        $enrollmentsQuery = $user->enrollments()->with('course');
        if ($statusFilter === 'active') {
            $enrollmentsQuery->where('status', 'active');
        }

        $enrolledCourses = $enrollmentsQuery->get();
        $courseIds = $enrolledCourses->pluck('course_id')->toArray();

        $timetables = Timetable::with('teacherSubject.subject', 'teacherSubject.teacher')
            ->whereHas('teacherSubject', function($query) use ($courseIds) {
                $query->whereIn('subject_id', function($subQuery) use ($courseIds) {
                    $subQuery->select('subject_id')
                        ->from('subjects')
                        ->whereIn('course_id', $courseIds);
                });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('dashboards.student.schedule', [
            'enrolledCourses' => $enrolledCourses,
            'timetables' => $timetables,
            'viewMode' => $viewMode,
        ]);
    }

    public function subjects(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()->with('course')->get();

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->grade !== null);
        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->grade / 20) * 4.0;
            }), 2)
            : 0;

        return view('dashboards.student.subjects', [
            'enrolledCourses' => $enrolledCourses,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa
        ]);
    }

    public function grades(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()->with('course')->get();

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->grade !== null);

        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->grade / 20) * 4.0;
            }), 2)
            : 0;

        $highestGrade = $enrollmentsWithGrades->count() > 0
            ? $enrollmentsWithGrades->max('grade')
            : 0;

        $lowestGrade = $enrollmentsWithGrades->count() > 0
            ? $enrollmentsWithGrades->min('grade')
            : 0;

        return view('dashboards.student.grades', [
            'enrolledCourses' => $enrolledCourses,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa,
            'highestGrade' => $highestGrade,
            'lowestGrade' => $lowestGrade
        ]);
    }

    public function exams(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()->with('course')->get();

        return view('dashboards.student.exams', [
            'enrolledCourses' => $enrolledCourses
        ]);
    }
}
