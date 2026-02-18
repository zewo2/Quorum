@extends('layouts.be_master')

@section('title', 'Edit Timetable - Quorum')
@section('page-title', 'Edit Timetable Entry')

@section('content')
<div class="admin-page">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Edit Timetable Entry</h1>
            <p>Update schedule information</p>
        </div>
        <a href="{{ route('dashboard.admin.timetables.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="dashboard-card form-card">
        <form action="{{ route('dashboard.admin.timetables.update', $timetable) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field-group">
                    <label class="field">
                        <span>Class <span class="required">*</span></span>
                        <select name="teacher_subject_id" required>
                            <option value="">Select a class</option>
                            @foreach($teacherSubjects as $teacherName => $subjects)
                                <optgroup label="{{ $teacherName }}">
                                    @foreach($subjects as $ts)
                                        <option value="{{ $ts->id }}" {{ $timetable->teacher_subject_id === $ts->id ? 'selected' : '' }}>
                                            {{ $ts->subject->name }} ({{ $ts->subject->code ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('teacher_subject_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Day of Week <span class="required">*</span></span>
                        <select name="day_of_week" required>
                            <option value="">Select a day</option>
                            @foreach($daysOfWeek as $day)
                                <option value="{{ $day }}" {{ $timetable->day_of_week === $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Start Time <span class="required">*</span></span>
                        <input type="time" name="start_time" value="{{ substr($timetable->start_time, 0, 5) }}" required>
                        @error('start_time')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>End Time <span class="required">*</span></span>
                        <input type="time" name="end_time" value="{{ substr($timetable->end_time, 0, 5) }}" required>
                        @error('end_time')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Room</span>
                        <select name="room">
                            <option value="">Select a room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->code }}" {{ $timetable->room === $room->code ? 'selected' : '' }}>
                                    {{ $room->code }}@if($room->building) • {{ $room->building }}@endif
                                </option>
                            @endforeach
                        </select>
                        @error('room')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Building</span>
                        <input type="text" name="building" placeholder="e.g., Block A" value="{{ $timetable->building }}" maxlength="100">
                        @error('building')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Capacity</span>
                        <input type="number" name="capacity" placeholder="e.g., 30" value="{{ $timetable->capacity }}" min="1" max="500">
                        @error('capacity')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard.admin.timetables.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Entry</button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }

    .field-group {
        display: flex;
    }

    .required {
        color: var(--danger-color);
    }

    .form-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: flex-end;
        padding-top: var(--spacing-lg);
        border-top: 1px solid var(--border-color);
    }
</style>
@endsection
