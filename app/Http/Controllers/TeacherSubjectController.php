<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TeacherSubject::with(['teacher', 'subject']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('teacher', function ($teacherQuery) use ($search) {
                    $teacherQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('subject', function ($subjectQuery) use ($search) {
                    $subjectQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->get('academic_year'));
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->get('teacher_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->get('subject_id'));
        }

        $assignments = $query
            ->orderByDesc('academic_year')
            ->orderBy('semester')
            ->paginate(15)
            ->withQueryString();

        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $statuses = ['active', 'inactive'];

        return view('dashboards.admin.teacher-subjects.index', compact('assignments', 'subjects', 'teachers', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $statuses = ['active', 'inactive'];

        return view('dashboards.admin.teacher-subjects.create', compact('teachers', 'subjects', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'teacher')),
            ],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'semester' => ['required', 'integer', Rule::in([1, 2])],
            'class_capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $request->validate([
            'teacher_id' => [
                Rule::unique('teacher_subjects')->where(fn ($query) => $query
                    ->where('teacher_id', $request->teacher_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('academic_year', $request->academic_year)
                    ->where('semester', $request->semester))
            ],
        ], [
            'teacher_id.unique' => 'This teacher is already assigned to the subject for the selected term.',
        ]);

        TeacherSubject::create($validated);

        return redirect()
            ->route('dashboard.admin.teacher-subjects.index')
            ->with('success', 'Subject assigned to teacher');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherSubject $teacherSubject)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $statuses = ['active', 'inactive'];

        return view('dashboards.admin.teacher-subjects.edit', compact('teacherSubject', 'teachers', 'subjects', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherSubject $teacherSubject)
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'teacher')),
            ],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'semester' => ['required', 'integer', Rule::in([1, 2])],
            'class_capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $request->validate([
            'teacher_id' => [
                Rule::unique('teacher_subjects')
                    ->where(fn ($query) => $query
                        ->where('teacher_id', $request->teacher_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('academic_year', $request->academic_year)
                        ->where('semester', $request->semester))
                    ->ignore($teacherSubject->id),
            ],
        ], [
            'teacher_id.unique' => 'This teacher is already assigned to the subject for the selected term.',
        ]);

        $teacherSubject->update($validated);

        return redirect()
            ->route('dashboard.admin.teacher-subjects.index')
            ->with('success', 'Assignment updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherSubject $teacherSubject)
    {
        $teacherSubject->delete();

        return redirect()
            ->route('dashboard.admin.teacher-subjects.index')
            ->with('success', 'Assignment removed');
    }
}
