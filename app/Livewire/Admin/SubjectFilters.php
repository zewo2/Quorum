<?php

namespace App\Livewire\Admin;

use App\Models\Subject;
use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class SubjectFilters extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $course = '';

    #[Url]
    public string $year = '';

    #[Url]
    public string $semester = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'course', 'year', 'semester');
        $this->resetPage();
    }

    public function render()
    {
        $query = Subject::with('courses');

        // Search filter
        if (filled($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%");
            });
        }

        // Course filter
        if (filled($this->course)) {
            $query->whereHas('courses', function ($q) {
                $q->where('courses.id', $this->course);
            });
        }

        if (filled($this->year)) {
            $query->where('year', (int) $this->year);
        }

        if (filled($this->semester)) {
            $query->where('semester', (int) $this->semester);
        }

        $subjects = $query->orderBy('name')->paginate(15);

        // Get all courses for filter dropdown
        $courses = Course::select('id', 'name', 'code')->orderBy('name')->get();

        // Stats
        $totalSubjects = Subject::count();
        $totalCredits = Subject::sum('credits');
        $courseCount = Course::count();

        return view('livewire.admin.subject-filters', [
            'subjects' => $subjects,
            'courses' => $courses,
            'totalSubjects' => $totalSubjects,
            'totalCredits' => $totalCredits,
            'courseCount' => $courseCount,
        ]);
    }
}
