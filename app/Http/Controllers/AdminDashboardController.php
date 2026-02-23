<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeCourses = Course::where('status', 'active')->count();
        $totalEnrollments = Enrollment::where('status', 'active')->count();
        $departmentCount = Course::distinct('department')->count('department');

        // Get recent activity (last 4 items)
        $recentActivity = UserActivity::with('user', 'performer')
            ->latest('created_at')
            ->limit(4)
            ->get();

        // System health checks
        $systemStatus = [
            'database' => $this->checkDatabaseConnection(),
            'storage' => $this->checkStorageSpace(),
        ];

        return view('dashboards.admin.index', compact(
            'totalUsers',
            'activeCourses',
            'totalEnrollments',
            'departmentCount',
            'recentActivity',
            'systemStatus'
        ));
    }

    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'healthy',
                'label' => 'Healthy',
                'percentage' => 100,
                'color' => 'success',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'label' => 'Error',
                'percentage' => 0,
                'color' => 'danger',
            ];
        }
    }

    private function checkStorageSpace(): array
    {
        $diskSpace = disk_free_space(storage_path());
        $diskTotal = disk_total_space(storage_path());
        $percentage = round((($diskTotal - $diskSpace) / $diskTotal) * 100);

        return [
            'status' => $percentage > 85 ? 'warning' : 'healthy',
            'label' => $percentage . '% Used',
            'percentage' => $percentage,
            'color' => $percentage > 85 ? 'warning' : 'success',
        ];
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
