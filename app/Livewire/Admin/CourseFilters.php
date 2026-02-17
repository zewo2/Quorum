<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Enrollment;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class CourseFilters extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $department = '';

    #[Url]
    public string $status = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'department', 'status');
        $this->resetPage();
    }

    public function render()
    {
        $query = Course::withCount(['enrollments', 'subjects']);

        // Search filter
        if (filled($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // Department filter
        if (filled($this->department)) {
            $query->where('department', $this->department);
        }

        // Status filter
        if (filled($this->status)) {
            $query->where('status', $this->status);
        }

        $courses = $query->orderBy('name')->paginate(10);

        // Get unique departments for filter
        $departments = Course::select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Stats
        $totalCourses = Course::count();
        $activeCourses = Course::where('status', 'active')->count();
        $totalEnrollments = Enrollment::where('status', 'active')->count();
        $departmentCount = Course::distinct('department')->count('department');

        return view('livewire.admin.course-filters', [
            'courses' => $courses,
            'departments' => $departments,
            'totalCourses' => $totalCourses,
            'activeCourses' => $activeCourses,
            'totalEnrollments' => $totalEnrollments,
            'departmentCount' => $departmentCount,
        ]);
    }
}
