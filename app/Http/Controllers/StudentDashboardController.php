<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return view('dashboards.student.index', [
            'courseCount' => $courseCount,
            'totalCredits' => $totalCredits,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa,
            'activeEnrollments' => $activeEnrollments,
            'enrolledCourses' => $enrolledCourses
        ]);
    }

    public function schedule(): \Illuminate\View\View
    {
        /** @var User $user */
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()->with('course')->get();

        return view('dashboards.student.schedule', [
            'enrolledCourses' => $enrolledCourses
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
