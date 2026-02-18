<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Room;
use App\Models\TeacherSubject;
use App\Models\Timetable;
use App\Services\TimetableGaService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
        $preview = $request->session()->get('ga.timetables', []);
        $stats = $request->session()->get('ga.stats', []);
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

        $teacherSubjects = TeacherSubject::with('teacher', 'subject.course', 'subject.courses')
            ->where('status', 'active')
            ->whereHas('subject', function ($query) use ($validated) {
                $query->whereHas('courses', function ($courseQuery) use ($validated) {
                    $courseQuery->whereIn('courses.id', $validated['selected_course_ids']);
                });

                if (!empty($validated['selected_year'])) {
                    $query->where('year', (int) $validated['selected_year']);
                }

                if (!empty($validated['selected_semester'])) {
                    $query->where('semester', (int) $validated['selected_semester']);
                }
            })
            ->get();

        if (!empty($validated['selected_teacher_subject_ids'])) {
            $teacherSubjects = $teacherSubjects->whereIn('id', $validated['selected_teacher_subject_ids'])->values();
        }

        $hoursMeta = $this->buildHoursMeta($teacherSubjects);
        $remainingHours = collect($hoursMeta)->pluck('remaining_hours', 'teacher_subject_id')->all();

        $targets = $teacherSubjects->filter(function ($teacherSubject) use ($remainingHours) {
            return ($remainingHours[$teacherSubject->id] ?? 0) > 0;
        })->values();

        if ($targets->isEmpty()) {
            return redirect()->route('dashboard.admin.timetables.ga')
                ->withErrors(['ga' => 'No selectable classes found. Selected classes may have already reached their legal hour cap.']);
        }

        $monthStart = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $validated['month'])->endOfMonth();

        $existing = Timetable::with('teacherSubject')->get();
        $rooms = Room::orderBy('capacity')->get();
        $service = new TimetableGaService($this->days, $this->slots);
        $schedule = $service->generateMonthly(
            $targets,
            $rooms,
            $existing,
            $monthStart,
            $monthEnd,
            $remainingHours,
            ['mode' => $validated['mode']]
        );

        $preview = [];
        foreach ($schedule as $entry) {
            $teacherSubject = $targets->firstWhere('id', $entry['teacher_subject_id']);
            $room = $rooms->firstWhere('code', $entry['room']);
            $maxHours = $this->creditsToMaxHours((int) ($teacherSubject?->subject?->credits ?? 0));
            $scheduledHours = $hoursMeta[$entry['teacher_subject_id']]['scheduled_hours'] ?? 0;
            $remaining = $hoursMeta[$entry['teacher_subject_id']]['remaining_hours'] ?? 0;

            $preview[] = [
                'teacher_subject_id' => $entry['teacher_subject_id'],
                'course' => $teacherSubject?->subject?->courses?->pluck('name')?->join(', ')
                    ?: ($teacherSubject?->subject?->course?->name ?? 'N/A'),
                'teacher' => $teacherSubject?->teacher?->name ?? 'N/A',
                'subject' => $teacherSubject?->subject?->name ?? 'N/A',
                'class_date' => $entry['class_date'],
                'day_of_week' => $entry['day_of_week'],
                'start_time' => $entry['start_time'],
                'end_time' => $entry['end_time'],
                'room' => $entry['room'],
                'building' => $room?->building,
                'capacity' => $room?->capacity,
                'required' => $teacherSubject?->class_capacity,
                'max_hours' => $maxHours,
                'already_scheduled_hours' => $scheduledHours,
                'remaining_hours_before' => $remaining,
            ];
        }

        $request->session()->put('ga.timetables', $preview);
        $request->session()->put('ga.stats', [
            'generated' => count($preview),
            'classes_selected' => $targets->count(),
            'courses_selected' => count($validated['selected_course_ids']),
            'month' => $validated['month'],
            'mode' => $validated['mode'],
            'year' => $validated['selected_year'] ?? null,
            'semester' => $validated['selected_semester'] ?? null,
        ]);
        $request->session()->put('ga.selected_course_ids', $validated['selected_course_ids']);
        $request->session()->put('ga.selected_teacher_subject_ids', $validated['selected_teacher_subject_ids'] ?? []);
        $request->session()->put('ga.selected_month', $validated['month']);
        $request->session()->put('ga.selected_mode', $validated['mode']);
        $request->session()->put('ga.selected_year', $validated['selected_year'] ?? null);
        $request->session()->put('ga.selected_semester', $validated['selected_semester'] ?? null);

        return redirect()->route('dashboard.admin.timetables.ga')
            ->with('success', 'Schedule generated. Review and apply when ready.');
    }

    public function apply(Request $request): RedirectResponse
    {
        $preview = $request->session()->get('ga.timetables', []);

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
            $startKey = $this->normalizeTime($timetable->start_time);
            $courseIds = $this->extractCourseIdsFromTeacherSubject($timetable->teacherSubject);

            if ($timetable->class_date) {
                $dateKey = $this->normalizeDate($timetable->class_date);
                if ($teacherId) {
                    $teacherDateSlots[$teacherId][$dateKey][$startKey] = true;
                }
                foreach ($courseIds as $courseId) {
                    $courseDateSlots[$courseId][$dateKey][$startKey] = true;
                }
                if ($timetable->room) {
                    $roomDateSlots[$timetable->room][$dateKey][$startKey] = true;
                }
            } else {
                if ($teacherId) {
                    $teacherWeeklySlots[$teacherId][$timetable->day_of_week][$startKey] = true;
                }
                foreach ($courseIds as $courseId) {
                    $courseWeeklySlots[$courseId][$timetable->day_of_week][$startKey] = true;
                }
                if ($timetable->room) {
                    $roomWeeklySlots[$timetable->room][$timetable->day_of_week][$startKey] = true;
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
            $entryStart = $this->normalizeTime($entry['start_time']);
            $entryDate = $this->normalizeDate($entry['class_date']);
            $entryDay = Carbon::parse($entryDate)->format('l');
            $duration = $this->durationHours($entry['start_time'], $entry['end_time']);

            if (($remainingHours[$teacherSubjectId] ?? 0) < $duration) {
                $hoursLimitReached++;
                continue;
            }

            $roomConflict = ($roomDateSlots[$entry['room']][$entryDate][$entryStart] ?? false)
                || ($roomWeeklySlots[$entry['room']][$entryDay][$entryStart] ?? false);
            $teacherConflict = $teacherId
                ? (($teacherDateSlots[$teacherId][$entryDate][$entryStart] ?? false)
                    || ($teacherWeeklySlots[$teacherId][$entryDay][$entryStart] ?? false))
                : false;

            $courseConflict = false;
            foreach ($courseIds as $courseId) {
                $courseConflict = $courseConflict
                    || ($courseDateSlots[$courseId][$entryDate][$entryStart] ?? false)
                    || ($courseWeeklySlots[$courseId][$entryDay][$entryStart] ?? false);
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

            $roomDateSlots[$entry['room']][$entryDate][$entryStart] = true;
            if ($teacherId) {
                $teacherDateSlots[$teacherId][$entryDate][$entryStart] = true;
            }
            foreach ($courseIds as $courseId) {
                $courseDateSlots[$courseId][$entryDate][$entryStart] = true;
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
