@extends('layouts.be_master')

@section('title', 'My Schedule - Quorum')
@section('page-title', 'My Schedule')

@section('content')
<div class="schedule-page">
    <div class="dashboard-card filters-card">
        <div class="filters-left">
            <label class="field">
                <span>Enrolled Courses</span>
                <select>
                    <option>All courses</option>
                    <option>Active only</option>
                </select>
            </label>
            <label class="field">
                <span>View</span>
                <select>
                    <option>Detailed</option>
                    <option>Compact</option>
                </select>
            </label>
        </div>
        <div class="filters-actions">
            <a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary">My Subjects</a>
            <a href="{{ route('dashboard.student.grades') }}" class="btn btn-secondary">My Grades</a>
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
.filters-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
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

@media (max-width: 960px) {
    .filters-card { flex-direction: column; align-items: stretch; }
    .week-row { grid-template-columns: repeat(2, 1fr); }
    .slot { grid-column: 1 / -1; }
    .timeline-row, .task-item { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
