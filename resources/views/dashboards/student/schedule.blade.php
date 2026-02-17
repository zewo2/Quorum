@extends('layouts.be_master')

@section('title', 'My Schedule - Quorum')
@section('page-title', 'My Schedule')

@section('content')
<div class="schedule-page">
    <div class="dashboard-card filters-card">
        <form method="GET" action="{{ route('dashboard.student.schedule') }}" class="filters-form">
            <div class="filters-left">
                <label class="field">
                    <span>Enrolled Courses</span>
                    <select name="status">
                        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All courses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active only</option>
                    </select>
                </label>
                <label class="field">
                    <span>View</span>
                    <select name="view">
                        <option value="detailed" {{ request('view', 'detailed') === 'detailed' ? 'selected' : '' }}>Detailed</option>
                        <option value="compact" {{ request('view') === 'compact' ? 'selected' : '' }}>Compact</option>
                    </select>
                </label>
                <div class="filters-actions">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ route('dashboard.student.schedule') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
            <div class="filters-actions">
                <a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary">My Subjects</a>
                <a href="{{ route('dashboard.student.grades') }}" class="btn btn-secondary">My Grades</a>
            </div>
        </form>
    </div>

    <div class="dashboard-card week-grid">
        <div class="card-header">
            <h3>Enrolled Courses</h3>
            <span class="chip">{{ $enrolledCourses->count() }} courses</span>
        </div>
        <div class="week-table">
            <div class="week-row week-head">
                <div class="slot">Course</div>
                <div>Code</div><div>Department</div><div>Credits</div><div>Status</div><div>Grade</div>
            </div>
            @forelse($enrolledCourses as $enrollment)
                <div class="week-row">
                    <div class="slot">{{ $enrollment->course->name }}</div>
                    <div class="cell">{{ $enrollment->course->code ?? 'N/A' }}</div>
                    <div class="cell">{{ $enrollment->course->department }}</div>
                    <div class="cell">{{ $enrollment->course->credits }}</div>
                    <div class="cell">
                        <span style="background: {{ $enrollment->status === 'active' ? 'rgba(16, 185, 129, 0.12)' : 'rgba(107, 114, 128, 0.12)' }}; color: {{ $enrollment->status === 'active' ? '#10b981' : '#6b7280' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">{{ ucfirst($enrollment->status) }}</span>
                    </div>
                    <div class="cell" style="text-align: right;">
                        @if($enrollment->grade)
                            <strong>{{ $enrollment->grade }}/20</strong>
                        @else
                            <span style="color: var(--text-dark-secondary);">Pending</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding: var(--spacing-lg); text-align: center; color: var(--text-dark-secondary); grid-column: 1 / -1;">
                    No enrolled courses
                </div>
            @endforelse
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Class Schedule</h3>
            <span class="chip">{{ $timetables->count() }} classes</span>
        </div>

        @if($timetables->isEmpty())
            <div style="padding: 2rem; text-align: center; color: var(--text-dark-secondary);">
                <p>No scheduled classes found for your enrolled courses.</p>
            </div>
        @else
            <div class="schedule-grid {{ ($viewMode ?? request('view', 'detailed')) === 'compact' ? 'schedule-compact' : '' }}">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    $groupedTimetables = $timetables->groupBy('day_of_week');
                @endphp
                @foreach($days as $day)
                    <div class="schedule-day-column">
                        <h4 class="day-header">{{ $day }}</h4>
                        <div class="schedule-slots">
                            @forelse($groupedTimetables->get($day, []) as $timetable)
                                <div class="schedule-slot">
                                    <div class="slot-time">
                                        {{ $timetable->start_time->format('H:i') }}
                                        <span class="slot-duration">- {{ $timetable->end_time->format('H:i') }}</span>
                                    </div>
                                    <div class="slot-details">
                                        <p class="slot-subject">{{ $timetable->teacherSubject?->subject?->name }}</p>
                                        <p class="slot-teacher">Prof. {{ $timetable->teacherSubject?->teacher?->name }}</p>
                                        @if($timetable->room)
                                            <p class="slot-info">{{ $timetable->room }}@if($timetable->building) • {{ $timetable->building }}@endif</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="no-classes">-</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="dashboard-grid detail-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Course Details</h3>
                <span class="chip">{{ now()->format('F d, Y') }}</span>
            </div>
            <div class="timeline">
                @forelse($enrolledCourses->take(3) as $enrollment)
                <div class="timeline-row">
                    <div class="timeline-dot" style="border-color: {{ $enrollment->grade ? ($enrollment->grade >= 17 ? '#10b981' : '#0ea5e9') : '#9ca3af' }};"></div>
                    <div>
                        <p class="item-title">{{ $enrollment->course->name }}</p>
                        <span class="item-sub">{{ $enrollment->course->code ?? 'N/A' }} • {{ $enrollment->course->department }}</span>
                    </div>
                    @if($enrollment->grade)
                        <span class="badge badge-success">{{ $enrollment->grade }}/20</span>
                    @else
                        <span class="badge badge-secondary">No grade</span>
                    @endif
                </div>
                @empty
                <div style="padding: var(--spacing-md); color: var(--text-dark-secondary); text-align: center;">
                    No enrolled courses
                </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Course Status Summary</h3>
                <a href="{{ route('dashboard.student.subjects') }}" class="card-link">See all →</a>
            </div>
            <div class="tasks-list">
                @forelse($enrolledCourses as $enrollment)
                <div class="task-item">
                    <div>
                        <p class="item-title">{{ $enrollment->course->name }}</p>
                        <span class="item-sub">{{ $enrollment->course->credits }} credits • {{ ucfirst($enrollment->status) }}</span>
                    </div>
                    @if($enrollment->grade)
                        <span class="badge badge-success">{{ $enrollment->grade }}/20</span>
                    @else
                        <span class="badge badge-secondary">Pending</span>
                    @endif
                </div>
                @empty
                <div style="padding: var(--spacing-md); color: var(--text-dark-secondary); text-align: center;">
                    No enrolled courses
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.schedule-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.filters-card { display: flex; gap: var(--spacing-lg); justify-content: space-between; align-items: flex-end; }
.filters-form { display: flex; gap: var(--spacing-lg); justify-content: space-between; align-items: flex-end; width: 100%; }
.filters-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
.field select option {
    background: var(--bg-dark);
    color: var(--text-dark);
    padding: 8px;
}
.field select option:checked {
    background: var(--primary);
    color: white;
}
.filters-actions { display: flex; gap: var(--spacing-md); }

.week-grid { display: flex; flex-direction: column; gap: var(--spacing-md); }
.chip { padding: 6px 12px; border-radius: 999px; border: 1px solid var(--border-dark); color: var(--text-dark-secondary); background: rgba(255, 255, 255, 0.04); font-size: 0.85rem; }
.week-table { display: flex; flex-direction: column; gap: var(--spacing-sm); }
.week-row { display: grid; grid-template-columns: 120px repeat(5, 1fr); gap: var(--spacing-sm); }
.week-head { color: var(--text-dark-secondary); font-weight: 600; }
.slot { color: var(--text-dark-secondary); font-weight: 600; }
.cell { padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); color: var(--text-dark); }
.cell.empty { color: var(--text-dark-secondary); opacity: 0.5; text-align: center; }

.detail-grid { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
.timeline { display: flex; flex-direction: column; gap: var(--spacing-md); }
.timeline-row { display: grid; grid-template-columns: auto 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.timeline-dot { width: 14px; height: 14px; border-radius: 50%; border: 3px solid var(--border-dark); }
.item-title { color: var(--text-dark); font-weight: 600; }
.item-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.tasks-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.task-item { display: grid; grid-template-columns: 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-secondary { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }

.schedule-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
}

.schedule-day-column {
    display: flex;
    flex-direction: column;
}

.day-header {
    font-weight: 600;
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--primary-color);
    margin-bottom: var(--spacing-md);
    color: var(--text-dark);
    font-size: 0.95rem;
}

.schedule-slots {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.schedule-compact .schedule-slot {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: 0.8rem;
}

.schedule-compact .slot-time {
    font-size: 0.75rem;
}

.schedule-compact .slot-subject {
    font-size: 0.8rem;
}

.schedule-slot {
    background: linear-gradient(135deg, var(--bg-light), var(--bg-lighter));
    border-left: 3px solid var(--primary-color);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-sm);
    transition: all 0.2s ease;
}

.schedule-slot:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateX(2px);
}

.slot-time {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 0.8rem;
    margin-bottom: var(--spacing-xs);
}

.slot-duration {
    font-weight: normal;
    color: var(--text-dark-secondary);
    font-size: 0.7rem;
}

.slot-details {
    margin-top: var(--spacing-xs);
}

.slot-subject {
    margin: 0;
    font-weight: 500;
    font-size: 0.85rem;
    color: var(--text-dark);
}

.slot-teacher {
    margin: var(--spacing-xs) 0 0;
    font-size: 0.75rem;
    color: var(--text-dark-secondary);
}

.slot-info {
    margin: var(--spacing-xs) 0 0;
    color: var(--text-dark-secondary);
    font-size: 0.7rem;
}

.no-classes {
    color: var(--text-dark-secondary);
    font-size: 0.8rem;
    text-align: center;
    padding: var(--spacing-md);
    margin: 0;
}

@media (max-width: 960px) {
    .filters-card { flex-direction: column; align-items: stretch; }
    .filters-form { flex-direction: column; align-items: stretch; }
    .week-row { grid-template-columns: repeat(2, 1fr); }
    .slot { grid-column: 1 / -1; }
    .timeline-row, .task-item { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
