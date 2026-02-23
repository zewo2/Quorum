@extends('layouts.be_master')

@section('title', 'Create Exam - Quorum')
@section('page-title', 'Create Exam')

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
            <h1>Add Exam</h1>
            <p>Create a new exam schedule</p>
        </div>
        <a href="{{ route('dashboard.admin.exams.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="dashboard-card form-card">
        <form action="{{ route('dashboard.admin.exams.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field-group">
                    <label class="field">
                        <span>Subject <span class="required">*</span></span>
                        <select name="subject_id" required>
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                @php
                                    $subjectCourses = $subject->courses?->pluck('name')->join(', ');
                                    if (!$subjectCourses) {
                                        $subjectCourses = 'N/A';
                                    }
                                @endphp
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subjectCourses }})
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Date <span class="required">*</span></span>
                        <input type="date" name="exam_date" value="{{ old('exam_date') }}" required>
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Start Time <span class="required">*</span></span>
                        <input type="time" name="start_time" value="{{ old('start_time') }}" required>
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>End Time <span class="required">*</span></span>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" required>
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Room</span>
                        <select name="room">
                            <option value="">Select a room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->code }}" {{ old('room') === $room->code ? 'selected' : '' }}>
                                    {{ $room->code }}@if($room->building) • {{ $room->building }}@endif
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard.admin.exams.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Exam</button>
            </div>
        </form>
    </div>
</div>
@endsection
