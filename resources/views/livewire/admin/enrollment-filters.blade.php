<div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="enrollments-header">
        <div class="filter-bar">
            <input type="text" wire:model.live="search" placeholder="Search students or courses" class="filter-input">
            <select wire:model.live="course_id" class="filter-select">
                <option value="">All courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">
                        {{ $course->code }} - {{ $course->name }}
                    </option>
                @endforeach
            </select>
            <select wire:model.live="status" class="filter-select">
                <option value="">All statuses</option>
                @foreach($statuses as $statusItem)
                    <option value="{{ $statusItem }}">
                        {{ ucfirst($statusItem) }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
        </div>
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
                                <a href="{{ route('dashboard.admin.enrollments.edit', $enrollment) }}" class="action-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('dashboard.admin.enrollments.destroy', $enrollment) }}" class="inline-form" onsubmit="return confirm('Remove this enrollment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
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
    padding: var(--spacing-md) var(--spacing-lg);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-info {
    background: rgba(59, 130, 246, 0.12);
    color: #3b82f6;
}

.alert-success {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.12);
    color: #ef4444;
}

.alert-error {
    background: rgba(239, 68, 68, 0.12);
    color: #ef4444;
}

.enrollments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-lg);
    flex-wrap: wrap;
}

.filter-bar {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: var(--spacing-md);
    align-items: end;
    flex: 1;
    min-width: 300px;
}

.filter-input,
.filter-select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-family: inherit;
    font-size: 0.95rem;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--primary);
}

.filter-select option {
    background: var(--bg-dark);
    color: var(--text-dark);
    padding: 8px;
}

.filter-select option:checked {
    background: var(--primary);
    color: white;
}

.btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    opacity: 0.9;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.08);
    color: var(--text-dark);
    border-color: var(--border-dark);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.12);
}

.table-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-top: 0.5cm;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: rgba(255, 255, 255, 0.03);
}

.data-table th {
    padding: var(--spacing-md) var(--spacing-lg);
    text-align: left;
    font-weight: 600;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-dark);
    font-size: 0.95rem;
}

.data-table td {
    padding: var(--spacing-md) var(--spacing-lg);
    color: var(--text-dark-secondary);
    border-bottom: 1px solid var(--border-dark);
}

.data-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.02);
}

.cell-stack {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.cell-title {
    color: var(--text-dark);
    font-weight: 600;
}

.cell-sub {
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: capitalize;
    width: fit-content;
}

.status-active {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}

.status-completed {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
}

.status-withdrawn {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

.status-pending {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.action-buttons {
    display: flex;
    gap: var(--spacing-sm);
    flex-wrap: wrap;
}

.action-btn {
    padding: 6px 10px;
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid var(--border-dark);
    background: rgba(255, 255, 255, 0.04);
    color: var(--text-dark);
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    padding: 0;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.action-btn-danger {
    color: var(--danger);
}

.action-btn-danger:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

.inline-form {
    display: inline;
}

.empty-state {
    text-align: center;
    padding: var(--spacing-2xl) var(--spacing-lg);
    color: var(--text-dark-secondary);
}

.pagination-container {
    display: flex;
    justify-content: flex-end;
    padding: var(--spacing-lg);
    border-top: 1px solid var(--border-dark);
}

@media (max-width: 900px) {
    .filter-bar {
        grid-template-columns: 1fr;
    }

    .enrollments-header {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-bar {
        flex: none;
    }
}

@media (max-width: 768px) {
    .data-table {
        font-size: 0.9rem;
    }

    .data-table th,
    .data-table td {
        padding: var(--spacing-sm) var(--spacing-md);
    }

    .cell-stack {
        min-width: 150px;
    }
}
</style>
@endpush
