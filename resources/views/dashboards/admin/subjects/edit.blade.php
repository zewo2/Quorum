@extends('layouts.be_master')

@section('title', 'Edit Subject - Quorum')
@section('page-title', 'Edit Subject')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('dashboard.admin.subjects.index') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Subjects
        </a>
    </div>

    <div class="form-card">
        <h2>Edit Subject: {{ $subject->name }}</h2>

        <form method="POST" action="{{ route('dashboard.admin.subjects.update', $subject) }}" class="subject-form">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3 class="section-title">Subject Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="code">Subject Code <span class="required">*</span></label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code', $subject->code) }}"
                            required
                            autofocus
                            class="@error('code') is-invalid @enderror"
                            placeholder="MATH101"
                            maxlength="50"
                        >
                        @error('code')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Unique identifier for the subject</small>
                    </div>

                    <div class="form-group">
                        <label for="name">Subject Name <span class="required">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $subject->name) }}"
                            required
                            class="@error('name') is-invalid @enderror"
                            placeholder="Calculus I"
                            maxlength="255"
                        >
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="@error('description') is-invalid @enderror"
                        placeholder="Subject description and overview..."
                        maxlength="1000"
                    >{{ old('description', $subject->description) }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Max 1000 characters</small>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Subject Details</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="course_ids">Courses <span class="required">*</span></label>
                        @php
                            $selectedCourseIds = old('course_ids', $subject->courses->pluck('id')->all());
                        @endphp
                        <details class="courses-dropdown @error('course_ids') is-invalid @enderror" id="courseDropdownEdit">
                            <summary>
                                <span id="courseDropdownEditLabel">
                                    {{ count($selectedCourseIds) > 0 ? count($selectedCourseIds) . ' course(s) selected' : 'Select one or more courses' }}
                                </span>
                            </summary>
                            <div class="courses-options">
                                @foreach($courses as $course)
                                    <label class="course-option">
                                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" {{ in_array($course->id, $selectedCourseIds) ? 'checked' : '' }}>
                                        <span>{{ $course->name }} ({{ $course->code }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>
                        @error('course_ids')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('course_ids.*')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Open the list and tick one or more courses.</small>
                    </div>

                    <div class="form-group">
                        <label for="credits">Credits <span class="required">*</span></label>
                        <input
                            type="number"
                            id="credits"
                            name="credits"
                            value="{{ old('credits', $subject->credits) }}"
                            required
                            min="1"
                            max="20"
                            class="@error('credits') is-invalid @enderror"
                        >
                        @error('credits')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Credit hours (1-20)</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Update Subject
                </button>
                <a href="{{ route('dashboard.admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.form-container {
    max-width: 700px;
    margin: 0 auto;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-xl);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    color: var(--text-dark-secondary);
    text-decoration: none;
    transition: color 0.2s;
}

.back-link:hover {
    color: var(--primary-light);
}

.form-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
}

.form-card h2 {
    margin-bottom: var(--spacing-lg);
    color: var(--text-dark);
}

.form-section {
    margin-bottom: var(--spacing-xl);
}

.section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-dark-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.form-group label {
    font-weight: 500;
    color: var(--text-dark);
    font-size: 0.875rem;
}

.required {
    color: var(--danger);
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: var(--spacing-sm) var(--spacing-md);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    background: var(--bg-dark);
    color: var(--text-dark);
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
}

.form-group input.is-invalid,
.form-group textarea.is-invalid,
.form-group select.is-invalid {
    border-color: var(--danger);
}

.courses-dropdown {
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    background: var(--bg-dark);
}

.courses-dropdown summary {
    list-style: none;
    cursor: pointer;
    padding: var(--spacing-sm) var(--spacing-md);
    color: var(--text-dark);
}

.courses-dropdown summary::-webkit-details-marker {
    display: none;
}

.courses-dropdown[open] summary {
    border-bottom: 1px solid var(--border-dark);
}

.courses-options {
    max-height: 180px;
    overflow: auto;
    padding: var(--spacing-sm) var(--spacing-md);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.course-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 0.875rem;
}

.courses-dropdown.is-invalid {
    border-color: var(--danger);
}

.error-message {
    color: var(--danger);
    font-size: 0.8125rem;
    margin-top: var(--spacing-xs);
}

.form-hint {
    color: var(--text-dark-secondary);
    font-size: 0.8125rem;
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border-dark);
}

.btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius-md);
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    transition: all 0.2s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-secondary {
    background: var(--bg-dark);
    color: var(--text-dark);
    border: 1px solid var(--border-dark);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.08);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('courseDropdownEdit');
    const label = document.getElementById('courseDropdownEditLabel');
    if (!dropdown || !label) return;

    const updateLabel = () => {
        const count = dropdown.querySelectorAll('input[type="checkbox"]:checked').length;
        label.textContent = count > 0 ? `${count} course(s) selected` : 'Select one or more courses';
    };

    dropdown.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
        checkbox.addEventListener('change', updateLabel);
    });

    updateLabel();
});
</script>
@endpush
@endsection
