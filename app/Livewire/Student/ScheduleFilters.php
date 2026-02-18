<?php

namespace App\Livewire\Student;

use App\Models\Timetable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class ScheduleFilters extends Component
{
    #[Url]
    public string $status = 'all';

    #[Url]
    public string $view = 'detailed';

    public function resetFilters(): void
    {
        $this->status = 'all';
        $this->view = 'detailed';
    }

    public function render()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return view('livewire.student.schedule-filters', [
                'enrolledCourses' => collect(),
                'timetables' => collect(),
                'viewMode' => $this->view,
            ]);
        }

        $enrollmentsQuery = $user->enrollments()->with('course');

        if ($this->status === 'active') {
            $enrollmentsQuery->where('status', 'active');
        }

        $enrolledCourses = $enrollmentsQuery->get();
        $courseIds = $enrolledCourses->pluck('course_id')->toArray();

        $timetables = empty($courseIds)
            ? collect()
            : Timetable::with('teacherSubject.subject', 'teacherSubject.teacher')
                ->whereHas('teacherSubject', function ($query) use ($courseIds) {
                    $query->whereHas('subject', function ($subjectQuery) use ($courseIds) {
                        $subjectQuery->whereHas('courses', function ($courseQuery) use ($courseIds) {
                            $courseQuery->whereIn('courses.id', $courseIds);
                        });
                    });
                })
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

        return view('livewire.student.schedule-filters', [
            'enrolledCourses' => $enrolledCourses,
            'timetables' => $timetables,
            'viewMode' => $this->view,
        ]);
    }
}
