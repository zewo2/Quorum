<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Room;
use App\Models\Subject;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ExamFilters extends Component
{
    use WithPagination;

    #[Url]
    public string $course = '';

    #[Url]
    public string $subject = '';

    #[Url]
    public string $room = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    public function updated($property): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['course', 'subject', 'room', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $courses = Course::orderBy('name')->get(['id', 'name']);

        $subjectsQuery = Subject::orderBy('name');
        if ($this->course !== '') {
            $subjectsQuery->where('course_id', $this->course);
        }
        $subjects = $subjectsQuery->get(['id', 'name', 'course_id']);

        $rooms = Room::orderBy('code')->get(['code', 'building', 'capacity']);

        $examsQuery = Exam::with('subject.course');

        if ($this->course !== '') {
            $examsQuery->whereHas('subject', function ($query) {
                $query->where('course_id', $this->course);
            });
        }

        if ($this->subject !== '') {
            $examsQuery->where('subject_id', $this->subject);
        }

        if ($this->room !== '') {
            $examsQuery->where('room', $this->room);
        }

        if ($this->dateFrom !== '') {
            $examsQuery->whereDate('exam_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo !== '') {
            $examsQuery->whereDate('exam_date', '<=', $this->dateTo);
        }

        $exams = $examsQuery->orderBy('exam_date')
            ->orderBy('start_time')
            ->paginate(20);

        return view('livewire.admin.exam-filters', [
            'courses' => $courses,
            'subjects' => $subjects,
            'rooms' => $rooms,
            'exams' => $exams,
        ]);
    }
}
