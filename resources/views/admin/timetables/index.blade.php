@extends('layouts.be_master')

@section('title', 'Timetable Management - Quorum')
@section('page-title', 'Timetable Management')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Timetable Management</h1>
            <p>Manage class schedules and time slots</p>
        </div>
        <a href="{{ route('dashboard.admin.timetables.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Schedule
        </a>
    </div>

    <div class="dashboard-card filters-card">
        <form method="GET" action="{{ route('dashboard.admin.timetables.index') }}" class="filters-form">
            <div class="filters-row">
                <label class="field">
                    <span>Day of Week</span>
                    <select name="day">
                        <option value="">All Days</option>
                        <option value="Monday" {{ request('day') === 'Monday' ? 'selected' : '' }}>Monday</option>
                        <option value="Tuesday" {{ request('day') === 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                        <option value="Wednesday" {{ request('day') === 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                        <option value="Thursday" {{ request('day') === 'Thursday' ? 'selected' : '' }}>Thursday</option>
                        <option value="Friday" {{ request('day') === 'Friday' ? 'selected' : '' }}>Friday</option>
                        <option value="Saturday" {{ request('day') === 'Saturday' ? 'selected' : '' }}>Saturday</option>
                    </select>
                </label>

                <label class="field">
                    <span>Teacher</span>
                    <select name="teacher">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>Subject</span>
                    <select name="subject">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="field">
                    <span>Room</span>
                    <input type="text" name="room" placeholder="Search by room" value="{{ request('room') }}">
                </label>

                <div class="filters-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('dashboard.admin.timetables.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
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
                                    {{ $entry->start_time->format('H:i') }} - {{ $entry->end_time->format('H:i') }}
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
                                No timetable entries yet. <a href="{{ route('dashboard.admin.timetables.create') }}">Create one</a>
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

    <div class="dashboard-grid" style="margin-top: 2rem;">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Weekly View</h3>
            </div>
            <div class="schedule-grid">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                @endphp
                @foreach($days as $day)
                    <div class="schedule-day">
                        <h4>{{ $day }}</h4>
                        @php
                            $dayTimetables = $timetables->where('day_of_week', $day)->sortBy('start_time');
                        @endphp
                        <div class="schedule-items">
                            @forelse($dayTimetables as $entry)
                                <div class="schedule-slot">
                                    <div class="slot-time">{{ $entry->start_time->format('H:i') }}</div>
                                    <div class="slot-info">
                                        <p class="slot-subject">{{ $entry->teacherSubject?->subject?->name ?? 'N/A' }}</p>
                                        <p class="slot-room">{{ $entry->room ?? 'TBA' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p style="color: var(--text-dark-secondary); font-size: 0.9rem;">No classes</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .filters-card {
        margin-bottom: var(--spacing-lg);
    }

    .filters-form {
        width: 100%;
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--spacing-md);
        align-items: end;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .field span {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-dark-secondary);
    }

    .field select,
    .field input {
        padding: var(--spacing-sm) var(--spacing-md);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--bg-dark);
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .filters-actions {
        display: flex;
        gap: var(--spacing-sm);
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--spacing-lg);
        margin-top: var(--spacing-md);
    }

    .schedule-day h4 {
        margin-bottom: var(--spacing-md);
        font-weight: 600;
    }

    .schedule-items {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-sm);
    }

    .schedule-slot {
        background: var(--bg-light);
        border-left: 3px solid var(--primary-color);
        padding: var(--spacing-sm) var(--spacing-md);
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
    }

    .slot-time {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.8rem;
    }

    .slot-subject {
        margin: var(--spacing-xs) 0 0;
        font-weight: 500;
    }

    .slot-room {
        margin: var(--spacing-xs) 0 0;
        color: var(--text-dark-secondary);
        font-size: 0.75rem;
    }
</style>
@endsection
