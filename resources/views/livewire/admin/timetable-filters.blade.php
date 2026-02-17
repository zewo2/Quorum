<div>
    <div class="dashboard-card filters-card" style="margin-bottom: 0.5cm;">
        <div class="filters-form">
            <div class="filters-row">
                <label class="field">
                    <span>Day of Week</span>
                    <select wire:model.live="day">
                        <option value="">All Days</option>
                        @foreach($days as $dayOption)
                            <option value="{{ $dayOption }}">{{ $dayOption }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>Teacher</span>
                    <select wire:model.live="teacher">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>Subject</span>
                    <select wire:model.live="subject">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>Room</span>
                    <input type="text" wire:model.live="room" placeholder="Search by room">
                </label>

                <div class="filters-actions">
                    <button type="button" wire:click="$reset" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Current Timetables</h3>
            <span class="chip">{{ $timetables->total() }} entries</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Building</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timetables as $entry)
                        <tr>
                            <td>
                                <strong>{{ $entry->teacherSubject?->subject?->name ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $entry->teacherSubject?->teacher?->name ?? 'N/A' }}</td>
                            <td>{{ $entry->day_of_week }}</td>
                            <td>
                                <span class="badge">
                                    {{ substr($entry->start_time, 0, 5) }} - {{ substr($entry->end_time, 0, 5) }}
                                </span>
                            </td>
                            <td>{{ $entry->room ?? '-' }}</td>
                            <td>{{ $entry->building ?? '-' }}</td>
                            <td>{{ $entry->capacity ?? '-' }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('dashboard.admin.timetables.edit', $entry) }}" class="action-btn" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('dashboard.admin.timetables.destroy', $entry) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-danger" title="Delete" onclick="return confirm('Delete this entry?')">
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
                            <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-dark-secondary);">
                                No timetable entries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($timetables->hasPages())
            <div class="pagination-wrapper">
                {{ $timetables->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.field select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
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

.row-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.inline-form {
    display: inline;
}

.action-btn {
    padding: 0;
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
</style>
@endpush
