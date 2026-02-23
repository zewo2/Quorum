<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    /**
     * Get all courses or a specific course by ID
     */
    public function index(Request $request): JsonResponse
    {
        $id = $request->query('id');

        if ($id) {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Course retrieved successfully',
                'data' => $course
            ]);
        }

        $courses = Course::all();

        return response()->json([
            'success' => true,
            'message' => 'Courses retrieved successfully',
            'data' => $courses,
            'total' => $courses->count()
        ]);
    }

    /**
     * Get a specific course by ID (route parameter)
     */
    public function show(int $id): JsonResponse
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course retrieved successfully',
            'data' => $course
        ]);
    }
}
