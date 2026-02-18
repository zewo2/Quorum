<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\User;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class TimetableFilters extends Component
{

    #[\Livewire\Attributes\Url]
    public $teacher = '';

    #[\Livewire\Attributes\Url]
    public $subject = '';

    #[\Livewire\Attributes\Url]
    public $month = '';

    #[\Livewire\Attributes\Url]
    public $courseId = '';

    #[\Livewire\Attributes\Url]
    public $week = 1;

    public function mount(): void
    {
        if (!$this->month) {
            $this->month = now()->format('Y-m');
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'courseId', 'teacher', 'subject'], true)) {
            $this->week = 1;
        }
    }

    public function resetFilters()
    {
        $this->reset(['teacher', 'subject', 'courseId']);
        $this->month = now()->format('Y-m');
        $this->week = 1;
    }

    public function clearSelectedCourseSchedule(): void
    {
        if (!$this->courseId) {
            session()->flash('error', 'Select a course first.');
            return;
        }

        $deleted = Timetable::whereHas('teacherSubject.subject', function ($query) {
            $query->where('course_id', $this->courseId)
                ->orWhereHas('courses', function ($courseQuery) {
                    $courseQuery->where('courses.id', $this->courseId);
                });
        })->delete();

        $this->week = 1;

        session()->flash('success', $deleted > 0
            ? "Deleted {$deleted} timetable entries for the selected course."
            : 'No timetable entries found for the selected course.');
    }

    public function clearAllSchedules(): void
    {
        $deleted = Timetable::query()->delete();

        $this->week = 1;

        session()->flash('success', $deleted > 0
            ? "Deleted {$deleted} timetable entries from all courses."
            : 'No timetable entries found to delete.');
    }

    public function render(): View
    {
        $courses = Course::orderBy('name')->get(['id', 'name', 'code']);
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name']);
        $subjects = Subject::orderBy('name')
            ->get(['id', 'name']);

        if (!$this->courseId) {
            return view('livewire.admin.timetable-filters', [
                'timetables' => collect(),
                'groupedByDate' => collect(),
                'entryCount' => 0,
                'teachers' => $teachers,
                'subjects' => $subjects,
                'courses' => $courses,
                'showCalendar' => false,
                'weekDates' => collect(),
                'currentWeek' => 1,
                'totalWeeks' => 0,
                'monthStart' => null,
            ]);
        }

        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $query = Timetable::with('teacherSubject.teacher', 'teacherSubject.subject');

        // Query entire month
        $query->whereBetween('class_date', [$monthStart->toDateString(), $monthEnd->toDateString()]);

        $query->whereHas('teacherSubject.subject', function($q) {
            $q->where('course_id', $this->courseId)
              ->orWhereHas('courses', function ($courseQuery) {
                  $courseQuery->where('courses.id', $this->courseId);
              });
        });

        if ($this->teacher) {
            $query->whereHas('teacherSubject', function($q) {
                $q->where('teacher_id', $this->teacher);
            });
        }

        if ($this->subject) {
            $query->whereHas('teacherSubject', function($q) {
                $q->where('subject_id', $this->subject);
            });
        }

        $timetables = $query->orderBy('class_date')
            ->orderBy('start_time')
            ->get();

        // Group by date
        $groupedByDate = $timetables->groupBy(function($item) {
            return $item->class_date;
        });

        // Build month weeks excluding Sundays:
        // week 1 = month start day to Saturday, then Monday-Saturday blocks.
        $weeks = [];
        $cursor = $monthStart->copy();

        while ($cursor->lte($monthEnd) && $cursor->isSunday()) {
            $cursor->addDay();
        }

        while ($cursor->lte($monthEnd)) {
            $weekDates = collect();
            $dayCursor = $cursor->copy();

            while ($dayCursor->lte($monthEnd)) {
                if (!$dayCursor->isSunday()) {
                    $weekDates->push($dayCursor->copy());
                }

                if ($dayCursor->isSaturday()) {
                    break;
                }

                $dayCursor->addDay();
            }

            if ($weekDates->isNotEmpty()) {
                $weeks[] = $weekDates;
            }

            $cursor = $dayCursor->copy()->addDay();
            while ($cursor->lte($monthEnd) && $cursor->isSunday()) {
                $cursor->addDay();
            }
        }

        $totalWeeks = count($weeks);
        $currentWeek = min(max((int) $this->week, 1), max($totalWeeks, 1));
        $this->week = $currentWeek;
        $weekDates = $totalWeeks > 0 ? $weeks[$currentWeek - 1] : collect();

        return view('livewire.admin.timetable-filters', [
            'timetables' => $timetables,
            'groupedByDate' => $groupedByDate,
            'weekDates' => $weekDates,
            'currentWeek' => $currentWeek,
            'totalWeeks' => $totalWeeks,
            'entryCount' => $timetables->count(),
            'teachers' => $teachers,
            'subjects' => $subjects,
            'courses' => $courses,
            'showCalendar' => true,
            'monthStart' => $monthStart,
        ]);
    }
}
