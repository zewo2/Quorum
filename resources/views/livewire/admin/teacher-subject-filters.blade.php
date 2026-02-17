<div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="assignments-header">
        <div class="filter-bar">
            <input type="text" wire:model.live="search" placeholder="Search teachers or subjects" class="filter-input">
            <select wire:model.live="teacher_id" class="filter-select">
                <option value="">All teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            <select wire:model.live="subject_id" class="filter-select">
                <option value="">All subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">
                        {{ $subject->code }} - {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            <input type="number" wire:model.live="academic_year" class="filter-input" placeholder="Year (e.g. 2024)">
            <select wire:model.live="status" class="filter-select">
                <option value="">All statuses</option>
                @foreach($statuses as $statusItem)
                    <option value="{{ $statusItem }}">{{ ucfirst($statusItem) }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
        </div>

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
                                <a href="{{ route('dashboard.admin.teacher-subjects.edit', $assignment) }}" class="action-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('dashboard.admin.teacher-subjects.destroy', $assignment) }}" class="inline-form" onsubmit="return confirm('Remove this assignment?');">
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
.assignments-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.alert {
    padding: var(--spacing-md) var(--spacing-lg);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-success {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.12);
    color: #ef4444;
}

.assignments-header {
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
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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
    white-space: nowrap;
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

.status-inactive {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
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

@media (max-width: 1200px) {
    .filter-bar {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    }
}

@media (max-width: 900px) {
    .assignments-header {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-bar {
        grid-template-columns: 1fr;
        flex: none;
    }

    .btn-primary {
        width: 100%;
        text-align: center;
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

    .filter-bar {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
