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
        array $remainingHoursByTeacherSubject
    ): array
    {
        if ($teacherSubjects->isEmpty()) {
            return [];
        }

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
            $scored = $this->scorePopulation($population, $sessionGenes, $teacherSubjects, $rooms, $existingTimetables);
            $population = $this->nextGeneration($scored, $sessionGenes, $candidateDates, $rooms);
        }

        $finalScores = $this->scorePopulation($population, $sessionGenes, $teacherSubjects, $rooms, $existingTimetables);
        usort($finalScores, fn ($a, $b) => $a['fitness'] <=> $b['fitness']);

        $best = $finalScores[0]['schedule'] ?? [];

        return array_values($best);
    }

    private function buildSessionGenes(Collection $teacherSubjects, array $remainingHoursByTeacherSubject): array
    {
        $sessionGenes = [];

        foreach ($teacherSubjects as $teacherSubject) {
            $remaining = max(0, (float) ($remainingHoursByTeacherSubject[$teacherSubject->id] ?? 0));
            $sessionCount = (int) ceil($remaining / 2);

            for ($index = 1; $index <= $sessionCount; $index++) {
                $geneKey = $teacherSubject->id . ':' . $index;
                $sessionGenes[$geneKey] = [
                    'gene_key' => $geneKey,
                    'teacher_subject_id' => $teacherSubject->id,
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
                'class_date' => $classDate,
                'day_of_week' => Carbon::parse($classDate)->format('l'),
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'room' => $room?->code,
            ];
        }

        return $schedule;
    }

    private function scorePopulation(array $population, array $sessionGenes, Collection $teacherSubjects, Collection $rooms, Collection $existingTimetables): array
    {
        $scored = [];

        foreach ($population as $schedule) {
            $fitness = $this->calculateFitness($schedule, $sessionGenes, $teacherSubjects, $rooms, $existingTimetables);
            $scored[] = [
                'schedule' => $schedule,
                'fitness' => $fitness,
            ];
        }

        return $scored;
    }

    private function calculateFitness(array $schedule, array $sessionGenes, Collection $teacherSubjects, Collection $rooms, Collection $existingTimetables): float
    {
        $penalty = 0.0;

        $roomDateSlots = [];
        $teacherDateSlots = [];
        $roomWeeklySlots = [];
        $teacherWeeklySlots = [];

        foreach ($existingTimetables as $timetable) {
            $teacherId = $timetable->teacherSubject?->teacher_id;
            $startKey = $this->normalizeTime($timetable->start_time);

            if ($timetable->class_date) {
                $dateKey = $this->normalizeDate($timetable->class_date);
                if ($teacherId) {
                    $teacherDateSlots[$teacherId][$dateKey][$startKey] = true;
                }
                if ($timetable->room) {
                    $roomDateSlots[$timetable->room][$dateKey][$startKey] = true;
                }
            } else {
                $weekday = $timetable->day_of_week;
                if ($teacherId) {
                    $teacherWeeklySlots[$teacherId][$weekday][$startKey] = true;
                }
                if ($timetable->room) {
                    $roomWeeklySlots[$timetable->room][$weekday][$startKey] = true;
                }
            }
        }

        foreach ($schedule as $entry) {
            $teacherSubject = $teacherSubjects->firstWhere('id', $entry['teacher_subject_id']);
            $teacherId = $teacherSubject?->teacher_id;
            $entryStart = $this->normalizeTime($entry['start_time']);
            $entryDate = $this->normalizeDate($entry['class_date']);
            $entryDay = $entry['day_of_week'];

            if (!$entry['room']) {
                $penalty += 300;
                continue;
            }

            $roomConflict = ($roomDateSlots[$entry['room']][$entryDate][$entryStart] ?? false)
                || ($roomWeeklySlots[$entry['room']][$entryDay][$entryStart] ?? false);
            if ($roomConflict) {
                $penalty += 1000;
            }

            if ($teacherId) {
                $teacherConflict = ($teacherDateSlots[$teacherId][$entryDate][$entryStart] ?? false)
                    || ($teacherWeeklySlots[$teacherId][$entryDay][$entryStart] ?? false);
                if ($teacherConflict) {
                    $penalty += 1000;
                }
            }

            $roomDateSlots[$entry['room']][$entryDate][$entryStart] = true;
            if ($teacherId) {
                $teacherDateSlots[$teacherId][$entryDate][$entryStart] = true;
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
}
