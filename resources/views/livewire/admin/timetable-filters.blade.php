<div>
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem;">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem;">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="dashboard-card filters-card" style="margin-bottom: 0.5cm;">
        <div class="filters-form">
            <div class="filters-row">
                <label class="field">
                    <span>Month</span>
                    <input type="month" wire:model.live="month">
                </label>

                <label class="field">
                    <span>Course</span>
                    <select wire:model.live="courseId">
                        <option value="">Select course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
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

                <div class="filters-actions">
                    <button type="button" wire:click="resetFilters" class="btn btn-secondary">Reset Filters</button>
                    <button
                        type="button"
                        wire:click="clearSelectedCourseSchedule"
                        class="btn btn-danger btn-critical"
                        @disabled(!$courseId)
                        onclick="confirm('Delete all timetable entries for the selected course?') || event.stopImmediatePropagation()"
                    >
                        Delete Schedule
                    </button>
                    <button
                        type="button"
                        wire:click="clearAllSchedules"
                        class="btn btn-danger btn-critical btn-critical-all"
                        onclick="confirm('Delete ALL timetable entries for every course? This cannot be undone.') || event.stopImmediatePropagation()"
                    >
                        Delete All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Weekly Schedule View</h3>
            <span class="chip">{{ $entryCount }} entries</span>
        </div>

        @if(!$showCalendar)
            <div class="empty-calendar-state">
                <p>Select a course to display the calendar.</p>
            </div>
        @else
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
                $weekDates = $weekDates ?? collect();
                $hasData = $entryCount > 0;

                // Group timetables by date and occupied hourly slot
                $scheduleByDateTime = [];
                foreach($groupedByDate as $dateKey => $dateEntries) {
                    $normalizedKey = \Carbon\Carbon::parse($dateKey)->toDateString();
                    $scheduleByDateTime[$normalizedKey] = [];

                    foreach($dateEntries as $entry) {
                        $startTime = \Carbon\Carbon::parse($entry->start_time);
                        $endTime = \Carbon\Carbon::parse($entry->end_time);
                        $slotCursor = $startTime->copy();

                        while ($slotCursor->lt($endTime)) {
                            $slotKey = $slotCursor->format('H:i');

                            if (!isset($scheduleByDateTime[$normalizedKey][$slotKey])) {
                                $scheduleByDateTime[$normalizedKey][$slotKey] = [];
                            }

                            $scheduleByDateTime[$normalizedKey][$slotKey][] = [
                                'entry' => $entry,
                                'is_start' => $slotCursor->equalTo($startTime),
                            ];

                            $slotCursor->addHour();
                        }
                    }
                }
            @endphp

            @if(!$hasData)
                <div class="empty-calendar-state">
                    <p>No schedule entries found for the selected criteria.</p>
                </div>
            @else
                <div class="calendar-grid" style="grid-template-columns: 84px repeat({{ max($weekDates->count(), 1) }}, 1fr);">
                    <div class="time-column">
                        <div class="calendar-header-cell">Time</div>
                        @foreach($timeSlots as $time => $label)
                            <div class="time-cell">{{ $label }}</div>
                        @endforeach
                    </div>

                    @foreach($weekDates as $date)
                        @php
                            $dateString = $date->toDateString();
                            $dayName = $date->format('l'); // Full day name: Monday, Tuesday, etc.
                            $dayNum = $date->format('j'); // Day of month: 1, 2, 3, etc.
                        @endphp
                        <div class="day-column">
                            <div class="calendar-header-cell">
                                <span class="day-name">{{ $dayName }}</span>
                                <span class="day-num">{{ $dayNum }}</span>
                            </div>
                            @foreach($timeSlots as $time => $label)
                                <div class="schedule-cell">
                                    @if(isset($scheduleByDateTime[$dateString][$time]))
                                        @foreach($scheduleByDateTime[$dateString][$time] as $slotData)
                                            @php
                                                $entry = $slotData['entry'];
                                                $isStart = $slotData['is_start'];
                                            @endphp

                                            <div class="schedule-event {{ $isStart ? '' : 'schedule-event-continuation' }}">
                                                @if($isStart)
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
                                                @else
                                                    <div class="continuation-fill">
                                                        <span class="continuation-subject">{{ $entry->teacherSubject?->subject?->name ?? 'N/A' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            @if($totalWeeks > 1)
                <div class="pagination-wrapper">
                    <div class="week-nav">
                        @for ($w = 1; $w <= $totalWeeks; $w++)
                            <button type="button"
                               wire:click="$set('week', {{ $w }})"
                               class="week-btn {{ $currentWeek == $w ? 'active' : '' }}">
                                Week {{ $w }}
                            </button>
                        @endfor
                    </div>
                </div>
            @endif
            @endif
    </div>
</div>

@push('styles')
<style>
.field input,
.field select {
    padding: 0.45rem 0.7rem;
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-size: 0.92rem;
}

.field span {
    font-size: 0.85rem;
}

.filters-card {
    padding: var(--spacing-md);
}

.filters-row {
    gap: var(--spacing-md);
    align-items: end;
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
    align-items: flex-end;
    flex-wrap: wrap;
}

.btn-critical {
    background: rgba(239, 68, 68, 0.95);
    border: 1px solid rgba(239, 68, 68, 1);
    color: #ffffff;
    font-weight: 700;
}

.btn-critical:hover {
    background: rgba(220, 38, 38, 1);
    border-color: rgba(220, 38, 38, 1);
    color: #ffffff;
}

.btn-critical-all {
    background: rgba(127, 29, 29, 0.98);
    border-color: rgba(127, 29, 29, 1);
}

.btn-critical-all:hover {
    background: rgba(99, 18, 18, 1);
    border-color: rgba(99, 18, 18, 1);
}

.btn-critical:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.calendar-wrapper {
    overflow-x: auto;
    padding: var(--spacing-sm);
}

.calendar-grid {
    display: grid;
    grid-template-columns: 100px repeat(6, 1fr);
    gap: 1px;
    background: var(--border-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    overflow: hidden;
    min-width: 760px;
}

.calendar-grid {
    --slot-height: 105px;
    --header-height: 52px;
}

.time-column, .day-column {
    display: flex;
    flex-direction: column;
}

.day-column .calendar-header-cell,
.day-column .schedule-cell {
    border-right: 1px solid var(--border-dark);
}

.day-column:last-child .calendar-header-cell,
.day-column:last-child .schedule-cell {
    border-right: none;
}

.calendar-header-cell {
    background: rgba(255, 255, 255, 0.03);
    padding: 0.6rem 0.45rem;
    font-weight: 600;
    text-align: center;
    color: var(--text-dark);
    border-bottom: 2px solid var(--border-dark);
    min-height: var(--header-height);
    height: var(--header-height);
    box-sizing: border-box;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.day-name {
    display: block;
    font-size: 0.82rem;
}

.day-num {
    display: block;
    font-size: 0.72rem;
    color: var(--text-dark-secondary);
    margin-top: 2px;
}

.time-cell {
    background: var(--bg-dark-secondary);
    padding: 0.3rem;
    font-size: 0.8rem;
    color: var(--text-dark-secondary);
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: var(--slot-height);
    height: var(--slot-height);
    box-sizing: border-box;
    border-bottom: 1px solid var(--border-dark);
    border-right: 1px solid var(--border-dark);
}

.schedule-cell {
    background: var(--bg-dark-secondary);
    padding: 4px;
    min-height: var(--slot-height);
    height: var(--slot-height);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    gap: 4px;
    border-bottom: 1px solid var(--border-dark);
    overflow-y: auto;
    max-height: var(--slot-height);
}

.schedule-event {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.15), rgba(99, 102, 241, 0.1));
    border-left: 3px solid var(--primary);
    border-radius: var(--radius-sm);
    padding: 3px 5px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    transition: all 0.2s;
    flex-shrink: 0;
}

.schedule-event:hover {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(99, 102, 241, 0.15));
    transform: translateY(-1px);
}

.schedule-event-continuation {
    border-top: 1px dashed rgba(79, 70, 229, 0.35);
    opacity: 0.9;
    padding: 0;
    gap: 0;
    flex: 1;
    min-height: 100%;
}

.continuation-fill {
    width: 100%;
    height: 100%;
    min-height: 100%;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    padding: 4px 6px;
    box-sizing: border-box;
}

.continuation-subject {
    font-size: 0.68rem;
    color: var(--text-dark);
    font-weight: 600;
    line-height: 1.2;
    opacity: 0.95;
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: var(--spacing-xs);
}

.event-subject {
    font-size: 0.7rem;
    color: var(--text-dark);
    font-weight: 600;
    line-height: 1.2;
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
    width: 18px;
    height: 18px;
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
    font-size: 0.68rem;
    color: var(--text-dark-secondary);
}

.event-room {
    font-size: 0.68rem;
    color: var(--text-dark-secondary);
}

.event-capacity {
    font-size: 0.65rem;
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

.pagination-wrapper {
    display: flex;
    justify-content: center;
    padding: var(--spacing-sm);
}

.week-nav {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.week-btn {
    padding: 0.35rem 0.65rem;
    border: 1px solid var(--border-dark);
    background: rgba(255, 255, 255, 0.04);
    color: var(--text-dark);
    border-radius: var(--radius-md);
    cursor: pointer;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s;
}

.week-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--border-light);
}

.week-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
</style>
@endpush
