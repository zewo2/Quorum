<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return view('dashboards.admin.subjects.index');
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        $courses = Course::orderBy('name')->get();
        return view('dashboards.admin.subjects.create', compact('courses'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'credits' => ['required', 'integer', 'min:1', 'max:20'],
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $subject = Subject::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'credits' => $validated['credits'],
            'course_id' => $validated['course_ids'][0],
        ]);

        $subject->courses()->sync($validated['course_ids']);

        return redirect()
            ->route('dashboard.admin.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        $subject->load('courses');
        $courses = Course::orderBy('name')->get();
        return view('dashboards.admin.subjects.edit', compact('subject', 'courses'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:subjects,code,' . $subject->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'credits' => ['required', 'integer', 'min:1', 'max:20'],
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $subject->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'credits' => $validated['credits'],
            'course_id' => $validated['course_ids'][0],
        ]);

        $subject->courses()->sync($validated['course_ids']);

        return redirect()
            ->route('dashboard.admin.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        // Check if subject has teacher assignments
        if ($subject->teacherSubjects()->exists()) {
            return redirect()
                ->route('dashboard.admin.subjects.index')
                ->with('error', 'Cannot delete a subject with assigned teachers. Please remove all assignments first.');
        }

        $subject->delete();

        return redirect()
            ->route('dashboard.admin.subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }
}
