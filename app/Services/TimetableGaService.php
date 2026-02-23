<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimetableGaService
{
    private array $days;
    private array $slots;
    private int $populationSize;
    private int $generations;
    private float $mutationRate;
    private string $mode = 'normal';
    private ?int $weeklyTarget = 40;

    public function __construct(array $days, array $slots, int $populationSize = 40, int $generations = 60, float $mutationRate = 0.15)
    {
        $this->days = $days;
        $this->slots = $slots;
        $this->populationSize = $populationSize;
        $this->generations = $generations;
        $this->mutationRate = $mutationRate;
    }

    public function generateMonthly(
        Collection $teacherSubjects,
        Collection $rooms,
        Collection $existingTimetables,
        Carbon $monthStart,
        Carbon $monthEnd,
        array $remainingHoursByTeacherSubject,
        array $options = []
    ): array
    {
        if ($teacherSubjects->isEmpty()) {
            return [];
        }

        $requestedMode = strtolower((string) ($options['mode'] ?? 'normal'));
        $this->mode = in_array($requestedMode, ['relaxed', 'normal', 'emergency'], true) ? $requestedMode : 'normal';
        $this->weeklyTarget = match ($this->mode) {
            'relaxed' => 20,
            'normal' => 40,
            default => null,
        };

        $teacherSubjects = $teacherSubjects->keyBy('id');
        $sessionGenes = $this->buildSessionGenes($teacherSubjects, $remainingHoursByTeacherSubject);
        if (empty($sessionGenes)) {
            return [];
        }

        $candidateDates = $this->buildCandidateDates($monthStart, $monthEnd);
        if (empty($candidateDates)) {
            return [];
        }

        $population = $this->seedPopulation($sessionGenes, $candidateDates, $rooms);

        for ($generation = 0; $generation < $this->generations; $generation++) {
            $scored = $this->scorePopulation($population, $sessionGenes, $teacherSubjects, $rooms, $existingTimetables, $candidateDates);
            $population = $this->nextGeneration($scored, $sessionGenes, $candidateDates, $rooms);
        }

        $finalScores = $this->scorePopulation($population, $sessionGenes, $teacherSubjects, $rooms, $existingTimetables, $candidateDates);
        usort($finalScores, fn ($a, $b) => $a['fitness'] <=> $b['fitness']);

        $best = $finalScores[0]['schedule'] ?? [];

        return array_values($best);
    }

    private function buildSessionGenes(Collection $teacherSubjects, array $remainingHoursByTeacherSubject): array
    {
        $sessionGenes = [];
        $semesterMonths = 6;

        foreach ($teacherSubjects as $teacherSubject) {
            $remaining = max(0, (float) ($remainingHoursByTeacherSubject[$teacherSubject->id] ?? 0));
            if ($remaining <= 0) {
                continue;
            }

            // Spread workload across semester unless in emergency mode.
            $plannedHoursThisMonth = $remaining;
            if ($this->mode !== 'emergency') {
                $suggestedMonthlyHours = max(2, (int) round($remaining / $semesterMonths));
                $plannedHoursThisMonth = min($remaining, $suggestedMonthlyHours);
            }
            $sessionCount = (int) ceil($plannedHoursThisMonth / 2);

            $courseIds = $this->extractCourseIdsFromTeacherSubject($teacherSubject);

            for ($index = 1; $index <= $sessionCount; $index++) {
                $geneKey = $teacherSubject->id . ':' . $index;
                $sessionGenes[$geneKey] = [
                    'gene_key' => $geneKey,
                    'teacher_subject_id' => $teacherSubject->id,
                    'subject_id' => $teacherSubject->subject_id,
                    'course_ids' => $courseIds,
                ];
            }
        }

        return $sessionGenes;
    }

    private function buildCandidateDates(Carbon $monthStart, Carbon $monthEnd): array
    {
        $dates = [];
        $cursor = $monthStart->copy();

        while ($cursor->lte($monthEnd)) {
            $dayName = $cursor->format('l');
            if (in_array($dayName, $this->days, true)) {
                $dates[] = $cursor->toDateString();
            }
            $cursor->addDay();
        }

        return $dates;
    }

    private function seedPopulation(array $sessionGenes, array $candidateDates, Collection $rooms): array
    {
        $population = [];

        for ($i = 0; $i < $this->populationSize; $i++) {
            $population[] = $this->randomSchedule($sessionGenes, $candidateDates, $rooms);
        }

        return $population;
    }

    private function randomSchedule(array $sessionGenes, array $candidateDates, Collection $rooms): array
    {
        $schedule = [];

        foreach ($sessionGenes as $gene) {
            $slot = $this->randomSlot();
            $classDate = $candidateDates[array_rand($candidateDates)];
            $room = $rooms->isNotEmpty() ? $rooms->random() : null;

            $schedule[$gene['gene_key']] = [
                'gene_key' => $gene['gene_key'],
                'teacher_subject_id' => $gene['teacher_subject_id'],
                'subject_id' => $gene['subject_id'] ?? null,
                'course_ids' => $gene['course_ids'] ?? [],
                'class_date' => $classDate,
                'day_of_week' => Carbon::parse($classDate)->format('l'),
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'room' => $room?->code,
            ];
        }

        return $schedule;
    }

    private function scorePopulation(array $population, array $sessionGenes, Collection $teacherSubjects, Collection $rooms, Collection $existingTimetables, array $candidateDates): array
    {
        $scored = [];

        foreach ($population as $schedule) {
            $fitness = $this->calculateFitness($schedule, $sessionGenes, $teacherSubjects, $rooms, $existingTimetables, $candidateDates);
            $scored[] = [
                'schedule' => $schedule,
                'fitness' => $fitness,
            ];
        }

        return $scored;
    }

    private function calculateFitness(array $schedule, array $sessionGenes, Collection $teacherSubjects, Collection $rooms, Collection $existingTimetables, array $candidateDates): float
    {
        $penalty = 0.0;

        $roomDateSlots = [];
        $teacherDateSlots = [];
        $courseDateSlots = [];
        $roomWeeklySlots = [];
        $teacherWeeklySlots = [];
        $courseWeeklySlots = [];
        $weeklyHours = [];

        foreach ($candidateDates as $candidateDate) {
            $weekKey = Carbon::parse($candidateDate)->format('o-W');
            $weeklyHours[$weekKey] = 0;
        }

        foreach ($existingTimetables as $timetable) {
            $teacherId = $timetable->teacherSubject?->teacher_id;
            $courseIds = $this->extractCourseIdsFromTeacherSubject($timetable->teacherSubject);
            $timeKeys = $this->expandTimeKeys($timetable->start_time, $timetable->end_time);

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
                $weekday = $timetable->day_of_week;
                if ($teacherId) {
                    foreach ($timeKeys as $timeKey) {
                        $teacherWeeklySlots[$teacherId][$weekday][$timeKey] = true;
                    }
                }
                foreach ($courseIds as $courseId) {
                    foreach ($timeKeys as $timeKey) {
                        $courseWeeklySlots[$courseId][$weekday][$timeKey] = true;
                    }
                }
                if ($timetable->room) {
                    foreach ($timeKeys as $timeKey) {
                        $roomWeeklySlots[$timetable->room][$weekday][$timeKey] = true;
                    }
                }
            }
        }

        foreach ($schedule as $entry) {
            $teacherSubject = $teacherSubjects->firstWhere('id', $entry['teacher_subject_id']);
            $teacherId = $teacherSubject?->teacher_id;
            $courseIds = collect($entry['course_ids'] ?? $this->extractCourseIdsFromTeacherSubject($teacherSubject))
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();
            $entryTimeKeys = $this->expandTimeKeys($entry['start_time'], $entry['end_time']);
            $entryDate = $this->normalizeDate($entry['class_date']);
            $entryDay = $entry['day_of_week'];
            $entryDuration = $this->durationHours($entry['start_time'], $entry['end_time']);

            $weekKey = Carbon::parse($entryDate)->format('o-W');
            $weeklyHours[$weekKey] = ($weeklyHours[$weekKey] ?? 0) + $entryDuration;

            if (!$entry['room']) {
                $penalty += 300;
                continue;
            }

            $roomConflict = false;
            foreach ($entryTimeKeys as $timeKey) {
                $roomConflict = $roomConflict
                    || ($roomDateSlots[$entry['room']][$entryDate][$timeKey] ?? false)
                    || ($roomWeeklySlots[$entry['room']][$entryDay][$timeKey] ?? false);
            }
            if ($roomConflict) {
                $penalty += 1000000;
                continue;
            }

            if ($teacherId) {
                $teacherConflict = false;
                foreach ($entryTimeKeys as $timeKey) {
                    $teacherConflict = $teacherConflict
                        || ($teacherDateSlots[$teacherId][$entryDate][$timeKey] ?? false)
                        || ($teacherWeeklySlots[$teacherId][$entryDay][$timeKey] ?? false);
                }
                if ($teacherConflict) {
                    $penalty += 1000000;
                    continue;
                }
            }

            // Hard rule: same course cohort cannot have two classes at the same time slot.
            foreach ($courseIds as $courseId) {
                $courseConflict = false;
                foreach ($entryTimeKeys as $timeKey) {
                    $courseConflict = $courseConflict
                        || ($courseDateSlots[$courseId][$entryDate][$timeKey] ?? false)
                        || ($courseWeeklySlots[$courseId][$entryDay][$timeKey] ?? false);
                }

                if ($courseConflict) {
                    $penalty += 1000000;
                    continue 2;
                }
            }

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

            $room = $rooms->firstWhere('code', $entry['room']);
            $capacity = $room?->capacity;
            $required = $teacherSubject?->class_capacity;

            if ($capacity !== null && $required !== null) {
                if ($capacity < $required) {
                    $penalty += 200;
                } else {
                    $penalty += abs($capacity - $required) / 10;
                }
            }
        }

        // Soft weekly target by mode.
        if ($this->weeklyTarget !== null) {
            foreach ($weeklyHours as $hours) {
                $deviation = abs($hours - $this->weeklyTarget);
                $penalty += $deviation * 6;

                if ($hours > $this->weeklyTarget) {
                    $penalty += ($hours - $this->weeklyTarget) * 40;
                }
            }
        }

        return $penalty;
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

    private function nextGeneration(array $scored, array $sessionGenes, array $candidateDates, Collection $rooms): array
    {
        usort($scored, fn ($a, $b) => $a['fitness'] <=> $b['fitness']);

        $eliteCount = max(2, (int) floor($this->populationSize * 0.2));
        $elites = array_slice($scored, 0, $eliteCount);

        $next = array_map(fn ($item) => $item['schedule'], $elites);

        while (count($next) < $this->populationSize) {
            $parentA = $elites[array_rand($elites)]['schedule'];
            $parentB = $elites[array_rand($elites)]['schedule'];

            $child = $this->crossover($parentA, $parentB, $sessionGenes, $candidateDates, $rooms);
            $child = $this->mutate($child, $sessionGenes, $candidateDates, $rooms);

            $next[] = $child;
        }

        return $next;
    }

    private function crossover(array $parentA, array $parentB, array $sessionGenes, array $candidateDates, Collection $rooms): array
    {
        $child = [];

        foreach ($sessionGenes as $gene) {
            $geneKey = $gene['gene_key'];
            $gene = (random_int(0, 1) === 1)
                ? ($parentA[$geneKey] ?? null)
                : ($parentB[$geneKey] ?? null);

            if (!$gene) {
                $slot = $this->randomSlot();
                $classDate = $candidateDates[array_rand($candidateDates)];
                $room = $rooms->isNotEmpty() ? $rooms->random() : null;
                $gene = [
                    'gene_key' => $geneKey,
                    'teacher_subject_id' => $sessionGenes[$geneKey]['teacher_subject_id'],
                    'subject_id' => $sessionGenes[$geneKey]['subject_id'] ?? null,
                    'course_ids' => $sessionGenes[$geneKey]['course_ids'] ?? [],
                    'class_date' => $classDate,
                    'day_of_week' => Carbon::parse($classDate)->format('l'),
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'room' => $room?->code,
                ];
            }

            $child[$geneKey] = $gene;
        }

        return $child;
    }

    private function mutate(array $schedule, array $sessionGenes, array $candidateDates, Collection $rooms): array
    {
        foreach ($sessionGenes as $gene) {
            $geneKey = $gene['gene_key'];
            if (random_int(0, 1000) / 1000 <= $this->mutationRate) {
                $slot = $this->randomSlot();
                $classDate = $candidateDates[array_rand($candidateDates)];
                $room = $rooms->isNotEmpty() ? $rooms->random() : null;
                $schedule[$geneKey] = [
                    'gene_key' => $geneKey,
                    'teacher_subject_id' => $gene['teacher_subject_id'],
                    'subject_id' => $gene['subject_id'] ?? null,
                    'course_ids' => $gene['course_ids'] ?? [],
                    'class_date' => $classDate,
                    'day_of_week' => Carbon::parse($classDate)->format('l'),
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'room' => $room?->code,
                ];
            }
        }

        return $schedule;
    }

    private function randomSlot(): array
    {
        $slot = $this->slots[array_rand($this->slots)];

        return [
            'start_time' => $slot[0],
            'end_time' => $slot[1],
        ];
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
}
