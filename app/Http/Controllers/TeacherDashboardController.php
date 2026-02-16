<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeacherDashboardController extends Controller
{
    /**
     * Show the teacher dashboard index
     * @return View
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $teacherSubjects = $user->subjectsTaught()
            ->with('course.enrollments')
            ->get();

        $classCount = $teacherSubjects->count();
        $totalStudents = $teacherSubjects->sum(function ($subject) {
            return $subject->course?->enrollments->count() ?? 0;
        });

        $attendanceStats = Attendance::where('teacher_id', $user->id)
            ->selectRaw("SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count")
            ->selectRaw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count")
            ->selectRaw('COUNT(*) as total_count')
            ->first();

        $attendedCount = ($attendanceStats->present_count ?? 0) + ($attendanceStats->late_count ?? 0);
        $totalCount = $attendanceStats->total_count ?? 0;
        $attendanceRate = $totalCount > 0
            ? (int) round(($attendedCount / $totalCount) * 100)
            : 0;

        $todayDay = now()->format('l');
        $todaySchedule = Timetable::whereHas('teacherSubject', function($query) use ($user) {
            $query->where('teacher_id', $user->id);
        })
            ->where('day_of_week', $todayDay)
            ->with('teacherSubject.subject')
            ->orderBy('start_time')
            ->get();

        return view('dashboards.teacher.index', [
            'classCount' => $classCount,
            'totalStudents' => $totalStudents,
            'attendanceRate' => $attendanceRate,
            'todaySchedule' => $todaySchedule,
            'teacherSubjects' => $teacherSubjects,
        ]);
    }

    /**
     * Show all classes taught by this teacher
     * @return View
     */
    public function classes(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $teacherSubjects = $user->subjectsTaught()
            ->with('course.enrollments')
            ->get();

        return view('dashboards.teacher.classes', [
            'teacherSubjects' => $teacherSubjects,
        ]);
    }

    public function schedule(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $timetables = Timetable::with('teacherSubject.subject')
            ->whereHas('teacherSubject', function($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $groupedByDay = $timetables->groupBy('day_of_week');

        return view('dashboards.teacher.schedule', [
            'timetables' => $timetables,
            'groupedByDay' => $groupedByDay,
        ]);
    }

    /**
     * Show attendance management
     * @return View
     */
    public function attendance(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $date = $request->input('date', now()->format('Y-m-d'));
        $session = $request->input('session', 'session_1');

        $teacherSubjects = $user->subjectsTaught()
            ->with('course')
            ->get();

        $subjectId = $request->input('subject', $teacherSubjects->first()?->id);
        $selectedSubject = $teacherSubjects->firstWhere('id', $subjectId);

        $enrollments = collect();
        if ($selectedSubject && $selectedSubject->course) {
            $enrollments = $selectedSubject->course->enrollments()
                ->with(['user', 'attendances' => function($query) use ($date, $session) {
                    $query->where('date', $date)
                          ->where('session', $session);
                }])
                ->get();
        }

        $presentCount = $enrollments->filter(function($enrollment) {
            return $enrollment->attendances->isNotEmpty() &&
                   $enrollment->attendances->first()->status === 'present';
        })->count();

        $lateCount = $enrollments->filter(function($enrollment) {
            return $enrollment->attendances->isNotEmpty() &&
                   $enrollment->attendances->first()->status === 'late';
        })->count();

        $absentCount = $enrollments->filter(function($enrollment) {
            return $enrollment->attendances->isEmpty() ||
                   $enrollment->attendances->first()->status === 'absent';
        })->count();

        return view('dashboards.teacher.attendance', [
            'teacherSubjects' => $teacherSubjects,
            'selectedSubject' => $selectedSubject,
            'enrollments' => $enrollments,
            'date' => $date,
            'session' => $session,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
        ]);
    }

    /**
     * Store attendance records
     * @return RedirectResponse
     */
    public function storeAttendance(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'date' => 'required|date',
            'session' => 'required|string',
            'subject' => 'required|exists:subjects,id',
            'attendance' => 'required|array',
            'attendance.*.enrollment_id' => 'required|exists:enrollments,id',
            'attendance.*.status' => 'required|in:present,late,absent',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        foreach ($validated['attendance'] as $record) {
            Attendance::updateOrCreate(
                [
                    'enrollment_id' => $record['enrollment_id'],
                    'date' => $validated['date'],
                    'session' => $validated['session'],
                ],
                [
                    'teacher_id' => $user->id,
                    'status' => $record['status'],
                    'notes' => $record['notes'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('dashboard.teacher.attendance', [
                'subject' => $validated['subject'],
                'date' => $validated['date'],
                'session' => $validated['session'],
            ])
            ->with('success', 'Attendance saved successfully!');
    }
}
