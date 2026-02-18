<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class ExamsFilters extends Component
{
    #[Url]
    public string $status = 'all';

    #[Url]
    public string $sort = 'status';

    public function resetFilters(): void
    {
        $this->status = 'all';
        $this->sort = 'status';
    }

    public function render()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return view('livewire.student.exams-filters', [
                'enrolledCourses' => collect(),
                'nextExamByCourse' => collect(),
                'today' => now()->startOfDay(),
            ]);
        }

        $enrolledQuery = $user->enrollments()->with('course');

        if ($this->status !== 'all') {
            $enrolledQuery->where('status', $this->status);
        }

        $enrolledCourses = $enrolledQuery->get();

        $courseIds = $enrolledCourses->pluck('course_id')->toArray();
        $today = now()->startOfDay();

        $exams = empty($courseIds)
            ? collect()
            : Exam::with('subject.course', 'subject.courses')
                ->whereHas('subject', function ($query) use ($courseIds) {
                    $query->whereHas('courses', function ($courseQuery) use ($courseIds) {
                        $courseQuery->whereIn('courses.id', $courseIds);
                    });
                })
                ->orderBy('exam_date')
                ->orderBy('start_time')
                ->get();

        $nextExamByCourse = $enrolledCourses->mapWithKeys(function ($enrollment) use ($exams, $today) {
            $courseId = $enrollment->course_id;

            $courseExams = $exams->filter(function ($exam) use ($courseId) {
                $subjectCourseIds = $exam->subject?->courses?->pluck('id')->all() ?? [];

                return in_array($courseId, $subjectCourseIds, true);
            });

            $selected = $courseExams->first(fn ($exam) => $exam->exam_date->startOfDay()->gte($today))
                ?? $courseExams->first();

            return [$courseId => $selected];
        });

        $enrolledCourses = match ($this->sort) {
            'course_name' => $enrolledCourses->sortBy(fn ($e) => $e->course->name ?? '')->values(),
            'grade' => $enrolledCourses->sortByDesc(fn ($e) => $e->grade ?? -1)->values(),
            default => $enrolledCourses->sortBy(fn ($e) => ($e->status ?? '') . '|' . ($e->course->name ?? ''))->values(),
        };

        return view('livewire.student.exams-filters', [
            'enrolledCourses' => $enrolledCourses,
            'nextExamByCourse' => $nextExamByCourse,
            'today' => $today,
        ]);
    }
}
