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

    public function courses()
    {
        return view('dashboards.admin.courses');
    }

    public function timetables()
    {
        return view('dashboards.admin.timetables');
    }
}
