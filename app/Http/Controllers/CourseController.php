<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('dashboards.admin.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:courses,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'total_years' => ['required', 'integer', 'min:2', 'max:4'],
            'department' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Course::create($validated);

        return redirect()
            ->route('dashboard.admin.courses')
            ->with('success', 'Course created successfully!');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('dashboards.admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:courses,code,' . $course->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'total_years' => ['required', 'integer', 'min:2', 'max:4'],
            'department' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $course->update($validated);

        return redirect()
            ->route('dashboard.admin.courses')
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Check if course has enrollments
        if ($course->enrollments()->exists()) {
            return redirect()
                ->route('dashboard.admin.courses')
                ->with('error', 'Cannot delete a course with active enrollments. Please unenroll all students first.');
        }

        $course->delete();

        return redirect()
            ->route('dashboard.admin.courses')
            ->with('success', 'Course deleted successfully!');
    }
}
