@extends('layouts.be_master')

@section('title', 'New Enrollment - Quorum')
@section('page-title', 'Create Enrollment')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('dashboard.admin.enrollments.index') }}" class="back-link">Back to Enrollments</a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('dashboard.admin.enrollments.store') }}" class="stacked-form">
            @csrf

            <div class="form-group">
                <label for="student_id">Student <span class="required">*</span></label>
                <select id="student_id" name="student_id" required class="@error('student_id') is-invalid @enderror">
                    <option value="">Select a student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ (int)old('student_id') === $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="course_id">Course <span class="required">*</span></label>
                <select id="course_id" name="course_id" required class="@error('course_id') is-invalid @enderror">
                    <option value="">Select a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ (int)old('course_id') === $course->id ? 'selected' : '' }}>
                            {{ $course->code }} - {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" required class="@error('status') is-invalid @enderror">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', 'active') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="final_grade">Final Grade</label>
                    <input type="number" step="0.01" min="0" max="20" id="final_grade" name="final_grade" value="{{ old('final_grade') }}" class="@error('final_grade') is-invalid @enderror" placeholder="0 - 20">
                    @error('final_grade')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="@error('notes') is-invalid @enderror" placeholder="Add optional notes">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Enrollment</button>
                <a href="{{ route('dashboard.admin.enrollments.index') }}" class="btn btn-secondary">Cancel</a>
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

.form-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
}

.stacked-form { display: flex; flex-direction: column; gap: var(--spacing-xl); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg); }
.form-group { display: flex; flex-direction: column; gap: var(--spacing-xs); }
.form-group label { font-weight: 600; color: var(--text-dark); }
.required { color: var(--danger); }

.form-group input,
.form-group select,
.form-group textarea {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
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
