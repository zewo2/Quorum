@extends('layouts.be_master')

@section('title', 'Edit User - Quorum')
@section('page-title', 'Edit User')

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
        <div class="user-info-header">
            <div class="user-avatar-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h2>{{ $user->name }}</h2>
                <p class="user-email">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.admin.users.update', $user) }}" class="user-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
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
                    value="{{ old('email', $user->email) }}"
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
                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-divider">
                <span>Change Password (Optional)</span>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="@error('password') is-invalid @enderror"
                >
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <small class="form-hint">Leave blank to keep current password</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Update User
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

.user-info-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding-bottom: var(--spacing-xl);
    border-bottom: 1px solid var(--border-dark);
    margin-bottom: var(--spacing-xl);
}

.user-avatar-large {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
    font-size: 1.5rem;
}

.user-info-header h2 {
    color: var(--text-dark);
    font-size: 1.5rem;
    margin-bottom: var(--spacing-xs);
}

.user-email {
    color: var(--text-dark-secondary);
    font-size: 0.9375rem;
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

.form-divider {
    display: flex;
    align-items: center;
    text-align: center;
    color: var(--text-dark-secondary);
    margin: var(--spacing-md) 0;
}

.form-divider::before,
.form-divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid var(--border-dark);
}

.form-divider span {
    padding: 0 var(--spacing-md);
    font-size: 0.875rem;
    font-weight: 500;
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-md);
}
</style>
@endpush
@endsection
