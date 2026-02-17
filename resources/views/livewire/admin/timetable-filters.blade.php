<div>
    <div class="dashboard-card filters-card">
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
                                    <a href="{{ route('dashboard.admin.timetables.edit', $entry) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('dashboard.admin.timetables.destroy', $entry) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this entry?')">Delete</button>
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
