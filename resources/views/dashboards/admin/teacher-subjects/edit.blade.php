@extends('layouts.be_master')

@section('title', 'Edit Assignment - Quorum')
@section('page-title', 'Edit Teacher Assignment')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('dashboard.admin.teacher-subjects.index') }}" class="back-link">Back to assignments</a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('dashboard.admin.teacher-subjects.update', $teacherSubject) }}" class="stacked-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="teacher_id">Teacher <span class="required">*</span></label>
                <select id="teacher_id" name="teacher_id" required class="@error('teacher_id') is-invalid @enderror">
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ (int)old('teacher_id', $teacherSubject->teacher_id) === $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->email }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="subject_id">Subject <span class="required">*</span></label>
                <select id="subject_id" name="subject_id" required class="@error('subject_id') is-invalid @enderror">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ (int)old('subject_id', $teacherSubject->subject_id) === $subject->id ? 'selected' : '' }}>
                            {{ $subject->code }} - {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="academic_year">Academic Year <span class="required">*</span></label>
                    <input type="number" id="academic_year" name="academic_year" min="2000" max="2100" value="{{ old('academic_year', $teacherSubject->academic_year) }}" class="@error('academic_year') is-invalid @enderror">
                    @error('academic_year')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="semester">Semester <span class="required">*</span></label>
                    <select id="semester" name="semester" required class="@error('semester') is-invalid @enderror">
                        <option value="1" {{ (int)old('semester', $teacherSubject->semester) === 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ (int)old('semester', $teacherSubject->semester) === 2 ? 'selected' : '' }}>2</option>
                    </select>
                    @error('semester')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="class_capacity">Class Capacity <span class="required">*</span></label>
                    <input type="number" id="class_capacity" name="class_capacity" min="1" max="500" value="{{ old('class_capacity', $teacherSubject->class_capacity) }}" class="@error('class_capacity') is-invalid @enderror">
                    @error('class_capacity')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" required class="@error('status') is-invalid @enderror">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $teacherSubject->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Assignment</button>
                <a href="{{ route('dashboard.admin.teacher-subjects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.form-container { max-width: 760px; }
.form-header { margin-bottom: var(--spacing-lg); }
.back-link { color: var(--text-dark-secondary); text-decoration: none; }
.back-link:hover { color: var(--primary); }

.form-card { background: var(--bg-dark-secondary); border: 1px solid var(--border-dark); border-radius: var(--radius-lg); padding: var(--spacing-2xl); }
.stacked-form { display: flex; flex-direction: column; gap: var(--spacing-xl); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); }
.form-group { display: flex; flex-direction: column; gap: var(--spacing-xs); }
.form-group label { font-weight: 600; color: var(--text-dark); }
.required { color: var(--danger); }

.form-group input,
.form-group select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
}

.error-message { color: var(--danger); font-size: 0.9rem; }
.form-actions { display: flex; gap: var(--spacing-md); justify-content: flex-end; }

@media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endpush
@endsection
