<div>
    <div class="dashboard-card filters-card">
        <div class="filters-row">
            <label class="field">
                <span>Search Code</span>
                <input type="text" wire:model.live="q" placeholder="e.g., A-101">
            </label>

            <label class="field">
                <span>Building</span>
                <select wire:model.live="building">
                    <option value="">All Buildings</option>
                    @foreach($buildings as $buildingItem)
                        <option value="{{ $buildingItem }}">{{ $buildingItem }}</option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span>Capacity Min</span>
                <input type="number" wire:model.live="capacity_min" min="1" max="1000" placeholder="e.g., 20">
            </label>

            <label class="field">
                <span>Capacity Max</span>
                <input type="number" wire:model.live="capacity_max" min="1" max="1000" placeholder="e.g., 100">
            </label>

            <div class="filters-actions">
                <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Rooms</h3>
            <span class="chip">{{ $rooms->total() }} rooms</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Building</th>
                        <th>Capacity</th>
                        <th>Features</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td><strong>{{ $room->code }}</strong></td>
                            <td>{{ $room->building ?? '—' }}</td>
                            <td><span class="badge">{{ $room->capacity }}</span></td>
                            <td>
                                @if($room->features)
                                    <small>{{ substr($room->features, 0, 30) }}{{ strlen($room->features) > 30 ? '...' : '' }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('dashboard.admin.rooms.edit', $room->id) }}" class="action-btn edit-btn" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('dashboard.admin.rooms.destroy', $room->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-message">No rooms found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rooms->hasPages())
            <div class="pagination-wrapper">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.filters-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
    align-items: end;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    color: var(--text-dark-secondary);
}

.field input,
.field select {
    padding: var(--spacing-sm) var(--spacing-md);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    background: var(--bg-dark);
    color: var(--text-dark);
    font-family: inherit;
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
    gap: var(--spacing-sm);
    flex-wrap: wrap;
}

.dashboard-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    flex-wrap: wrap;
    gap: var(--spacing-md);
}

.card-header h3 {
    font-size: 1.25rem;
    color: var(--text-dark);
    font-weight: 600;
}

.chip {
    padding: 6px 14px;
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

.data-table thead {
    background: rgba(255, 255, 255, 0.03);
}

.data-table th {
    padding: var(--spacing-md) var(--spacing-lg);
    text-align: left;
    font-weight: 600;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-dark);
}

.data-table td {
    padding: var(--spacing-md) var(--spacing-lg);
    color: var(--text-dark-secondary);
    border-bottom: 1px solid var(--border-dark);
}

.data-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.02);
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
    background: rgba(79, 70, 229, 0.15);
    color: #4f46e5;
}

.row-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 1px solid var(--border-dark);
    background: rgba(255, 255, 255, 0.04);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all 0.2s;
    color: var(--text-dark);
    text-decoration: none;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
}

.edit-btn:hover {
    background: var(--primary);
}

.delete-btn:hover {
    background: var(--danger);
}

.empty-message {
    text-align: center;
    padding: var(--spacing-2xl) var(--spacing-lg);
    color: var(--text-dark-secondary);
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    padding: var(--spacing-lg);
    border-top: 1px solid var(--border-dark);
}

@media (max-width: 960px) {
    .filters-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
