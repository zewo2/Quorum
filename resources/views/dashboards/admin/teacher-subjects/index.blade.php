@extends('layouts.be_master')

@section('title', 'Teacher Subjects - Quorum')
@section('page-title', 'Teacher Subject Assignments')

@section('content')
<div class="assignments-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="assignments-header">
        <form method="GET" action="{{ route('dashboard.admin.teacher-subjects.index') }}" class="filter-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search teachers or subjects" class="filter-input">
            <select name="teacher_id" class="filter-select">
                <option value="">All teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ (string)request('teacher_id') === (string)$teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            <select name="subject_id" class="filter-select">
                <option value="">All subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ (string)request('subject_id') === (string)$subject->id ? 'selected' : '' }}>
                        {{ $subject->code }} - {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            <input type="number" name="academic_year" value="{{ request('academic_year') }}" class="filter-input" placeholder="Year (e.g. 2024)">
            <select name="status" class="filter-select">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'teacher_id', 'subject_id', 'academic_year', 'status']))
                <a href="{{ route('dashboard.admin.teacher-subjects.index') }}" class="btn btn-ghost">Clear</a>
            @endif
        </form>

        <a href="{{ route('dashboard.admin.teacher-subjects.create') }}" class="btn btn-primary">Assign Teacher</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Subject</th>
                    <th>Year</th>
                    <th>Semester</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td>
                            <div class="cell-stack">
                                <span class="cell-title">{{ $assignment->teacher->name ?? 'Unknown teacher' }}</span>
                                <span class="cell-sub">{{ $assignment->teacher->email ?? '' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cell-stack">
                                <span class="cell-title">{{ $assignment->subject->name ?? 'Unknown subject' }}</span>
                                <span class="cell-sub">{{ $assignment->subject->code ?? '' }}</span>
                            </div>
                        </td>
                        <td>{{ $assignment->academic_year }}</td>
                        <td>Semester {{ $assignment->semester }}</td>
                        <td>{{ $assignment->class_capacity }}</td>
                        <td><span class="status-badge status-{{ $assignment->status }}">{{ ucfirst($assignment->status) }}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('dashboard.admin.teacher-subjects.edit', $assignment) }}" class="action-btn" title="Edit">Edit</a>
                                <form method="POST" action="{{ route('dashboard.admin.teacher-subjects.destroy', $assignment) }}" class="inline-form" onsubmit="return confirm('Remove this assignment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No assignments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($assignments->hasPages())
        <div class="pagination-container">
            {{ $assignments->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.assignments-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }

.alert { padding: var(--spacing-sm) var(--spacing-md); border-radius: var(--radius-md); border: 1px solid var(--border-dark); }
.alert-success { color: #10b981; background: rgba(16, 185, 129, 0.12); }
.alert-error { color: #ef4444; background: rgba(239, 68, 68, 0.12); }

.assignments-header { display: flex; align-items: center; justify-content: space-between; gap: var(--spacing-md); flex-wrap: wrap; }
.filter-bar { display: flex; gap: var(--spacing-sm); flex: 1; min-width: 320px; flex-wrap: wrap; }
.filter-input, .filter-select {
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    padding: var(--spacing-sm) var(--spacing-md);
    font-family: inherit;
    min-width: 170px;
}
.btn-ghost { background: transparent; color: var(--text-dark-secondary); border: 1px solid var(--border-dark); }

.table-card { background: var(--bg-dark-secondary); border: 1px solid var(--border-dark); border-radius: var(--radius-lg); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: var(--spacing-md) var(--spacing-lg); border-bottom: 1px solid var(--border-dark); text-align: left; }
.data-table th { background: rgba(255, 255, 255, 0.03); font-weight: 600; color: var(--text-dark); }
.data-table tbody tr:hover { background: rgba(255, 255, 255, 0.02); }

.cell-stack { display: flex; flex-direction: column; gap: 4px; }
.cell-title { color: var(--text-dark); font-weight: 600; }
.cell-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.status-badge { display: inline-block; padding: 4px 10px; border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 600; text-transform: capitalize; }
.status-active { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.status-inactive { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

.action-buttons { display: flex; gap: var(--spacing-sm); }
.action-btn { padding: 6px 10px; border-radius: var(--radius-md); border: 1px solid var(--border-dark); background: transparent; color: var(--text-dark-secondary); font-size: 0.9rem; }
.action-btn:hover { background: var(--primary); color: white; }
.action-btn-danger:hover { background: var(--danger); color: white; }
.inline-form { display: inline; }
.empty-state { text-align: center; color: var(--text-dark-secondary); padding: var(--spacing-xl); }

.pagination-container { display: flex; justify-content: flex-end; }

@media (max-width: 900px) {
    .data-table th:nth-child(5), .data-table td:nth-child(5) { display: none; }
}
</style>
@endpush
@endsection
