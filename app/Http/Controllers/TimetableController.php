<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Timetable;
use App\Models\TeacherSubject;
use App\Models\Subject;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimetableController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.timetables.index');
    }

    public function create(): View
    {
        $teacherSubjects = TeacherSubject::with('teacher', 'subject')
            ->where('status', 'active')
            ->get()
            ->groupBy(function ($ts) {
                return $ts->teacher->name;
            });

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $rooms = Room::orderBy('code')->get(['code', 'building']);

        return view('admin.timetables.create', compact('teacherSubjects', 'daysOfWeek', 'rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_subject_id' => 'required|exists:teacher_subjects,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|exists:rooms,code',
            'building' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1|max:500',
        ]);

        $conflict = Timetable::where('teacher_subject_id', $validated['teacher_subject_id'])
            ->where('day_of_week', $validated['day_of_week'])
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
                ->withErrors(['start_time' => 'This time slot conflicts with an existing class for this teacher.']);
        }

        try {
            Timetable::create($validated);
            return redirect()->route('dashboard.admin.timetables.index')
                ->with('success', 'Timetable entry created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create timetable entry. Please check for conflicts.']);
        }
    }

    public function edit(Timetable $timetable): View
    {
        $teacherSubjects = TeacherSubject::with('teacher', 'subject')
            ->where('status', 'active')
            ->get()
            ->groupBy(function ($ts) {
                return $ts->teacher->name;
            });

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $rooms = Room::orderBy('code')->get(['code', 'building']);

        return view('admin.timetables.edit', compact('timetable', 'teacherSubjects', 'daysOfWeek', 'rooms'));
    }

    public function update(Request $request, Timetable $timetable): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_subject_id' => 'required|exists:teacher_subjects,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|exists:rooms,code',
            'building' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1|max:500',
        ]);

        $conflict = Timetable::where('teacher_subject_id', $validated['teacher_subject_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $timetable->id)
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
                ->withErrors(['start_time' => 'This time slot conflicts with an existing class for this teacher.']);
        }

        try {
            $timetable->update($validated);
            return redirect()->route('dashboard.admin.timetables.index')
                ->with('success', 'Timetable entry updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update timetable entry. Please check for conflicts.']);
        }
    }

    public function destroy(Timetable $timetable): RedirectResponse
    {
        $timetable->delete();

        return redirect()->route('admin.timetables.index')
            ->with('success', 'Timetable entry deleted successfully!');
    }

    public function byTeacher(int $teacherId): \Illuminate\Http\JsonResponse
    {
        $timetables = Timetable::with('teacherSubject.subject')
            ->whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($timetables);
    }

    public function byStudent(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $timetables = Timetable::with('teacherSubject.subject', 'teacherSubject.teacher')
            ->whereHas('teacherSubject.subject', function ($query) use ($user) {
                $query->whereHas('courses', function ($q) use ($user) {
                    $q->whereHas('enrollments', function ($e) use ($user) {
                        $e->where('student_id', $user->id);
                    });
                });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return response()->json($timetables);
    }
}
