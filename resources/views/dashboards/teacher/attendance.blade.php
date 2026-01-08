@extends('layouts.be_master')

@section('title', 'Attendance - Quorum')
@section('page-title', 'Attendance')

@section('content')
<div class="attendance-page">
    <div class="dashboard-card header-card">
        <div class="header-left">
            <label class="field">
                <span>Class</span>
                <select>
                    <option>Web Development • CS210 • Room A-204</option>
                    <option>Database Systems • CS330 • Lab C-305</option>
                    <option>Advanced Programming • CS360 • Room B-101</option>
                </select>
            </label>
            <label class="field">
                <span>Date</span>
                <input type="date" value="2026-01-08">
            </label>
            <label class="field">
                <span>Session</span>
                <select>
                    <option>09:00 - 10:30</option>
                    <option>11:00 - 12:30</option>
                    <option>14:00 - 15:30</option>
                </select>
            </label>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary">Reset</button>
            <button class="btn btn-primary">Save attendance</button>
        </div>
    </div>

    <div class="dashboard-grid summary-grid">
        <div class="dashboard-card summary-card">
            <p class="stat-label">Present</p>
            <p class="stat-value">25</p>
            <span class="stat-meta">89% of class</span>
        </div>
        <div class="dashboard-card summary-card">
            <p class="stat-label">Late</p>
            <p class="stat-value">2</p>
            <span class="stat-meta">Arrived after 09:10</span>
        </div>
        <div class="dashboard-card summary-card">
            <p class="stat-label">Absent</p>
            <p class="stat-value">1</p>
            <span class="stat-meta">Notified: 0</span>
        </div>
    </div>

    <div class="dashboard-card attendance-card">
        <div class="card-header">
            <div>
                <h3>Roster</h3>
                <p class="card-sub">Tap a status to mark attendance. All data is hardcoded.</p>
            </div>
            <div class="legend">
                <span class="badge badge-success">P</span>
                <span class="legend-label">Present</span>
                <br>
                <span class="badge badge-warning">L</span>
                <span class="legend-label">Late</span>
                <br>
                <span class="badge badge-absent">A</span>
                <span class="legend-label">Absent</span>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([['Maria Silva','ST20314','Computer Science','Present',''], ['Joao Costa','ST19877','Computer Science','Late','Traffic'], ['Ines Rocha','ST20544','Computer Science','Present',''], ['Ricardo Pereira','ST20011','Computer Science','Absent',''], ['Ana Matos','ST20402','Computer Science','Present','']] as $row)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($row[0], 0, 2)) }}</div>
                                    <div>
                                        <p class="item-title">{{ $row[0] }}</p>
                                        <span class="item-sub">Year 2</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                            <td>
                                <div class="status-toggle">
                                    <button class="pill {{ $row[3] === 'Present' ? 'active' : '' }}">P</button>
                                    <button class="pill {{ $row[3] === 'Late' ? 'active warning' : '' }}">L</button>
                                    <button class="pill {{ $row[3] === 'Absent' ? 'active danger' : '' }}">A</button>
                                </div>
                            </td>
                            <td>
                                <div class="notes-box">{{ $row[4] ?: '—' }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Attendance Trend</h3>
            <span class="chip">Last 5 sessions</span>
        </div>
        <div class="trend-grid">
            <div class="trend-bar">
                <div class="bar" style="height: 92%;"></div>
                <span>Mon</span>
            </div>
            <div class="trend-bar">
                <div class="bar" style="height: 88%;"></div>
                <span>Tue</span>
            </div>
            <div class="trend-bar">
                <div class="bar" style="height: 94%;"></div>
                <span>Wed</span>
            </div>
            <div class="trend-bar">
                <div class="bar" style="height: 86%;"></div>
                <span>Thu</span>
            </div>
            <div class="trend-bar">
                <div class="bar" style="height: 91%;"></div>
                <span>Fri</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.attendance-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.header-card { display: flex; gap: var(--spacing-lg); justify-content: space-between; align-items: flex-end; }
.header-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field input, .field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
.header-actions { display: flex; gap: var(--spacing-md); }

.summary-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
.summary-card { border: 1px solid var(--border-dark); }
.stat-label { color: var(--text-dark-secondary); }
.stat-value { color: var(--text-dark); font-size: 1.8rem; font-weight: 700; }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.9rem; }

.attendance-card .card-sub { color: var(--text-dark-secondary); margin-top: 4px; }
.legend { display: grid; grid-template-columns: repeat(3, auto); gap: var(--spacing-sm); align-items: center; }
.legend-label { color: var(--text-dark-secondary); font-size: 0.9rem; }

.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: var(--spacing-md); border-bottom: 1px solid var(--border-dark); text-align: left; }
.data-table th { color: var(--text-dark); background: rgba(255, 255, 255, 0.03); font-weight: 600; }
.user-cell { display: flex; align-items: center; gap: var(--spacing-md); }
.avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.status-toggle { display: inline-flex; gap: 6px; }
.pill { padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border-dark); background: rgba(255, 255, 255, 0.02); color: var(--text-dark-secondary); cursor: pointer; }
.pill.active { background: rgba(16, 185, 129, 0.12); color: #10b981; border-color: rgba(16, 185, 129, 0.6); }
.pill.active.warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; border-color: rgba(245, 158, 11, 0.5); }
.pill.active.danger { background: rgba(239, 68, 68, 0.12); color: #ef4444; border-color: rgba(239, 68, 68, 0.5); }
.notes-box { padding: var(--spacing-sm) var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); color: var(--text-dark-secondary); }

.chip { padding: 6px 12px; border: 1px solid var(--border-dark); border-radius: 999px; background: rgba(255, 255, 255, 0.04); color: var(--text-dark-secondary); font-size: 0.85rem; }
.trend-grid { display: grid; grid-template-columns: repeat(5, minmax(40px, 1fr)); gap: var(--spacing-md); align-items: end; height: 180px; }
.trend-bar { display: flex; flex-direction: column; align-items: center; gap: var(--spacing-sm); }
.bar { width: 100%; border-radius: var(--radius-md); background: linear-gradient(180deg, var(--primary), var(--primary-dark)); }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-absent { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

@media (max-width: 980px) {
    .header-card { flex-direction: column; align-items: stretch; }
    .legend { grid-template-columns: repeat(2, auto); row-gap: var(--spacing-xs); }
}
</style>
@endpush
@endsection
