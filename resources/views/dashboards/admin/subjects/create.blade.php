@extends('layouts.be_master')

@section('title', 'Create Subject - Quorum')
@section('page-title', 'Create Subject')

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
        <h2>Create New Subject</h2>

        <form method="POST" action="{{ route('dashboard.admin.subjects.store') }}" class="subject-form">
            @csrf

            <div class="form-section">
                <h3 class="section-title">Subject Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="code">Subject Code <span class="required">*</span></label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
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
                            value="{{ old('name') }}"
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
                    >{{ old('description') }}</textarea>
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
                        <label for="course_id">Course <span class="required">*</span></label>
                        <select
                            id="course_id"
                            name="course_id"
                            required
                            class="@error('course_id') is-invalid @enderror"
                        >
                            <option value="">-- Select a Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="credits">Credits <span class="required">*</span></label>
                        <input
                            type="number"
                            id="credits"
                            name="credits"
                            value="{{ old('credits', 3) }}"
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
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                    Create Subject
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
@endsection
