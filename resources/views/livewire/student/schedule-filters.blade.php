<div class="schedule-page">
    <div class="dashboard-card filters-card">
        <div class="filters-form">
            <div class="filters-left">
                <label class="field">
                    <span>Enrolled Courses</span>
                    <select wire:model.live="status">
                        <option value="all">All courses</option>
                        <option value="active">Active only</option>
                    </select>
                </label>
                <div class="filters-actions primary-actions">
                    <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
                </div>
            </div>
            <div class="filters-actions quick-links">
                <a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary">My Subjects</a>
                <a href="{{ route('dashboard.student.grades') }}" class="btn btn-secondary">My Grades</a>
            </div>
        </div>
    </div>

    <div class="dashboard-card week-grid">
        <div class="card-header">
            <h3>Enrolled Courses</h3>
            <span class="chip">{{ $enrolledCourses->count() }} courses</span>
        </div>
        <div class="week-table">
            <div class="week-row week-head">
                <div class="slot">Course</div>
                <div>Code</div><div>Department</div><div>Total Years</div><div>Status</div><div>Grade</div>
            </div>
            @forelse($enrolledCourses as $enrollment)
                <div class="week-row">
                    <div class="slot">{{ $enrollment->course->name }}</div>
                    <div class="cell">{{ $enrollment->course->code ?? 'N/A' }}</div>
                    <div class="cell">{{ $enrollment->course->department }}</div>
                    <div class="cell">{{ $enrollment->course->total_years }} years</div>
                    <div class="cell">
                        <span style="background: {{ $enrollment->status === 'active' ? 'rgba(16, 185, 129, 0.12)' : 'rgba(107, 114, 128, 0.12)' }}; color: {{ $enrollment->status === 'active' ? '#10b981' : '#6b7280' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">{{ ucfirst($enrollment->status) }}</span>
                    </div>
                    <div class="cell" style="text-align: right;">
                        @if($enrollment->final_grade)
                            <strong>{{ $enrollment->final_grade }}/20</strong>
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
            <div class="schedule-grid {{ $viewMode === 'compact' ? 'schedule-compact' : '' }}">
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
                                        {{ substr((string) $timetable->start_time, 0, 5) }}
                                        <span class="slot-duration">- {{ substr((string) $timetable->end_time, 0, 5) }}</span>
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
</div>
