@extends('layouts.be_master')

@section('title', 'My Schedule - Quorum')
@section('page-title', 'My Schedule')

@section('content')
<div class="schedule-page">
    <div class="dashboard-card filters-card">
        <div class="filters-left">
            <label class="field">
                <span>Week</span>
                <select>
                    <option>Jan 6 - Jan 12, 2026</option>
                    <option>Jan 13 - Jan 19, 2026</option>
                </select>
            </label>
            <label class="field">
                <span>View</span>
                <select>
                    <option>Compact</option>
                    <option>Detailed</option>
                </select>
            </label>
        </div>
        <div class="filters-actions">
            <a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary">Subjects</a>
            <button class="btn btn-primary">Export</button>
        </div>
    </div>

    <div class="dashboard-card week-grid">
        <div class="card-header">
            <h3>Week Overview</h3>
            <span class="chip">5 classes</span>
        </div>
        <div class="week-table">
            @php
                $slots = [
                    ['time' => '09:00', 'mon' => 'Web Dev (A-204)', 'tue' => '-', 'wed' => 'Web Dev (A-204)', 'thu' => '-', 'fri' => 'Data Ethics (B-101)'],
                    ['time' => '11:00', 'mon' => 'Data Structures (B-101)', 'tue' => 'Database Systems (C-305)', 'wed' => '-', 'thu' => 'Database Systems (C-305)', 'fri' => '-'],
                    ['time' => '14:00', 'mon' => '-', 'tue' => 'Linear Algebra (A-108)', 'wed' => '-', 'thu' => 'Advanced Programming (B-101)', 'fri' => '-'],
                ];
            @endphp

            <div class="week-row week-head">
                <div class="slot">Time</div>
                <div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div>
            </div>
            @foreach($slots as $slot)
                <div class="week-row">
                    <div class="slot">{{ $slot['time'] }}</div>
                    @foreach(['mon','tue','wed','thu','fri'] as $day)
                        <div class="cell {{ $slot[$day] === '-' ? 'empty' : '' }}">{{ $slot[$day] }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="dashboard-grid detail-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Today</h3>
                <span class="chip">January 8, 2026</span>
            </div>
            <div class="timeline">
                <div class="timeline-row">
                    <div class="timeline-dot" style="border-color: #22c55e;"></div>
                    <div>
                        <p class="item-title">Web Development</p>
                        <span class="item-sub">09:00 - 10:30 • Room A-204</span>
                    </div>
                    <span class="badge badge-success">Starts soon</span>
                </div>
                <div class="timeline-row">
                    <div class="timeline-dot" style="border-color: #0ea5e9;"></div>
                    <div>
                        <p class="item-title">Database Systems</p>
                        <span class="item-sub">11:00 - 12:30 • Lab C-305</span>
                    </div>
                    <span class="badge badge-secondary">Labs</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Upcoming Deadlines</h3>
                <a href="#" class="card-link">See all →</a>
            </div>
            <div class="tasks-list">
                <div class="task-item">
                    <div>
                        <p class="item-title">Project 2 submission</p>
                        <span class="item-sub">Web Development • Due Jan 15</span>
                    </div>
                    <span class="badge badge-warning">7 days</span>
                </div>
                <div class="task-item">
                    <div>
                        <p class="item-title">Quiz: Normalization</p>
                        <span class="item-sub">Database Systems • Due Jan 10</span>
                    </div>
                    <span class="badge badge-success">On track</span>
                </div>
                <div class="task-item">
                    <div>
                        <p class="item-title">Problem set #3</p>
                        <span class="item-sub">Data Structures • Due Jan 17</span>
                    </div>
                    <span class="badge badge-secondary">In progress</span>
                </div>
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
