<?php

namespace App\Livewire\Admin;

use App\Models\TeacherSubject;
use App\Models\Subject;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class TeacherSubjectFilters extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $teacher_id = '';

    #[Url]
    public string $subject_id = '';

    #[Url]
    public string $academic_year = '';

    #[Url]
    public string $status = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'teacher_id', 'subject_id', 'academic_year', 'status');
        $this->resetPage();
    }

    public function render()
    {
        $query = TeacherSubject::with(['teacher', 'subject']);

        if (filled($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('teacher', function ($teacherQuery) use ($search) {
                    $teacherQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('subject', function ($subjectQuery) use ($search) {
                    $subjectQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        if (filled($this->status)) {
            $query->where('status', $this->status);
        }

        if (filled($this->academic_year)) {
            $query->where('academic_year', $this->academic_year);
        }

        if (filled($this->teacher_id)) {
            $query->where('teacher_id', $this->teacher_id);
        }

        if (filled($this->subject_id)) {
            $query->where('subject_id', $this->subject_id);
        }

        $assignments = $query
            ->orderByDesc('academic_year')
            ->orderBy('semester')
            ->paginate(15);

        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $statuses = ['active', 'inactive'];

        return view('livewire.admin.teacher-subject-filters', [
            'assignments' => $assignments,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'statuses' => $statuses,
        ]);
    }
}
