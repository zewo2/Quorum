<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentApiController extends Controller
{
    /**
     * Get all departments or a specific department by name/id
     */
    public function index(Request $request): JsonResponse
    {
        $id = $request->query('id');

        // Get unique departments with course count
        $departmentsQuery = Course::selectRaw('department, COUNT(*) as course_count')
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('department', 'asc');

        if ($id) {
            $departments = $departmentsQuery->where('department', $id)->get();

            if ($departments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Department retrieved successfully',
                'data' => $departments->first(),
                'courses' => Course::where('department', $id)->get()
            ]);
        }

        $departments = $departmentsQuery->get();

        return response()->json([
            'success' => true,
            'message' => 'Departments retrieved successfully',
            'data' => $departments,
            'total' => $departments->count()
        ]);
    }

    /**
     * Get a specific department by name (route parameter)
     */
    public function show(string $id): JsonResponse
    {
        $department = Course::selectRaw('department, COUNT(*) as course_count')
            ->where('department', $id)
            ->whereNotNull('department')
            ->groupBy('department')
            ->first();

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found',
                'data' => null
            ], 404);
        }

        $courses = Course::where('department', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Department retrieved successfully',
            'data' => $department,
            'courses' => $courses,
            'course_count' => $courses->count()
        ]);
    }
}
