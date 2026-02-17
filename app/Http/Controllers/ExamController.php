<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Room;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Exam::with('subject.course', 'subject.courses');

        if ($request->filled('course')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->whereHas('courses', function ($courseQuery) use ($request) {
                    $courseQuery->where('courses.id', $request->course);
                });
            });
        }

        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }

        if ($request->filled('room')) {
            $query->where('room', $request->room);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('exam_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('exam_date', '<=', $request->date_to);
        }

        $exams = $query->orderBy('exam_date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        $courses = Course::orderBy('name')->get(['id', 'name']);
        $subjects = Subject::with('courses')->orderBy('name')->get(['id', 'name', 'course_id']);
        $rooms = Room::orderBy('code')->get(['code', 'building', 'capacity']);

        return view('admin.exams.index', compact('exams', 'courses', 'subjects', 'rooms'));
    }

    public function create(): View
    {
        $subjects = Subject::with('course', 'courses')->orderBy('name')->get();
        $rooms = Room::orderBy('code')->get(['code', 'building']);

        return view('admin.exams.create', compact('subjects', 'rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|exists:rooms,code',
        ]);

        $conflict = Exam::where('subject_id', $validated['subject_id'])
            ->where('exam_date', $validated['exam_date'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })
                ->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })
                ->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start_time' => 'This exam time conflicts with another exam for the same subject.']);
        }

        Exam::create($validated);

        return redirect()->route('dashboard.admin.exams.index')
            ->with('success', 'Exam created successfully!');
    }

    public function edit(Exam $exam): View
    {
        $subjects = Subject::with('course', 'courses')->orderBy('name')->get();
        $rooms = Room::orderBy('code')->get(['code', 'building']);

        return view('admin.exams.edit', compact('exam', 'subjects', 'rooms'));
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|exists:rooms,code',
        ]);

        $conflict = Exam::where('subject_id', $validated['subject_id'])
            ->where('exam_date', $validated['exam_date'])
            ->where('id', '!=', $exam->id)
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })
                ->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                })
                ->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '>=', $validated['start_time'])
                      ->where('end_time', '<=', $validated['end_time']);
                });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start_time' => 'This exam time conflicts with another exam for the same subject.']);
        }

        $exam->update($validated);

        return redirect()->route('dashboard.admin.exams.index')
            ->with('success', 'Exam updated successfully!');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()->route('dashboard.admin.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }
}
