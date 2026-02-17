<?php

namespace App\Livewire\Admin;

use App\Models\Timetable;
use App\Models\User;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class TimetableFilters extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Url]
    public $day = '';

    #[\Livewire\Attributes\Url]
    public $teacher = '';

    #[\Livewire\Attributes\Url]
    public $subject = '';

    #[\Livewire\Attributes\Url]
    public $room = '';

    public function updated()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Timetable::with('teacherSubject.teacher', 'teacherSubject.subject');

        if ($this->day) {
            $query->where('day_of_week', $this->day);
        }

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

        if ($this->room) {
            $query->where('room', 'like', '%' . $this->room . '%');
        }

        $timetables = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(20);

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name']);
        $subjects = Subject::orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.admin.timetable-filters', compact('timetables', 'days', 'teachers', 'subjects'));
    }
}
