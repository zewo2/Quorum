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
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="user-avatar-large-img">
            @else
                <div class="user-avatar-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <div>
                <h2>{{ $user->name }}</h2>
                <p class="user-email">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.admin.users.update', $user) }}" class="user-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3 class="section-title">Basic Information</h3>

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
                        placeholder="João Silva"
                    >
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Apenas letras e espaços são permitidos</small>
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
                        placeholder="joao.silva@example.com"
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
            </div>

            <div class="form-section">
                <h3 class="section-title">Contact & Personal Details</h3>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="@error('phone') is-invalid @enderror"
                        placeholder="912345678"
                        maxlength="15"
                    >
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Apenas números (9-15 dígitos)</small>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="@error('address') is-invalid @enderror"
                        placeholder="Rua Example, nº 123, 1000-000 Lisboa"
                    >{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                            class="@error('date_of_birth') is-invalid @enderror"
                            max="{{ date('Y-m-d') }}"
                        >
                        @error('date_of_birth')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nif">NIF</label>
                        <input
                            type="text"
                            id="nif"
                            name="nif"
                            value="{{ old('nif', $user->nif) }}"
                            class="@error('nif') is-invalid @enderror"
                            placeholder="123456789"
                            maxlength="9"
                        >
                        @error('nif')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">9 dígitos</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    @if($user->profile_picture)
                        <div class="current-picture">
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}">
                            <span>Current picture</span>
                        </div>
                    @endif
                    <input
                        type="file"
                        id="profile_picture"
                        name="profile_picture"
                        accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="@error('profile_picture') is-invalid @enderror"
                    >
                    @error('profile_picture')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">JPG, PNG, GIF (max 2MB) - Upload new to replace current</small>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Change Password (Optional)</h3>

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
                    <small class="form-hint">Deixe em branco para manter a password atual. Mínimo 8 caracteres, com maiúsculas, minúsculas, números e símbolos</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                    >
                </div>
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
    max-width: 800px;
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
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.user-avatar-large-img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.user-info-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-dark);
}

.user-email {
    margin: 0;
    color: var(--text-dark-secondary);
    font-size: 0.9375rem;
}

.user-form {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2xl);
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
    padding-bottom: var(--spacing-xl);
    border-bottom: 1px solid var(--border-dark);
}

.form-section:last-of-type {
    border-bottom: none;
    padding-bottom: 0;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 var(--spacing-sm) 0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
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
.form-group select,
.form-group textarea {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-family: inherit;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-group input.is-invalid,
.form-group select.is-invalid,
.form-group textarea.is-invalid {
    border-color: var(--danger);
}

.current-picture {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-sm);
}

.current-picture img {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    object-fit: cover;
}

.current-picture span {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
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
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border-dark);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .form-card {
        padding: var(--spacing-lg);
    }
}
</style>
@endpush
@endsection
