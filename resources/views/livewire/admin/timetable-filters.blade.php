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
                    <button type="button" wire:click="resetFilters" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Weekly Schedule View</h3>
            <span class="chip">{{ $timetables->total() }} entries</span>
        </div>

        <div class="calendar-wrapper">
            @php
                $timeSlots = [
                    '08:00' => '08:00 - 09:00',
                    '09:00' => '09:00 - 10:00',
                    '10:00' => '10:00 - 11:00',
                    '11:00' => '11:00 - 12:00',
                    '12:00' => '12:00 - 13:00',
                    '13:00' => '13:00 - 14:00',
                    '14:00' => '14:00 - 15:00',
                    '15:00' => '15:00 - 16:00',
                    '16:00' => '16:00 - 17:00',
                    '17:00' => '17:00 - 18:00',
                    '18:00' => '18:00 - 19:00',
                    '19:00' => '19:00 - 20:00',
                ];
                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                // Group timetables by day and time
                $scheduleGrid = [];
                foreach($timetables as $entry) {
                    $day = $entry->day_of_week;
                    $startTime = substr($entry->start_time, 0, 5);

                    if (!isset($scheduleGrid[$day])) {
                        $scheduleGrid[$day] = [];
                    }
                    if (!isset($scheduleGrid[$day][$startTime])) {
                        $scheduleGrid[$day][$startTime] = [];
                    }
                    $scheduleGrid[$day][$startTime][] = $entry;
                }
            @endphp

            <div class="calendar-grid">
                <div class="time-column">
                    <div class="calendar-header-cell">Time</div>
                    @foreach($timeSlots as $time => $label)
                        <div class="time-cell">{{ $label }}</div>
                    @endforeach
                </div>

                @foreach($daysOfWeek as $day)
                    <div class="day-column">
                        <div class="calendar-header-cell">{{ $day }}</div>
                        @foreach($timeSlots as $time => $label)
                            <div class="schedule-cell">
                                @if(isset($scheduleGrid[$day][$time]))
                                    @foreach($scheduleGrid[$day][$time] as $entry)
                                        <div class="schedule-event">
                                            <div class="event-header">
                                                <strong class="event-subject">{{ $entry->teacherSubject?->subject?->name ?? 'N/A' }}</strong>
                                                <div class="event-actions">
                                                    <a href="{{ route('dashboard.admin.timetables.edit', $entry) }}" class="event-action-btn" title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('dashboard.admin.timetables.destroy', $entry) }}" method="POST" style="display: inline; margin: 0;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="event-action-btn event-action-btn-danger" title="Delete" onclick="return confirm('Delete this entry?')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="event-details">
                                                <div class="event-info">
                                                    <span class="event-teacher">{{ $entry->teacherSubject?->teacher?->name ?? 'N/A' }}</span>
                                                    <span class="event-room">{{ $entry->room ?? 'Room TBD' }} {{ $entry->building ? '(' . $entry->building . ')' : '' }}</span>
                                                </div>
                                                @if($entry->capacity)
                                                    <span class="event-capacity">Cap: {{ $entry->capacity }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            @if($timetables->isEmpty())
                <div class="empty-calendar-state">
                    <p>No schedule entries found. Try adjusting your filters or add new entries.</p>
                </div>
            @endif
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

.calendar-wrapper {
    overflow-x: auto;
    padding: var(--spacing-md);
}

.calendar-grid {
    display: grid;
    grid-template-columns: 100px repeat(6, 1fr);
    gap: 1px;
    background: var(--border-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    overflow: hidden;
    min-width: 900px;
}

.time-column, .day-column {
    display: flex;
    flex-direction: column;
}

.calendar-header-cell {
    background: rgba(255, 255, 255, 0.03);
    padding: var(--spacing-md);
    font-weight: 600;
    text-align: center;
    color: var(--text-dark);
    border-bottom: 2px solid var(--border-dark);
}

.time-cell {
    background: var(--bg-dark-secondary);
    padding: var(--spacing-sm);
    font-size: 0.85rem;
    color: var(--text-dark-secondary);
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60px;
    border-bottom: 1px solid var(--border-dark);
}

.schedule-cell {
    background: var(--bg-dark-secondary);
    padding: var(--spacing-xs);
    min-height: 60px;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
    border-bottom: 1px solid var(--border-dark);
}

.schedule-event {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.15), rgba(99, 102, 241, 0.1));
    border-left: 3px solid var(--primary);
    border-radius: var(--radius-sm);
    padding: var(--spacing-xs);
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: all 0.2s;
}

.schedule-event:hover {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(99, 102, 241, 0.15));
    transform: translateY(-1px);
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: var(--spacing-xs);
}

.event-subject {
    font-size: 0.85rem;
    color: var(--text-dark);
    font-weight: 600;
    line-height: 1.3;
    flex: 1;
}

.event-actions {
    display: flex;
    gap: 4px;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.schedule-event:hover .event-actions {
    opacity: 1;
}

.event-action-btn {
    width: 20px;
    height: 20px;
    padding: 0;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text-dark);
    transition: all 0.2s;
    text-decoration: none;
}

.event-action-btn:hover {
    background: var(--primary);
    color: white;
}

.event-action-btn-danger:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.event-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.event-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.event-teacher {
    font-size: 0.75rem;
    color: var(--text-dark-secondary);
}

.event-room {
    font-size: 0.75rem;
    color: var(--text-dark-secondary);
}

.event-capacity {
    font-size: 0.7rem;
    color: var(--text-dark-secondary);
    background: rgba(255, 255, 255, 0.05);
    padding: 2px 6px;
    border-radius: 999px;
    align-self: flex-start;
}

.empty-calendar-state {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--text-dark-secondary);
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
