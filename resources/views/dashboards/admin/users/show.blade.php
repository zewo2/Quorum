@extends('layouts.be_master')

@section('title', 'View User - Quorum')
@section('page-title', 'User Details')

@section('content')
<div class="user-view-container">
    <div class="form-header">
        <a href="{{ route('dashboard.admin.users.index') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Users
        </a>
        <div class="header-actions">
            <a href="{{ route('dashboard.admin.users.edit', $user) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit User
            </a>
        </div>
    </div>

    <div class="user-details-grid">
        <!-- Profile Card -->
        <div class="detail-card profile-card">
            <div class="profile-header">
                <div class="user-avatar-xl">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="profile-info">
                    <h2>{{ $user->name }}</h2>
                    <span class="role-badge role-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <div>
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="detail-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <div>
                        <span class="detail-label">Member Since</span>
                        <span class="detail-value">{{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                </div>

                <div class="detail-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <div>
                        <span class="detail-label">Last Updated</span>
                        <span class="detail-value">{{ $user->updated_at->format('F d, Y g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Card -->
        <div class="detail-card">
            <h3>Account Activity</h3>
            <div class="activity-stats">
                <div class="stat-box">
                    <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 20V10"></path>
                            <path d="M12 20V4"></path>
                            <path d="M6 20v-6"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="stat-label">Total Logins</span>
                        <span class="stat-value">--</span>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div>
                        <span class="stat-label">Last Login</span>
                        <span class="stat-value">--</span>
                    </div>
                </div>
            </div>
        </div>

        @if($user->role === 'student')
            <!-- Student Info -->
            <div class="detail-card">
                <h3>Academic Information</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Enrolled Courses</span>
                        <span class="info-value">6</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Average Grade</span>
                        <span class="info-value">15.8</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Attendance Rate</span>
                        <span class="info-value">92%</span>
                    </div>
                </div>
            </div>
        @elseif($user->role === 'teacher')
            <!-- Teacher Info -->
            <div class="detail-card">
                <h3>Teaching Information</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Classes Teaching</span>
                        <span class="info-value">5</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Students</span>
                        <span class="info-value">142</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department</span>
                        <span class="info-value">Computer Science</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Danger Zone -->
        <div class="detail-card danger-card">
            <h3>Danger Zone</h3>
            <p class="danger-text">Once you delete a user, there is no going back. Please be certain.</p>
            <form method="POST" action="{{ route('dashboard.admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Delete User
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.user-view-container {
    max-width: 1000px;
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

.header-actions {
    display: flex;
    gap: var(--spacing-md);
}

.user-details-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-lg);
}

.detail-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-xl);
}

.detail-card h3 {
    color: var(--text-dark);
    font-size: 1.25rem;
    margin-bottom: var(--spacing-lg);
}

.profile-card {
    grid-column: 1 / -1;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    padding-bottom: var(--spacing-xl);
    border-bottom: 1px solid var(--border-dark);
    margin-bottom: var(--spacing-xl);
}

.user-avatar-xl {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
    font-size: 2rem;
}

.profile-info h2 {
    color: var(--text-dark);
    font-size: 1.75rem;
    margin-bottom: var(--spacing-sm);
}

.role-badge {
    padding: var(--spacing-xs) var(--spacing-md);
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: inline-block;
}

.role-student {
    background: rgba(14, 165, 233, 0.1);
    color: #0ea5e9;
}

.role-teacher {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.role-admin {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.profile-details {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.detail-item {
    display: flex;
    gap: var(--spacing-md);
    align-items: flex-start;
}

.detail-item svg {
    color: var(--primary-light);
    flex-shrink: 0;
    margin-top: 2px;
}

.detail-item > div {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.detail-label {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
}

.detail-value {
    color: var(--text-dark);
    font-weight: 500;
}

.activity-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
}

.stat-box {
    display: flex;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon svg {
    color: var(--primary-light);
}

.stat-box > div {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.stat-label {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
}

.stat-value {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1.25rem;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-md);
}

.info-label {
    color: var(--text-dark-secondary);
}

.info-value {
    color: var(--text-dark);
    font-weight: 600;
}

.danger-card {
    border-color: var(--danger);
}

.danger-text {
    color: var(--text-dark-secondary);
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

@media (min-width: 768px) {
    .user-details-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endpush
@endsection
