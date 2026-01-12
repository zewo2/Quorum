<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeCourses = Course::where('status', 'active')->count();
        $totalEnrollments = Enrollment::where('status', 'active')->count();
        $departmentCount = Course::distinct('department')->count('department');

        return view('dashboards.admin.index', compact(
            'totalUsers',
            'activeCourses',
            'totalEnrollments',
            'departmentCount'
        ));
    }

    public function users()
    {
        return view('dashboards.admin.users');
    }

    public function courses(Request $request)
    {
        $query = Course::withCount(['enrollments', 'subjects']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $courses = $query->orderBy('name')->paginate(10)->withQueryString();

        // Get unique departments for filter
        $departments = Course::select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Stats
        $totalCourses = Course::count();
        $activeCourses = Course::where('status', 'active')->count();
        $totalEnrollments = Enrollment::where('status', 'active')->count();
        $departmentCount = Course::distinct('department')->count('department');

        return view('dashboards.admin.courses', compact(
            'courses',
            'departments',
            'totalCourses',
            'activeCourses',
            'totalEnrollments',
            'departmentCount'
        ));
    }

    public function timetables()
    {
        return view('dashboards.admin.timetables');
    }
}
