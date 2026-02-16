@extends('layouts.be_master')

@section('title', 'My Schedule - Quorum')
@section('page-title', 'My Schedule')

@section('content')
<div class="schedule-page">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Weekly Schedule</h3>
                <span class="chip">{{ $timetables->count() }} classes</span>
            </div>

            @if($timetables->isEmpty())
                <div style="padding: 2rem; text-align: center; color: var(--text-dark-secondary);">
                    <p>No scheduled classes yet.</p>
                </div>
            @else
                <div class="schedule-grid">
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    @endphp
                    @foreach($days as $day)
                        <div class="schedule-day-column">
                            <h4 class="day-header">{{ $day }}</h4>
                            <div class="schedule-slots">
                                @forelse($groupedByDay->get($day, []) as $timetable)
                                    <div class="schedule-slot">
                                        <div class="slot-time">
                                            {{ $timetable->start_time->format('H:i') }}
                                            <span class="slot-duration">- {{ $timetable->end_time->format('H:i') }}</span>
                                        </div>
                                        <div class="slot-details">
                                            <p class="slot-subject">{{ $timetable->teacherSubject?->subject?->name }}</p>
                                            @if($timetable->room)
                                                <p class="slot-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                    {{ $timetable->room }}
                                                    @if($timetable->building)
                                                        • {{ $timetable->building }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="no-classes">-</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($timetables->isNotEmpty())
        <div class="dashboard-card" style="margin-top: var(--spacing-lg);">
            <div class="card-header">
                <h3>All Classes</h3>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Building</th>
                            <th>Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetables as $entry)
                            <tr>
                                <td><strong>{{ $entry->teacherSubject?->subject?->name }}</strong></td>
                                <td>{{ $entry->day_of_week }}</td>
                                <td>
                                    <span class="badge">
                                        {{ $entry->start_time->format('H:i') }} - {{ $entry->end_time->format('H:i') }}
                                    </span>
                                </td>
                                <td>{{ $entry->room ?? '-' }}</td>
                                <td>{{ $entry->building ?? '-' }}</td>
                                <td>{{ $entry->capacity ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: var(--spacing-lg);
        margin-top: var(--spacing-lg);
    }

    .schedule-day-column {
        display: flex;
        flex-direction: column;
    }

    .day-header {
        font-weight: 600;
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid var(--primary-color);
        margin-bottom: var(--spacing-md);
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .schedule-slots {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }

    .schedule-slot {
        background: linear-gradient(135deg, var(--bg-light), var(--bg-lighter));
        border-left: 3px solid var(--primary-color);
        padding: var(--spacing-md);
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
    }

    .schedule-slot:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateX(2px);
    }

    .slot-time {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.85rem;
        margin-bottom: var(--spacing-xs);
    }

    .slot-duration {
        font-weight: normal;
        color: var(--text-dark-secondary);
        font-size: 0.75rem;
    }

    .slot-details {
        margin-top: var(--spacing-xs);
    }

    .slot-subject {
        margin: 0;
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--text-dark);
    }

    .slot-info {
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
        margin: var(--spacing-xs) 0 0;
        color: var(--text-dark-secondary);
        font-size: 0.75rem;
    }

    .slot-info svg {
        flex-shrink: 0;
    }

    .no-classes {
        color: var(--text-dark-secondary);
        font-size: 0.85rem;
        text-align: center;
        padding: var(--spacing-md);
        margin: 0;
    }
</style>
@endsection
