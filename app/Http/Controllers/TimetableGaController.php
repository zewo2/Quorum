<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTimetableGaJob;
use App\Models\Course;
use App\Models\GaRun;
use App\Models\Room;
use App\Models\TeacherSubject;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimetableGaController extends Controller
{
    private array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    private array $slots = [
        ['09:00', '11:00'],
        ['11:00', '13:00'],
        ['14:00', '16:00'],
        ['16:00', '18:00'],
    ];

    public function index(Request $request): View
    {
        $preview = [];
        $stats = [];

        $currentRunId = $request->session()->get('ga.current_run_id');
        $gaRun = $currentRunId ? GaRun::find($currentRunId) : null;

        if ($gaRun && $gaRun->status === 'completed') {
            $preview = $gaRun->result_payload['preview'] ?? [];
            $stats = $gaRun->result_payload['stats'] ?? [];
        } else {
            $preview = $request->session()->get('ga.timetables', []);
            $stats = $request->session()->get('ga.stats', []);
        }

        $selectedCourseIds = $request->session()->get('ga.selected_course_ids', []);
        $selectedTeacherSubjectIds = $request->session()->get('ga.selected_teacher_subject_ids', []);
        $selectedMonth = $request->session()->get('ga.selected_month', now()->format('Y-m'));
        $selectedMode = $request->session()->get('ga.selected_mode', 'normal');
        $selectedYear = $request->session()->get('ga.selected_year');
        $selectedSemester = $request->session()->get('ga.selected_semester');

        $courses = Course::orderBy('name')->get(['id', 'name', 'code']);

        $teacherSubjects = TeacherSubject::with('teacher', 'subject.course', 'subject.courses')
            ->where('status', 'active')
            ->get();

        $hoursMeta = $this->buildHoursMeta($teacherSubjects);

        return view('admin.timetables.ga', compact(
            'preview',
            'stats',
            'courses',
            'teacherSubjects',
            'hoursMeta',
            'selectedCourseIds',
            'selectedTeacherSubjectIds',
            'selectedMonth',
            'selectedMode',
            'gaRun',
            'selectedYear',
            'selectedSemester'
        ));
    }

    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'mode' => ['required', 'in:relaxed,normal,emergency'],
            'selected_course_ids' => ['required', 'array', 'min:1'],
            'selected_course_ids.*' => ['integer', 'exists:courses,id'],
            'selected_year' => ['nullable', 'integer', 'min:1', 'max:4'],
            'selected_semester' => ['nullable', 'integer', 'in:1,2'],
            'selected_teacher_subject_ids' => ['nullable', 'array'],
            'selected_teacher_subject_ids.*' => ['integer', 'exists:teacher_subjects,id'],
        ]);

        $gaRun = GaRun::create([
            'user_id' => $request->user()?->id,
            'status' => 'queued',
            'progress' => 0,
            'input_payload' => [
                'month' => $validated['month'],
                'mode' => $validated['mode'],
                'selected_course_ids' => $validated['selected_course_ids'],
                'selected_year' => $validated['selected_year'] ?? null,
                'selected_semester' => $validated['selected_semester'] ?? null,
                'selected_teacher_subject_ids' => $validated['selected_teacher_subject_ids'] ?? [],
            ],
        ]);

        GenerateTimetableGaJob::dispatch($gaRun->id)->onQueue('default');

        $request->session()->forget('ga.timetables');
        $request->session()->forget('ga.stats');
        $request->session()->put('ga.current_run_id', $gaRun->id);
        $request->session()->put('ga.selected_course_ids', $validated['selected_course_ids']);
        $request->session()->put('ga.selected_teacher_subject_ids', $validated['selected_teacher_subject_ids'] ?? []);
        $request->session()->put('ga.selected_month', $validated['month']);
        $request->session()->put('ga.selected_mode', $validated['mode']);
        $request->session()->put('ga.selected_year', $validated['selected_year'] ?? null);
        $request->session()->put('ga.selected_semester', $validated['selected_semester'] ?? null);

        return redirect()->route('dashboard.admin.timetables.ga')
            ->with('success', 'Schedule generation queued. Please wait while we process your request.');
    }

    public function status(Request $request): JsonResponse
    {
        $currentRunId = $request->session()->get('ga.current_run_id');
        if (!$currentRunId) {
            return response()->json([
                'status' => 'idle',
                'progress' => 0,
            ]);
        }

        $gaRun = GaRun::find($currentRunId);
        if (!$gaRun) {
            return response()->json([
                'status' => 'idle',
                'progress' => 0,
            ]);
        }

        return response()->json([
            'status' => $gaRun->status,
            'progress' => (int) $gaRun->progress,
            'error_message' => $gaRun->error_message,
            'generated' => count($gaRun->result_payload['preview'] ?? []),
            'run_id' => $gaRun->id,
        ]);
    }

    public function apply(Request $request): RedirectResponse
    {
        $preview = $request->session()->get('ga.timetables', []);
        $currentRunId = $request->session()->get('ga.current_run_id');
        if ($currentRunId) {
            $gaRun = GaRun::find($currentRunId);
            if ($gaRun && $gaRun->status === 'completed') {
                $preview = $gaRun->result_payload['preview'] ?? [];
            }
        }

        if (empty($preview)) {
            return redirect()->route('dashboard.admin.timetables.ga')
                ->withErrors(['ga' => 'No generated schedule to apply.']);
        }

        $teacherSubjects = TeacherSubject::with('teacher', 'subject.course', 'subject.courses')
            ->whereIn('id', collect($preview)->pluck('teacher_subject_id')->all())
            ->get()
            ->keyBy('id');

        $hoursMeta = $this->buildHoursMeta($teacherSubjects->values());
        $remainingHours = collect($hoursMeta)->pluck('remaining_hours', 'teacher_subject_id')->all();

        $existing = Timetable::with('teacherSubject')->get();

        $rooms = Room::get()->keyBy('code');

        $roomDateSlots = [];
        $teacherDateSlots = [];
        $courseDateSlots = [];
        $roomWeeklySlots = [];
        $teacherWeeklySlots = [];
        $courseWeeklySlots = [];

        foreach ($existing as $timetable) {
            $teacherId = $timetable->teacherSubject?->teacher_id;
            $timeKeys = $this->expandTimeKeys($timetable->start_time, $timetable->end_time);
            $courseIds = $this->extractCourseIdsFromTeacherSubject($timetable->teacherSubject);

            if ($timetable->class_date) {
                $dateKey = $this->normalizeDate($timetable->class_date);
                if ($teacherId) {
                    foreach ($timeKeys as $timeKey) {
                        $teacherDateSlots[$teacherId][$dateKey][$timeKey] = true;
                    }
                }
                foreach ($courseIds as $courseId) {
                    foreach ($timeKeys as $timeKey) {
                        $courseDateSlots[$courseId][$dateKey][$timeKey] = true;
                    }
                }
                if ($timetable->room) {
                    foreach ($timeKeys as $timeKey) {
                        $roomDateSlots[$timetable->room][$dateKey][$timeKey] = true;
                    }
                }
            } else {
                if ($teacherId) {
                    foreach ($timeKeys as $timeKey) {
                        $teacherWeeklySlots[$teacherId][$timetable->day_of_week][$timeKey] = true;
                    }
                }
                foreach ($courseIds as $courseId) {
                    foreach ($timeKeys as $timeKey) {
                        $courseWeeklySlots[$courseId][$timetable->day_of_week][$timeKey] = true;
                    }
                }
                if ($timetable->room) {
                    foreach ($timeKeys as $timeKey) {
                        $roomWeeklySlots[$timetable->room][$timetable->day_of_week][$timeKey] = true;
                    }
                }
            }
        }

        $applied = 0;
        $skipped = 0;
        $skippedMissingClass = 0;
        $skippedNoRoom = 0;
        $conflicts = 0;
        $roomConflicts = 0;
        $teacherConflicts = 0;
        $courseConflicts = 0;
        $hoursLimitReached = 0;

        foreach ($preview as $entry) {
            $teacherSubjectId = $entry['teacher_subject_id'];
            if (!isset($teacherSubjects[$teacherSubjectId])) {
                $skipped++;
                $skippedMissingClass++;
                continue;
            }

            if (!$entry['room']) {
                $skipped++;
                $skippedNoRoom++;
                continue;
            }

            $teacherId = $teacherSubjects[$teacherSubjectId]?->teacher_id;
            $courseIds = $this->extractCourseIdsFromTeacherSubject($teacherSubjects[$teacherSubjectId]);
            $entryTimeKeys = $this->expandTimeKeys($entry['start_time'], $entry['end_time']);
            $entryDate = $this->normalizeDate($entry['class_date']);
            $entryDay = Carbon::parse($entryDate)->format('l');
            $duration = $this->durationHours($entry['start_time'], $entry['end_time']);

            if (($remainingHours[$teacherSubjectId] ?? 0) < $duration) {
                $hoursLimitReached++;
                continue;
            }

            $roomConflict = false;
            foreach ($entryTimeKeys as $timeKey) {
                $roomConflict = $roomConflict
                    || ($roomDateSlots[$entry['room']][$entryDate][$timeKey] ?? false)
                    || ($roomWeeklySlots[$entry['room']][$entryDay][$timeKey] ?? false);
            }
            $teacherConflict = $teacherId
                ? collect($entryTimeKeys)->contains(function ($timeKey) use ($teacherDateSlots, $teacherWeeklySlots, $teacherId, $entryDate, $entryDay) {
                    return ($teacherDateSlots[$teacherId][$entryDate][$timeKey] ?? false)
                        || ($teacherWeeklySlots[$teacherId][$entryDay][$timeKey] ?? false);
                })
                : false;

            $courseConflict = false;
            foreach ($courseIds as $courseId) {
                foreach ($entryTimeKeys as $timeKey) {
                    $courseConflict = $courseConflict
                        || ($courseDateSlots[$courseId][$entryDate][$timeKey] ?? false)
                        || ($courseWeeklySlots[$courseId][$entryDay][$timeKey] ?? false);
                }
            }

            if ($roomConflict || $teacherConflict || $courseConflict) {
                $conflicts++;
                if ($roomConflict) {
                    $roomConflicts++;
                }
                if ($teacherConflict) {
                    $teacherConflicts++;
                }
                if ($courseConflict) {
                    $courseConflicts++;
                }
                continue;
            }

            $room = $rooms[$entry['room']] ?? null;

            Timetable::create([
                'teacher_subject_id' => $teacherSubjectId,
                'day_of_week' => $entryDay,
                'class_date' => $entryDate,
                'start_time' => $entry['start_time'],
                'end_time' => $entry['end_time'],
                'room' => $entry['room'],
                'building' => $room?->building,
                'capacity' => $room?->capacity,
            ]);

            foreach ($entryTimeKeys as $timeKey) {
                $roomDateSlots[$entry['room']][$entryDate][$timeKey] = true;
            }
            if ($teacherId) {
                foreach ($entryTimeKeys as $timeKey) {
                    $teacherDateSlots[$teacherId][$entryDate][$timeKey] = true;
                }
            }
            foreach ($courseIds as $courseId) {
                foreach ($entryTimeKeys as $timeKey) {
                    $courseDateSlots[$courseId][$entryDate][$timeKey] = true;
                }
            }

            $remainingHours[$teacherSubjectId] = max(0, ($remainingHours[$teacherSubjectId] ?? 0) - $duration);

            $applied++;
        }

        return redirect()->route('dashboard.admin.timetables.index')
            ->with('success', "GA applied: {$applied} created, {$skipped} skipped, {$conflicts} conflicts, {$hoursLimitReached} over hour limit.")
            ->with('ga_apply_breakdown', [
                'created' => $applied,
                'skipped_total' => $skipped,
                'skipped_missing_class' => $skippedMissingClass,
                'skipped_no_room' => $skippedNoRoom,
                'conflicts_total' => $conflicts,
                'conflicts_room' => $roomConflicts,
                'conflicts_teacher' => $teacherConflicts,
                'conflicts_course' => $courseConflicts,
                'over_legal_hour_limit' => $hoursLimitReached,
            ]);
    }

    private function buildHoursMeta(Collection $teacherSubjects): array
    {
        if ($teacherSubjects->isEmpty()) {
            return [];
        }

        $teacherSubjectIds = $teacherSubjects->pluck('id');
        $scheduledHoursByTeacherSubject = [];

        $timetables = Timetable::whereIn('teacher_subject_id', $teacherSubjectIds)->get(['teacher_subject_id', 'start_time', 'end_time']);
        foreach ($timetables as $timetable) {
            $duration = $this->durationHours($timetable->start_time, $timetable->end_time);
            $scheduledHoursByTeacherSubject[$timetable->teacher_subject_id] = ($scheduledHoursByTeacherSubject[$timetable->teacher_subject_id] ?? 0) + $duration;
        }

        $meta = [];
        foreach ($teacherSubjects as $teacherSubject) {
            $credits = (int) ($teacherSubject->subject?->credits ?? 0);
            $maxHours = $this->creditsToMaxHours($credits);
            $scheduled = $scheduledHoursByTeacherSubject[$teacherSubject->id] ?? 0;
            $remaining = max(0, $maxHours - $scheduled);

            $meta[$teacherSubject->id] = [
                'teacher_subject_id' => $teacherSubject->id,
                'max_hours' => $maxHours,
                'scheduled_hours' => $scheduled,
                'remaining_hours' => $remaining,
                'selectable' => $remaining > 0,
            ];
        }

        return $meta;
    }

    private function creditsToMaxHours(int $credits): int
    {
        if ($credits === 3) {
            return 50;
        }

        if ($credits === 6) {
            return 100;
        }

        if ($credits <= 0) {
            return 50;
        }

        return (int) round(($credits / 3) * 50);
    }

    private function durationHours($startTime, $endTime): int
    {
        $start = Carbon::parse($this->normalizeTime($startTime));
        $end = Carbon::parse($this->normalizeTime($endTime));

        return max(0, (int) round($start->diffInMinutes($end) / 60));
    }

    private function expandTimeKeys($startTime, $endTime): array
    {
        $start = Carbon::parse($this->normalizeTime($startTime));
        $end = Carbon::parse($this->normalizeTime($endTime));

        if ($end->lte($start)) {
            return [$start->format('H:i')];
        }

        $keys = [];
        $cursor = $start->copy();
        while ($cursor->lt($end)) {
            $keys[] = $cursor->format('H:i');
            $cursor->addHour();
        }

        return $keys;
    }

    private function normalizeDate($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return (string) $value;
    }

    private function normalizeTime($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i');
        }

        if (is_string($value)) {
            return substr($value, 0, 5);
        }

        return (string) $value;
    }

    private function extractCourseIdsFromTeacherSubject($teacherSubject): array
    {
        if (!$teacherSubject) {
            return [];
        }

        $courseIds = $teacherSubject->subject?->courses?->pluck('id')
            ?->map(fn ($id) => (int) $id)
            ->all() ?? [];

        $primaryCourseId = (int) ($teacherSubject->subject?->course_id ?? 0);
        if ($primaryCourseId > 0 && !in_array($primaryCourseId, $courseIds, true)) {
            $courseIds[] = $primaryCourseId;
        }

        return array_values(array_unique(array_filter($courseIds)));
    }
}
