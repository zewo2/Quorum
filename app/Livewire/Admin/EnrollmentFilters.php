<?php

namespace App\Livewire\Admin;

use App\Models\Enrollment;
use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class EnrollmentFilters extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $course_id = '';

    #[Url]
    public string $status = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'course_id', 'status');
        $this->resetPage();
    }

    public function render()
    {
        $query = Enrollment::with(['student', 'course']);

        if (filled($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function ($courseQuery) use ($search) {
                    $courseQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        if (filled($this->status)) {
            $query->where('status', $this->status);
        }

        if (filled($this->course_id)) {
            $query->where('course_id', $this->course_id);
        }

        $enrollments = $query
            ->orderByDesc('created_at')
            ->paginate(15);

        $courses = Course::orderBy('name')->get();
        $statuses = ['active', 'completed', 'withdrawn'];

        return view('livewire.admin.enrollment-filters', [
            'enrollments' => $enrollments,
            'courses' => $courses,
            'statuses' => $statuses,
        ]);
    }
}
