<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects.
     */
    public function index(Request $request)
    {
        $query = Subject::with('course');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Course filter
        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

        $subjects = $query->orderBy('name')->paginate(15)->withQueryString();

        // Get all courses for filter dropdown
        $courses = Course::select('id', 'name', 'code')->orderBy('name')->get();

        // Stats
        $totalSubjects = Subject::count();
        $totalCredits = Subject::sum('credits');
        $courseCount = Course::count();

        return view('dashboards.admin.subjects.index', compact(
            'subjects',
            'courses',
            'totalSubjects',
            'totalCredits',
            'courseCount'
        ));
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
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        Subject::create($validated);

        return redirect()
            ->route('dashboard.admin.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
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
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $subject->update($validated);

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
