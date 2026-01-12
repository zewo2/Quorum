@extends('layouts.be_master')

@section('title', 'Course Management - Quorum')
@section('page-title', 'Course Management')

@section('content')
<div class="courses-page">
    <div class="dashboard-grid stats-grid">
        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Courses</p>
                <p class="stat-value">{{ $totalCourses }}</p>
                <span class="stat-meta">{{ $activeCourses }} active</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 12c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Active Enrollments</p>
                <p class="stat-value">{{ $totalEnrollments }}</p>
                <span class="stat-meta">Students enrolled</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect>
                    <path d="M6 10h.01"></path>
                    <path d="M10 10h.01"></path>
                    <path d="M14 10h.01"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Departments</p>
                <p class="stat-value">{{ $departmentCount }}</p>
                <span class="stat-meta">{{ $departments->implode(', ') }}</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <path d="M9 22V12h6v10"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Average Capacity</p>
                <p class="stat-value">{{ round($courses->avg('capacity') ?? 0) }}</p>
                <span class="stat-meta">students per course</span>
            </div>
        </div>
    </div>

    <div class="dashboard-card filters-card">
        <form method="GET" action="{{ route('dashboard.admin.courses') }}" class="filters-left">
            <label class="field">
                <span>Search courses</span>
                <input type="text" name="search" placeholder="e.g. Web Development" value="{{ request('search') }}" aria-label="Search courses">
            </label>
            <label class="field">
                <span>Department</span>
                <select name="department">
                    <option value="">All</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="field">
                <span>Status</span>
                <select name="status">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </label>
            <div class="filters-actions">
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->hasAny(['search', 'department', 'status']))
                    <a href="{{ route('dashboard.admin.courses') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="dashboard-card table-card">
        <div class="card-header">
            <h3>Course Catalog</h3>
            <span class="chip">Showing {{ $courses->count() }} of {{ $totalCourses }}</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Year/Semester</th>
                        <th>Enrolled</th>
                        <th>Subjects</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                <div class="item-main">
                                    <div class="item-dot" style="background: {{ $course->status === 'active' ? '#22c55e' : '#ef4444' }};"></div>
                                    <div>
                                        <p class="item-title">{{ $course->name }}</p>
                                        <span class="item-sub">{{ Str::limit($course->description, 40) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->department }}</td>
                            <td>Year {{ $course->year }} / Sem {{ $course->semester }}</td>
                            <td>{{ $course->enrollments_count }} / {{ $course->capacity }}</td>
                            <td>{{ $course->subjects_count }}</td>
                            <td>
                                <span class="badge badge-{{ $course->status === 'active' ? 'success' : 'archived' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td class="row-actions">
                                <button class="icon-btn" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                                <button class="icon-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-content">
                                    <p>No courses found</p>
                                    @if(request()->hasAny(['search', 'department', 'status']))
                                        <a href="{{ route('dashboard.admin.courses') }}" class="btn btn-secondary">Clear filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="pagination-wrapper">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

    <div class="dashboard-grid info-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Courses by Department</h3>
                <span class="chip">Distribution</span>
            </div>
            <div class="timeline">
                @foreach($departments as $dept)
                    @php
                        $deptCount = \App\Models\Course::where('department', $dept)->count();
                        $colors = ['#22c55e', '#0ea5e9', '#f59e0b', '#a855f7', '#f43f5e'];
                        $color = $colors[$loop->index % count($colors)];
                    @endphp
                    <div class="timeline-row">
                        <div class="timeline-dot" style="border-color: {{ $color }};"></div>
                        <div>
                            <p class="timeline-title">{{ $dept }}</p>
                            <span class="timeline-meta">{{ $deptCount }} {{ Str::plural('course', $deptCount) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Most Enrolled Courses</h3>
                <span class="chip">by students</span>
            </div>
            <div class="instructors-list">
                @php
                    $topCourses = \App\Models\Course::withCount('enrollments')
                        ->orderByDesc('enrollments_count')
                        ->take(5)
                        ->get();
                @endphp
                @foreach($topCourses as $topCourse)
                    <div class="instructor-item">
                        <div class="avatar">{{ strtoupper(substr($topCourse->code, 0, 2)) }}</div>
                        <div>
                            <p class="item-title">{{ $topCourse->name }}</p>
                            <span class="item-sub">{{ $topCourse->code }} • {{ $topCourse->department }}</span>
                        </div>
                        <span class="badge badge-success">{{ $topCourse->enrollments_count }} enrolled</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.courses-page {
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

.item-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
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

.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-archived { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

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
}

.icon-btn:hover { background: var(--primary); color: white; }
.icon-btn.danger:hover { background: var(--danger); }

.icon-btn svg { stroke: currentColor; }

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

.btn-ghost {
    background: transparent;
    color: var(--text-dark-secondary);
    border: 1px solid var(--border-dark);
}

.btn-ghost:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-dark);
}

.info-grid {
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.timeline-row {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.timeline-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid var(--border-dark);
}

.timeline-title {
    color: var(--text-dark);
    font-weight: 600;
}

.timeline-meta {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.instructors-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.instructor-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-md);
}

.avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
}

@media (max-width: 900px) {
    .filters-card {
        flex-direction: column;
        align-items: stretch;
    }

    .filters-actions {
        justify-content: flex-end;
    }
}
</style>
@endpush
@endsection
