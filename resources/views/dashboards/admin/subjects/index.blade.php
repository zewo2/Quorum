@extends('layouts.be_master')

@section('title', 'Subject Management - Quorum')
@section('page-title', 'Subject Management')

@section('content')
<div class="subjects-page">
    <div class="dashboard-grid stats-grid">
        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Subjects</p>
                <p class="stat-value">{{ $totalSubjects }}</p>
                <span class="stat-meta">Across all courses</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Credits</p>
                <p class="stat-value">{{ $totalCredits }}</p>
                <span class="stat-meta">Credit hours</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Courses</p>
                <p class="stat-value">{{ $courseCount }}</p>
                <span class="stat-meta">Total available</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Avg. Credits</p>
                <p class="stat-value">{{ $totalSubjects > 0 ? round($totalCredits / $totalSubjects, 1) : 0 }}</p>
                <span class="stat-meta">Per subject</span>
            </div>
        </div>
    </div>

    <div class="dashboard-card filters-card">
        <div class="filters-header">
            <h3>Subject Filters</h3>
            <a href="{{ route('dashboard.admin.subjects.create') }}" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                New Subject
            </a>
        </div>

        <form method="GET" action="{{ route('dashboard.admin.subjects.index') }}" class="filters-left">
            <label class="field">
                <span>Search subjects</span>
                <input type="text" name="search" placeholder="e.g. Calculus" value="{{ request('search') }}" aria-label="Search subjects">
            </label>
            <label class="field">
                <span>Course</span>
                <select name="course">
                    <option value="">All</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                    @endforeach
                </select>
            </label>
            <div class="filters-actions">
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->hasAny(['search', 'course']))
                    <a href="{{ route('dashboard.admin.subjects.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="dashboard-card table-card">
        <div class="card-header">
            <h3>Subject Catalog</h3>
            <span class="chip">Showing {{ $subjects->count() }} of {{ $totalSubjects }}</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Code</th>
                        <th>Course</th>
                        <th>Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>
                                <div class="item-main">
                                    <div>
                                        <p class="item-title">{{ $subject->name }}</p>
                                        @if($subject->description)
                                            <span class="item-sub">{{ Str::limit($subject->description, 50) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $subject->code }}</td>
                            <td>
                                @php
                                    $subjectCourses = $subject->courses;
                                @endphp
                                @if($subjectCourses->isNotEmpty())
                                    @foreach($subjectCourses as $course)
                                        <span class="badge badge-info" style="margin-right: 4px; margin-bottom: 4px;">{{ $course->code }}</span>
                                    @endforeach
                                    <small style="color: var(--text-dark-secondary); display: block; margin-top: 4px;">
                                        {{ $subjectCourses->pluck('name')->join(', ') }}
                                    </small>
                                @else
                                    <small style="color: var(--text-dark-secondary);">N/A</small>
                                @endif
                            </td>
                            <td>
                                <span class="credits-badge">{{ $subject->credits }}</span>
                            </td>
                            <td class="row-actions">
                                <a href="{{ route('dashboard.admin.subjects.edit', $subject) }}" class="icon-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('dashboard.admin.subjects.destroy', $subject) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn icon-btn-danger" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-content">
                                    <p>No subjects found</p>
                                    @if(request()->hasAny(['search', 'course']))
                                        <a href="{{ route('dashboard.admin.subjects.index') }}" class="btn btn-secondary">Clear filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subjects->hasPages())
            <div class="pagination-wrapper">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.subjects-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

.stat-card {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
    border: 1px solid var(--border-dark);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-label {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-dark);
}

.stat-meta {
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
}

.filters-card {
    display: flex;
    gap: var(--spacing-lg);
    align-items: flex-end;
    justify-content: space-between;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    margin-bottom: var(--spacing-md);
}

.filters-left {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
    flex: 1;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.field input,
.field select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
}

.filters-actions {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.btn {
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-md);
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    transition: all 0.2s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-sm {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: 0.875rem;
}

.btn-secondary {
    background: var(--text-dark-secondary);
    color: white;
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background: var(--text-dark);
}

.btn-ghost {
    background: transparent;
    color: var(--text-dark-secondary);
    border: 1px solid var(--border-dark);
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: 0.875rem;
}

.btn-ghost:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-dark);
}

.table-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.chip {
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-dark);
    border-radius: 999px;
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-dark);
}

.data-table th {
    color: var(--text-dark);
    font-weight: 600;
    background: rgba(255, 255, 255, 0.03);
}

.item-main {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.item-title {
    color: var(--text-dark);
    font-weight: 600;
}

.item-sub {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
}

.badge-info {
    background: rgba(59, 130, 246, 0.12);
    color: #3b82f6;
}

.credits-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(168, 85, 247, 0.12);
    color: #a855f7;
    border-radius: 50%;
    font-weight: 600;
    font-size: 0.9rem;
}

.row-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.icon-btn {
    width: 34px;
    height: 34px;
    border: 1px solid var(--border-dark);
    background: rgba(255, 255, 255, 0.04);
    border-radius: var(--radius-md);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    color: var(--text-dark);
}

.icon-btn:hover {
    background: var(--primary);
    color: white;
}

.icon-btn-danger {
    color: var(--danger);
}

.icon-btn-danger:hover {
    background: rgba(239, 68, 68, 0.15) !important;
    color: #ef4444;
}

.icon-btn svg {
    stroke: currentColor;
}

.empty-state {
    text-align: center;
    padding: var(--spacing-2xl) var(--spacing-lg);
}

.empty-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    color: var(--text-dark-secondary);
}

.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    padding: var(--spacing-md);
    border-top: 1px solid var(--border-dark);
}

@media (max-width: 900px) {
    .filters-card {
        flex-direction: column;
        align-items: stretch;
    }

    .filters-left {
        grid-template-columns: 1fr;
    }

    .data-table {
        font-size: 0.85rem;
    }

    .data-table th,
    .data-table td {
        padding: var(--spacing-sm);
    }
}
</style>
@endpush
@endsection
