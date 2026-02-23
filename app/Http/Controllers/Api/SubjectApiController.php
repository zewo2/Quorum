<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectApiController extends Controller
{
    /**
     * Get all subjects or a specific subject by ID
     */
    public function index(Request $request): JsonResponse
    {
        $id = $request->query('id');

        if ($id) {
            $subject = Subject::with('course', 'exams')->find($id);

            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subject retrieved successfully',
                'data' => $subject
            ]);
        }

        $subjects = Subject::with('course', 'exams')->get();

        return response()->json([
            'success' => true,
            'message' => 'Subjects retrieved successfully',
            'data' => $subjects,
            'total' => $subjects->count()
        ]);
    }

    /**
     * Get a specific subject by ID (route parameter)
     */
    public function show(int $id): JsonResponse
    {
        $subject = Subject::with('course', 'exams')->find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subject retrieved successfully',
            'data' => $subject
        ]);
    }
}
