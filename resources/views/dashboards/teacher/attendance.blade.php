@extends('layouts.be_master')

@section('title', 'Attendance - Quorum')
@section('page-title', 'Attendance')

@section('content')
<div class="attendance-page">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.teacher.attendance.store') }}" id="attendanceForm">
        @csrf
        <input type="hidden" name="subject" value="{{ $selectedSubject?->id }}">

        <div class="dashboard-card header-card">
            <div class="header-left">
                <label class="field">
                    <span>Class</span>
                    <select name="subject_select" onchange="updateFilters()">
                        @forelse($teacherSubjects as $subject)
                            @php
                                $subjectCourses = $subject->courses;
                            @endphp
                            <option value="{{ $subject->id }}" {{ $selectedSubject?->id === $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} • {{ $subjectCourses->pluck('name')->join(', ') ?: 'General' }}
                            </option>
                        @empty
                            <option disabled>No classes available</option>
                        @endforelse
                    </select>
                </label>
                <label class="field">
                    <span>Date</span>
                    <input type="date" name="date" value="{{ $date }}" onchange="updateFilters()">
                </label>
                <label class="field">
                    <span>Session</span>
                    <select name="session" onchange="updateFilters()">
                        @if($availableSessions->isEmpty())
                            <option value="" disabled selected>No sessions scheduled</option>
                        @else
                            @foreach($availableSessions as $sess)
                                <option value="{{ $sess['key'] }}" {{ $session === $sess['key'] ? 'selected' : '' }}>
                                    {{ $sess['display'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </label>
            </div>
            <div class="header-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">Reset</button>
                <button type="submit" class="btn btn-primary">Save attendance</button>
            </div>
        </div>

        @if($selectedSubject)
            <div class="dashboard-grid summary-grid">
                <div class="dashboard-card summary-card">
                    <p class="stat-label">Present</p>
                    <p class="stat-value" id="presentCount">{{ $presentCount }}</p>
                    <span class="stat-meta">{{ $enrollments->count() > 0 ? round(($presentCount / $enrollments->count()) * 100) : 0 }}% of class</span>
                </div>
                <div class="dashboard-card summary-card">
                    <p class="stat-label">Late</p>
                    <p class="stat-value" id="lateCount">{{ $lateCount }}</p>
                    <span class="stat-meta">Arrived after start</span>
                </div>
                <div class="dashboard-card summary-card">
                    <p class="stat-label">Absent</p>
                    <p class="stat-value" id="absentCount">{{ $absentCount }}</p>
                    <span class="stat-meta">Not present</span>
                </div>
            </div>

            <div class="dashboard-card attendance-card">
                <div class="card-header">
                    <div>
                        <h3>Roster - {{ $selectedSubject->name }}</h3>
                        <p class="card-sub">{{ $enrollments->count() }} students enrolled</p>
                    </div>
                    <div class="legend">
                        <div class="legend-item">
                            <span class="badge badge-success">P</span>
                            <span class="legend-label">Present</span>
                        </div>
                        <div class="legend-item">
                            <span class="badge badge-warning">L</span>
                            <span class="legend-label">Late</span>
                        </div>
                        <div class="legend-item">
                            <span class="badge badge-absent">A</span>
                            <span class="legend-label">Absent</span>
                        </div>
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
                            @forelse($enrollments as $enrollment)
                                @php
                                    $existingAttendance = $enrollment->attendances->first();
                                    $status = $existingAttendance?->status ?? 'present';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <div class="avatar">{{ strtoupper(substr($enrollment->user->name, 0, 2)) }}</div>
                                            <div>
                                                <p class="item-title">{{ $enrollment->user->name }}</p>
                                                <span class="item-sub">{{ $enrollment->user->role ?? 'Student' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->user->student_id ?? 'N/A' }}</td>
                                    <td>{{ $enrollment->course?->name ?? 'General' }}</td>
                                    <td>
                                        <input type="hidden" name="attendance[{{ $loop->index }}][enrollment_id]" value="{{ $enrollment->id }}">
                                        <div class="status-toggle" data-enrollment="{{ $enrollment->id }}">
                                            <button type="button" class="pill status-btn {{ $status === 'present' ? 'active' : '' }}" data-status="present" onclick="setStatus(this)">P</button>
                                            <button type="button" class="pill status-btn {{ $status === 'late' ? 'active warning' : '' }}" data-status="late" onclick="setStatus(this)">L</button>
                                            <button type="button" class="pill status-btn {{ $status === 'absent' ? 'active danger' : '' }}" data-status="absent" onclick="setStatus(this)">A</button>
                                        </div>
                                        <input type="hidden" name="attendance[{{ $loop->index }}][status]" value="{{ $status }}" class="status-input">
                                    </td>
                                    <td>
                                        <input type="text" name="attendance[{{ $loop->index }}][notes]" value="{{ $existingAttendance?->notes ?? '' }}" class="notes-input" placeholder="Optional notes">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: var(--spacing-lg); color: var(--text-dark-secondary);">
                                        No students enrolled in this class yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="dashboard-card" style="text-align: center; padding: 3rem;">
                <p style="color: var(--text-dark-secondary);">Select a class above to view and manage attendance.</p>
            </div>
        @endif
    </form>
</div>

@push('styles')
<style>
.attendance-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.header-card { display: flex; gap: var(--spacing-lg); justify-content: space-between; align-items: flex-end; }
.header-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field input, .field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
.field select option {
    background: var(--bg-dark);
    color: var(--text-dark);
    padding: 8px;
}
.field select option:checked {
    background: var(--primary);
    color: white;
}
.header-actions { display: flex; gap: var(--spacing-md); }

.alert { padding: var(--spacing-md); border-radius: var(--radius-md); margin-bottom: var(--spacing-lg); }
.alert-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }

.summary-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
.summary-card { border: 1px solid var(--border-dark); }
.stat-label { color: var(--text-dark-secondary); }
.stat-value { color: var(--text-dark); font-size: 1.8rem; font-weight: 700; }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.9rem; }

.attendance-card .card-sub { color: var(--text-dark-secondary); margin-top: 4px; }
.legend { display: flex; flex-direction: column; gap: var(--spacing-md); }
.legend-item { display: flex; align-items: center; gap: 8px; }
.legend-label { color: var(--text-dark-secondary); font-size: 0.9rem; }

.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: var(--spacing-md); border-bottom: 1px solid var(--border-dark); text-align: left; }
.data-table th { color: var(--text-dark); background: rgba(255, 255, 255, 0.03); font-weight: 600; }
.user-cell { display: flex; align-items: center; gap: var(--spacing-md); }
.avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; }
.status-toggle { display: inline-flex; gap: 6px; }
.pill { padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border-dark); background: rgba(255, 255, 255, 0.02); color: var(--text-dark-secondary); cursor: pointer; transition: all 0.2s; }
.pill:hover { background: rgba(255, 255, 255, 0.05); }
.pill.active { background: rgba(16, 185, 129, 0.12); color: #10b981; border-color: rgba(16, 185, 129, 0.6); }
.pill.active.warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; border-color: rgba(245, 158, 11, 0.5); }
.pill.active.danger { background: rgba(239, 68, 68, 0.12); color: #ef4444; border-color: rgba(239, 68, 68, 0.5); }
.notes-input { padding: var(--spacing-sm) var(--spacing-md); background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); width: 100%; max-width: 250px; }
.notes-input:focus { outline: none; border-color: var(--primary); }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-absent { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

@media (max-width: 980px) {
    .header-card { flex-direction: column; align-items: stretch; }
}
</style>
@endpush

@push('scripts')
<script>
function setStatus(button) {
    const toggle = button.closest('.status-toggle');
    const statusInput = button.closest('tr').querySelector('.status-input');
    const status = button.dataset.status;

    toggle.querySelectorAll('.status-btn').forEach(btn => {
        btn.classList.remove('active', 'warning', 'danger');
    });

    button.classList.add('active');
    if (status === 'late') {
        button.classList.add('warning');
    } else if (status === 'absent') {
        button.classList.add('danger');
    }

    statusInput.value = status;

    updateCounts();
}

function updateCounts() {
    const statusInputs = document.querySelectorAll('.status-input');
    let present = 0, late = 0, absent = 0;

    statusInputs.forEach(input => {
        if (input.value === 'present') present++;
        else if (input.value === 'late') late++;
        else if (input.value === 'absent') absent++;
    });

    document.getElementById('presentCount').textContent = present;
    document.getElementById('lateCount').textContent = late;
    document.getElementById('absentCount').textContent = absent;
}

function updateFilters() {
    const form = document.getElementById('attendanceForm');
    const subject = form.querySelector('[name="subject_select"]').value;
    const date = form.querySelector('[name="date"]').value;
    const session = form.querySelector('[name="session"]').value;

    window.location.href = `?subject=${subject}&date=${date}&session=${session}`;
}
</script>
@endpush
@endsection
