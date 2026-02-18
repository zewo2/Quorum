@extends('layouts.be_master')

@section('title', 'My Attendance - Quorum')
@section('page-title', 'My Attendance')

@section('content')
<div class="attendance-page">
    <div class="dashboard-card stats-grid">
        <div class="stat-box">
            <p class="stat-label">Attendance Rate</p>
            <p class="stat-value">{{ $attendanceRate }}%</p>
            <span class="stat-meta">Present + Late sessions</span>
        </div>
        <div class="stat-box">
            <p class="stat-label">Total Sessions</p>
            <p class="stat-value">{{ $totalSessions }}</p>
            <span class="stat-meta">Recorded sessions</span>
        </div>
        <div class="stat-box">
            <p class="stat-label">Present</p>
            <p class="stat-value">{{ $presentCount }}</p>
            <span class="stat-meta">On time</span>
        </div>
        <div class="stat-box">
            <p class="stat-label">Late</p>
            <p class="stat-value">{{ $lateCount }}</p>
            <span class="stat-meta">Late check-ins</span>
        </div>
        <div class="stat-box">
            <p class="stat-label">Absent</p>
            <p class="stat-value">{{ $absentCount }}</p>
            <span class="stat-meta">Missed sessions</span>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Attendance History</h3>
            <a href="{{ route('dashboard.student.schedule') }}" class="card-link">View schedule →</a>
        </div>

        <div class="table-wrap">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Session</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceRecords as $record)
                        <tr>
                            <td>{{ $record->date?->format('M d, Y') ?? '-' }}</td>
                            <td>{{ $record->enrollment?->course?->name ?? 'N/A' }}</td>
                            <td>{{ $record->teacher?->name ?? 'N/A' }}</td>
                            <td>{{ $record->session ?? '-' }}</td>
                            <td>
                                <span class="status-pill status-{{ $record->status }}">{{ ucfirst($record->status) }}</span>
                            </td>
                            <td>{{ $record->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-row">No attendance records available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.attendance-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--spacing-md); }
.stat-box { padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-dark); border-radius: var(--radius-md); }
.stat-label { color: var(--text-dark-secondary); }
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-dark); }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.85rem; }

.table-wrap { overflow-x: auto; border: 1px solid var(--border-dark); border-radius: var(--radius-md); }
.attendance-table { width: 100%; border-collapse: collapse; min-width: 760px; }
.attendance-table thead { background: rgba(255, 255, 255, 0.03); }
.attendance-table th,
.attendance-table td { padding: var(--spacing-md); border-bottom: 1px solid var(--border-dark); text-align: left; }
.attendance-table th { color: var(--text-dark); font-weight: 700; }
.attendance-table td { color: var(--text-dark-secondary); }
.attendance-table tbody tr:hover { background: rgba(255, 255, 255, 0.02); }

.status-pill { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.status-present { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.status-late { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.status-absent { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

.empty-row { text-align: center; color: var(--text-dark-secondary); }
</style>
@endpush
