@extends('layouts.be_master')

@section('title', 'Enrollments - Quorum')
@section('page-title', 'Enrollments')

@section('content')
<div class="enrollments-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="enrollments-header">
        <form method="GET" action="{{ route('dashboard.admin.enrollments.index') }}" class="filter-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students or courses" class="filter-input">
            <select name="course_id" class="filter-select">
                <option value="">All courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (string)request('course_id') === (string)$course->id ? 'selected' : '' }}>
                        {{ $course->code }} - {{ $course->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="filter-select">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'course_id', 'status']))
                <a href="{{ route('dashboard.admin.enrollments.index') }}" class="btn btn-ghost">Clear</a>
            @endif
        </form>
        <a href="{{ route('dashboard.admin.enrollments.create') }}" class="btn btn-primary">New Enrollment</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Final Grade</th>
                    <th>Updated</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>
                            <div class="cell-stack">
                                <span class="cell-title">{{ $enrollment->student->name ?? 'Unknown student' }}</span>
                                <span class="cell-sub">{{ $enrollment->student->email ?? 'No email' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cell-stack">
                                <span class="cell-title">{{ $enrollment->course->name ?? 'Unknown course' }}</span>
                                <span class="cell-sub">{{ $enrollment->course->code ?? '' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $enrollment->status }}">{{ ucfirst($enrollment->status) }}</span>
                        </td>
                        <td>{{ isset($enrollment->final_grade) ? number_format((float)$enrollment->final_grade, 2) : '—' }}</td>
                        <td>{{ $enrollment->updated_at?->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('dashboard.admin.enrollments.edit', $enrollment) }}" class="action-btn" title="Edit">Edit</a>
                                <form method="POST" action="{{ route('dashboard.admin.enrollments.destroy', $enrollment) }}" class="inline-form" onsubmit="return confirm('Remove this enrollment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">No enrollments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($enrollments->hasPages())
        <div class="pagination-container">
            {{ $enrollments->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.enrollments-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.alert {
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-dark);
}

.alert-success { color: #10b981; background: rgba(16, 185, 129, 0.12); }
.alert-error { color: #ef4444; background: rgba(239, 68, 68, 0.12); }

.enrollments-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.filter-bar {
    display: flex;
    gap: var(--spacing-sm);
    flex: 1;
    min-width: 320px;
    flex-wrap: wrap;
}

.filter-input,
.filter-select {
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    padding: var(--spacing-sm) var(--spacing-md);
    font-family: inherit;
    min-width: 180px;
}

.btn-ghost {
    background: transparent;
    color: var(--text-dark-secondary);
    border: 1px solid var(--border-dark);
}

.table-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: var(--spacing-md) var(--spacing-lg);
    border-bottom: 1px solid var(--border-dark);
    text-align: left;
}

.data-table th {
    background: rgba(255, 255, 255, 0.03);
    font-weight: 600;
    color: var(--text-dark);
}

.data-table tbody tr:hover { background: rgba(255, 255, 255, 0.02); }

.cell-stack { display: flex; flex-direction: column; gap: 4px; }
.cell-title { color: var(--text-dark); font-weight: 600; }
.cell-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-active { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.status-completed { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.status-withdrawn { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

.action-buttons { display: flex; gap: var(--spacing-sm); }
.action-btn {
    padding: 6px 10px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-dark);
    background: transparent;
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}
.action-btn:hover { background: var(--primary); color: white; }
.action-btn-danger:hover { background: var(--danger); color: white; }

.inline-form { display: inline; }
.empty-state { text-align: center; color: var(--text-dark-secondary); padding: var(--spacing-xl); }

.pagination-container { display: flex; justify-content: flex-end; }

@media (max-width: 900px) {
    .data-table th:nth-child(5),
    .data-table td:nth-child(5) { display: none; }
}
</style>
@endpush
@endsection
