@extends('layouts.be_master')

@section('title', 'My Classes - Quorum')
@section('page-title', 'My Classes')

@section('content')
<div class="classes-page">
    <div class="dashboard-card filters-card">
        <div class="filters-left">
            <label class="field">
                <span>Term</span>
                <select>
                    <option>Spring 2026</option>
                    <option>Fall 2025</option>
                    <option>Summer 2025</option>
                </select>
            </label>
            <label class="field">
                <span>Search classes</span>
                <input type="text" placeholder="Search by course or code" value="Web">
            </label>
        </div>
        <div class="filters-actions">
            <button class="btn btn-secondary">Reset</button>
            <button class="btn btn-primary">Create session</button>
        </div>
    </div>

    <div class="dashboard-grid cards-grid">
        <div class="dashboard-card class-card">
            <div class="card-header">
                <div>
                    <p class="eyebrow">CS210 • Year 2</p>
                    <h3>Web Development</h3>
                </div>
                <span class="badge badge-success">28 students</span>
            </div>
            <p class="card-sub">Front-end frameworks and UI engineering</p>
            <div class="meta-row">
                <div class="meta">
                    <span>Schedule</span>
                    <strong>Mon & Wed • 09:00 - 10:30</strong>
                </div>
                <div class="meta">
                    <span>Room</span>
                    <strong>A-204</strong>
                </div>
                <div class="meta">
                    <span>Assignments</span>
                    <strong>3 open</strong>
                </div>
            </div>
            <div class="class-actions">
                <a href="{{ route('dashboard.teacher.attendance') }}" class="btn btn-secondary">Attendance</a>
                <button class="btn btn-primary">View roster</button>
            </div>
        </div>

        <div class="dashboard-card class-card">
            <div class="card-header">
                <div>
                    <p class="eyebrow">CS330 • Year 3</p>
                    <h3>Database Systems</h3>
                </div>
                <span class="badge badge-success">24 students</span>
            </div>
            <p class="card-sub">Relational modeling and SQL performance</p>
            <div class="meta-row">
                <div class="meta">
                    <span>Schedule</span>
                    <strong>Tue & Thu • 11:00 - 12:30</strong>
                </div>
                <div class="meta">
                    <span>Room</span>
                    <strong>Lab C-305</strong>
                </div>
                <div class="meta">
                    <span>Assignments</span>
                    <strong>1 open</strong>
                </div>
            </div>
            <div class="class-actions">
                <a href="{{ route('dashboard.teacher.attendance') }}" class="btn btn-secondary">Attendance</a>
                <button class="btn btn-primary">View roster</button>
            </div>
        </div>

        <div class="dashboard-card class-card">
            <div class="card-header">
                <div>
                    <p class="eyebrow">CS360 • Year 3</p>
                    <h3>Advanced Programming</h3>
                </div>
                <span class="badge badge-warning">32 students</span>
            </div>
            <p class="card-sub">Patterns, testing, and performance</p>
            <div class="meta-row">
                <div class="meta">
                    <span>Schedule</span>
                    <strong>Fri • 14:00 - 17:00</strong>
                </div>
                <div class="meta">
                    <span>Room</span>
                    <strong>B-101</strong>
                </div>
                <div class="meta">
                    <span>Assignments</span>
                    <strong>Grading in progress</strong>
                </div>
            </div>
            <div class="class-actions">
                <a href="{{ route('dashboard.teacher.attendance') }}" class="btn btn-secondary">Attendance</a>
                <button class="btn btn-primary">View roster</button>
            </div>
        </div>
    </div>

    <div class="dashboard-grid detail-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Today’s Sessions</h3>
                <span class="chip">Jan 8, 2026</span>
            </div>
            <div class="schedule-list">
                <div class="schedule-item">
                    <div class="schedule-time">09:00 - 10:30</div>
                    <div class="schedule-body">
                        <p class="item-title">Web Development</p>
                        <span class="item-sub">Room A-204 • 28 students</span>
                    </div>
                    <div class="row-actions">
                        <a href="{{ route('dashboard.teacher.attendance') }}" class="btn btn-secondary">Attendance</a>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-time">11:00 - 12:30</div>
                    <div class="schedule-body">
                        <p class="item-title">Database Systems</p>
                        <span class="item-sub">Lab C-305 • 24 students</span>
                    </div>
                    <div class="row-actions">
                        <a href="{{ route('dashboard.teacher.attendance') }}" class="btn btn-secondary">Attendance</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Pending Tasks</h3>
                <a href="#" class="card-link">View details →</a>
            </div>
            <div class="tasks-list">
                <div class="task-item">
                    <div class="task-icon">📝</div>
                    <div>
                        <p class="item-title">Grade Project 2</p>
                        <span class="item-sub">Web Development • 28 submissions</span>
                    </div>
                    <span class="badge badge-warning">Due Jan 12</span>
                </div>
                <div class="task-item">
                    <div class="task-icon">📊</div>
                    <div>
                        <p class="item-title">Upload midterm grades</p>
                        <span class="item-sub">Database Systems</span>
                    </div>
                    <span class="badge badge-success">In progress</span>
                </div>
                <div class="task-item">
                    <div class="task-icon">📋</div>
                    <div>
                        <p class="item-title">Attendance report</p>
                        <span class="item-sub">All classes</span>
                    </div>
                    <span class="badge badge-success">Auto-saving</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.classes-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.filters-card { display: flex; gap: var(--spacing-lg); align-items: flex-end; justify-content: space-between; }
.filters-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field input, .field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
.filters-actions { display: flex; gap: var(--spacing-md); }

.cards-grid { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
.class-card { display: flex; flex-direction: column; gap: var(--spacing-md); }
.eyebrow { color: var(--text-dark-secondary); font-size: 0.85rem; letter-spacing: 0.04em; text-transform: uppercase; }
.card-sub { color: var(--text-dark-secondary); }
.meta-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: var(--spacing-md); }
.meta span { color: var(--text-dark-secondary); font-size: 0.85rem; }
.meta strong { color: var(--text-dark); }
.class-actions { display: flex; gap: var(--spacing-md); margin-top: var(--spacing-sm); }

.detail-grid { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
.chip { padding: 6px 12px; border: 1px solid var(--border-dark); border-radius: 999px; background: rgba(255, 255, 255, 0.04); color: var(--text-dark-secondary); font-size: 0.85rem; }
.schedule-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.schedule-item { display: grid; grid-template-columns: 140px 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.schedule-time { font-weight: 700; color: var(--primary-light); }
.tasks-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.task-item { display: grid; grid-template-columns: auto 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.task-icon { font-size: 1.3rem; }
.item-title { color: var(--text-dark); font-weight: 600; }
.item-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }
.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }

@media (max-width: 960px) {
    .filters-card { flex-direction: column; align-items: stretch; }
    .schedule-item { grid-template-columns: 1fr; }
    .task-item { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
