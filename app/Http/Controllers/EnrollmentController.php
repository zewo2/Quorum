<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'course']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function ($courseQuery) use ($search) {
                    $courseQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->get('course_id'));
        }

        $enrollments = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $courses = Course::orderBy('name')->get();
        $statuses = ['active', 'completed', 'withdrawn'];

        return view('dashboards.admin.enrollments.index', compact('enrollments', 'courses', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $statuses = ['active', 'completed', 'withdrawn'];

        return view('dashboards.admin.enrollments.create', compact('students', 'courses', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'student')),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'status' => ['required', Rule::in(['active', 'completed', 'withdrawn'])],
            'final_grade' => ['nullable', 'numeric', 'between:0,20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->validate([
            'student_id' => [
                Rule::unique('enrollments')->where(fn ($query) => $query
                    ->where('student_id', $request->student_id)
                    ->where('course_id', $request->course_id))
            ],
        ], [
            'student_id.unique' => 'This student is already enrolled in the selected course.',
        ]);

        Enrollment::create($validated);

        return redirect()
            ->route('dashboard.admin.enrollments.index')
            ->with('success', 'Enrollment created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $statuses = ['active', 'completed', 'withdrawn'];

        return view('dashboards.admin.enrollments.edit', compact('enrollment', 'students', 'courses', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'student')),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'status' => ['required', Rule::in(['active', 'completed', 'withdrawn'])],
            'final_grade' => ['nullable', 'numeric', 'between:0,20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->validate([
            'student_id' => [
                Rule::unique('enrollments')
                    ->where(fn ($query) => $query
                        ->where('student_id', $request->student_id)
                        ->where('course_id', $request->course_id))
                    ->ignore($enrollment->id),
            ],
        ], [
            'student_id.unique' => 'This student is already enrolled in the selected course.',
        ]);

        $enrollment->update($validated);

        return redirect()
            ->route('dashboard.admin.enrollments.index')
            ->with('success', 'Enrollment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()
            ->route('dashboard.admin.enrollments.index')
            ->with('success', 'Enrollment removed');
    }
}
