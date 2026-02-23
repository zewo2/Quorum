<?php

namespace App\Jobs;

use App\Models\GaRun;
use App\Models\Room;
use App\Models\TeacherSubject;
use App\Models\Timetable;
use App\Services\TimetableGaService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateTimetableGaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public int $tries = 1;

    public function __construct(private readonly int $gaRunId)
    {
    }

    public function handle(): void
    {
        $gaRun = GaRun::find($this->gaRunId);
        if (!$gaRun) {
            return;
        }

        $gaRun->update([
            'status' => 'running',
            'progress' => 10,
            'started_at' => now(),
            'error_message' => null,
        ]);

        try {
            $input = $gaRun->input_payload ?? [];

            $teacherSubjects = TeacherSubject::with('teacher', 'subject.course', 'subject.courses')
                ->where('status', 'active')
                ->whereHas('subject', function ($query) use ($input) {
                    $query->whereHas('courses', function ($courseQuery) use ($input) {
                        $courseQuery->whereIn('courses.id', $input['selected_course_ids'] ?? []);
                    });

                    if (!empty($input['selected_year'])) {
                        $query->where('year', (int) $input['selected_year']);
                    }

                    if (!empty($input['selected_semester'])) {
                        $query->where('semester', (int) $input['selected_semester']);
                    }
                })
                ->get();

            if (!empty($input['selected_teacher_subject_ids'])) {
                $teacherSubjects = $teacherSubjects->whereIn('id', $input['selected_teacher_subject_ids'])->values();
            }

            $hoursMeta = $this->buildHoursMeta($teacherSubjects);
            $remainingHours = collect($hoursMeta)->pluck('remaining_hours', 'teacher_subject_id')->all();

            $targets = $teacherSubjects->filter(function ($teacherSubject) use ($remainingHours) {
                return ($remainingHours[$teacherSubject->id] ?? 0) > 0;
            })->values();

            if ($targets->isEmpty()) {
                $gaRun->update([
                    'status' => 'failed',
                    'progress' => 100,
                    'error_message' => 'No selectable classes found. Selected classes may have already reached their legal hour cap.',
                    'completed_at' => now(),
                ]);
                return;
            }

            $monthStart = Carbon::createFromFormat('Y-m', $input['month'])->startOfMonth();
            $monthEnd = Carbon::createFromFormat('Y-m', $input['month'])->endOfMonth();

            $existing = Timetable::with('teacherSubject')->get();
            $rooms = Room::orderBy('capacity')->get();

            $gaRun->update(['progress' => 45]);

            $service = new TimetableGaService(
                ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                [
                    ['09:00', '11:00'],
                    ['11:00', '13:00'],
                    ['14:00', '16:00'],
                    ['16:00', '18:00'],
                ]
            );

            $schedule = $service->generateMonthly(
                $targets,
                $rooms,
                $existing,
                $monthStart,
                $monthEnd,
                $remainingHours,
                ['mode' => $input['mode'] ?? 'normal']
            );

            $gaRun->update(['progress' => 80]);

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

            $gaRun->update([
                'status' => 'completed',
                'progress' => 100,
                'result_payload' => [
                    'preview' => $preview,
                    'stats' => [
                        'generated' => count($preview),
                        'classes_selected' => $targets->count(),
                        'courses_selected' => count($input['selected_course_ids'] ?? []),
                        'month' => $input['month'] ?? null,
                        'mode' => $input['mode'] ?? 'normal',
                        'year' => $input['selected_year'] ?? null,
                        'semester' => $input['selected_semester'] ?? null,
                    ],
                ],
                'completed_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $gaRun->update([
                'status' => 'failed',
                'progress' => 100,
                'error_message' => $exception->getMessage(),
                'completed_at' => now(),
            ]);

            throw $exception;
        }
    }

    private function buildHoursMeta($teacherSubjects): array
    {
        if ($teacherSubjects->isEmpty()) {
            return [];
        }

        $teacherSubjectIds = $teacherSubjects->pluck('id');
        $scheduledHoursByTeacherSubject = [];

        $timetables = Timetable::whereIn('teacher_subject_id', $teacherSubjectIds)
            ->get(['teacher_subject_id', 'start_time', 'end_time']);

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
}
