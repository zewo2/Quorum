<div>
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
        <div class="filters-header">
            <h3>Course Filters</h3>
            <a href="{{ route('dashboard.admin.courses.create') }}" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                New Course
            </a>
        </div>

        <div class="filters-row">
            <label class="field">
                <span>Search courses</span>
                <input type="text" wire:model.live="search" placeholder="e.g. Web Development">
            </label>
            <label class="field">
                <span>Department</span>
                <select wire:model.live="department">
                    <option value="">All</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">
                <span>Status</span>
                <select wire:model.live="status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </label>
            <div class="filters-actions">
                <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
            </div>
        </div>
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
                        <th>Total Years</th>
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
                            <td>{{ $course->total_years }}</td>
                            <td>{{ $course->enrollments_count }} / {{ $course->capacity }}</td>
                            <td>{{ $course->subjects_count }}</td>
                            <td>
                                <span class="badge badge-{{ $course->status === 'active' ? 'success' : 'archived' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td class="row-actions">
                                <a href="{{ route('dashboard.admin.courses.edit', $course) }}" class="icon-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('dashboard.admin.courses.destroy', $course) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this course?');">
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
                            <td colspan="8" class="empty-state">
                                <div class="empty-content">
                                    <p>No courses found</p>
                                    @if($search || $department || $status)
                                        <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear filters</button>
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
</div>

@push('styles')
<style>
    [wire\:id] {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: var(--spacing-lg);
    }

    .stat-card {
        background: var(--bg-dark-secondary);
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        display: flex;
        gap: var(--spacing-md);
        align-items: center;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
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
        background: var(--bg-dark-secondary);
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
    }

    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: var(--spacing-md);
        flex-wrap: wrap;
        gap: var(--spacing-md);
    }

    .filters-header h3 {
        margin: 0;
        color: var(--text-dark);
    }

    .filters-row {
        display: flex;
        flex-direction: row;
        gap: var(--spacing-md);
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        color: var(--text-dark-secondary);
        font-size: 0.9rem;
        min-width: 160px;
    }

    .field input,
    .field select {
        padding: var(--spacing-sm) var(--spacing-md);
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-md);
        color: var(--text-dark);
        width: 100%;
        font-family: inherit;
        font-size: 0.95rem;
    }

    .field input:focus,
    .field select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .field select option {
        background: var(--bg-dark);
        color: var(--text-dark);
        padding: 8px;
    }

    .field select option:checked {
        background: var(--primary);
        color: white;
    }

    .filters-actions {
        display: flex;
        gap: var(--spacing-md);
        align-items: flex-end;
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
        font-size: 0.95rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-dark);
        border: 1px solid var(--border-dark);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .btn-sm {
        padding: var(--spacing-xs) var(--spacing-sm);
        font-size: 0.875rem;
    }

    .table-card {
        background: var(--bg-dark-secondary);
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-md);
    }

    .card-header h3 {
        margin: 0;
        color: var(--text-dark);
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
        vertical-align: middle;
    }

    .data-table td.row-actions {
        padding: var(--spacing-sm) var(--spacing-md);
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
        flex-shrink: 0;
    }

    .item-title {
        color: var(--text-dark);
        font-weight: 600;
        margin: 0;
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
    .badge-archived { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

    .row-actions {
        display: flex;
        gap: var(--spacing-sm);
        align-items: center;
        justify-content: flex-start;
    }

    .icon-btn {
        width: 34px;
        height: 34px;
        padding: 0;
        border: 1px solid var(--border-dark);
        background: rgba(255, 255, 255, 0.04);
        border-radius: var(--radius-md);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
        transition: all 0.2s;
        text-decoration: none;
        flex-shrink: 0;
    }

    .icon-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .icon-btn-danger {
        color: var(--danger);
    }

    .icon-btn-danger:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    .icon-btn svg {
        stroke: currentColor;
        display: block;
    }

    .row-actions form {
        display: inline;
        margin: 0;
        padding: 0;
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

    .empty-content p {
        margin: 0;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: flex-end;
        padding: var(--spacing-md);
        border-top: 1px solid var(--border-dark);
    }

    @media (max-width: 900px) {
        .filters-row {
            flex-direction: column;
        }

        .field {
            width: 100%;
        }

        .filters-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>
@endpush
