@extends('layouts.be_master')

@section('title', 'Create User - Quorum')
@section('page-title', 'Create New User')

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('dashboard.admin.users.index') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Users
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('dashboard.admin.users.store') }}" class="user-form">
            @csrf

            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="@error('name') is-invalid @enderror"
                >
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="@error('email') is-invalid @enderror"
                >
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Role <span class="required">*</span></label>
                <select
                    id="role"
                    name="role"
                    required
                    class="@error('role') is-invalid @enderror"
                >
                    <option value="">Select a role</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="@error('password') is-invalid @enderror"
                >
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <small class="form-hint">Minimum 8 characters</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Create User
                </button>
                <a href="{{ route('dashboard.admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.form-container {
    max-width: 600px;
}

.form-header {
    margin-bottom: var(--spacing-lg);
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
    padding: var(--spacing-2xl);
}

.user-form {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.form-group label {
    font-weight: 500;
    color: var(--text-dark);
    font-size: 0.9375rem;
}

.required {
    color: var(--danger);
}

.form-group input,
.form-group select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-family: inherit;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-group input.is-invalid,
.form-group select.is-invalid {
    border-color: var(--danger);
}

.error-message {
    color: var(--danger);
    font-size: 0.875rem;
}

.form-hint {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
}
</style>
@endpush
@endsection
