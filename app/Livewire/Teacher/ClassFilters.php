<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class ClassFilters extends Component
{
    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'all';

    public ?int $selectedSubjectId = null;
    public ?array $rosterData = null;

    public function resetFilters(): void
    {
        $this->search = '';
        $this->status = 'all';
    }

    public function viewRoster(int $subjectId): void
    {
        $this->selectedSubjectId = $subjectId;
        $this->loadRosterData();
    }

    public function closeRoster(): void
    {
        $this->selectedSubjectId = null;
        $this->rosterData = null;
    }

    public function updateStudentGrade(int $enrollmentId, ?float $grade): void
    {
        try {
            $enrollment = \App\Models\Enrollment::find($enrollmentId);

            if (!$enrollment) {
                return;
            }

            // Update grade
            $enrollment->final_grade = $grade;

            // If grade is provided, mark as completed
            if ($grade !== null && $grade > 0) {
                $enrollment->status = 'completed';
            }

            $enrollment->save();

            // Refresh roster data
            $this->loadRosterData();
        } catch (\Exception $e) {
            \Log::error('Error updating student grade: ' . $e->getMessage());
        }
    }

    private function loadRosterData(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$this->selectedSubjectId) {
            return;
        }

        $subject = $user->subjectsTaught()->find($this->selectedSubjectId);

        if (!$subject) {
            return;
        }

        $courses = $subject->courses;
        $students = $courses->flatMap(function ($course) {
            return $course->enrollments->map(function ($enrollment) {
                return [
                    'id' => $enrollment->student->id,
                    'name' => $enrollment->student->name,
                    'email' => $enrollment->student->email,
                    'status' => $enrollment->status,
                    'final_grade' => $enrollment->final_grade,
                    'enrollment_id' => $enrollment->id,
                ];
            });
        })->unique('id')->values()->toArray();

        $this->rosterData = [
            'subject_name' => $subject->name,
            'subject_code' => $subject->code,
            'student_count' => count($students),
            'students' => $students,
        ];
    }

    public function render()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return view('livewire.teacher.class-filters', [
                'teacherSubjects' => collect(),
                'rosterData' => null,
                'selectedSubjectId' => null,
            ]);
        }

        $subjectsQuery = $user->subjectsTaught()->with('courses.enrollments.student');

        // Apply search filter
        if ($this->search) {
            $subjectsQuery->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('code', 'like', "%{$this->search}%");
            });
        }

        // Apply status filter
        if ($this->status === 'active') {
            $subjectsQuery->wherePivot('status', 'active');
        }

        $teacherSubjects = $subjectsQuery->get();

        return view('livewire.teacher.class-filters', [
            'teacherSubjects' => $teacherSubjects,
            'rosterData' => $this->rosterData,
            'selectedSubjectId' => $this->selectedSubjectId,
        ]);
    }
}
