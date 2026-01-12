@extends('layouts.be_master')

@section('title', "$user->name - Profile - Quorum")
@section('page-title', 'User Profile')

@section('content')
<div class="profile-page">
    <!-- Header Section with Cover & Profile Picture -->
    <div class="profile-header">
        <div class="cover-image" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));"></div>
        <div class="profile-info-header">
            <div class="profile-picture-container">
                @if($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture)))
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="profile-picture">
                @else
                    <div class="profile-picture-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="profile-header-info">
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-role">{{ ucfirst($user->role ?? 'Student') }}</p>
                @if($user->email)
                    <p class="profile-email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        {{ $user->email }}
                    </p>
                @endif
            </div>
            @auth
                @if(auth()->id() === $user->id || auth()->user()->role === 'admin')
                    <div class="profile-actions">
                        <a href="{{ route('profile.edit', $user) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit Profile
                        </a>
                        <a href="{{ route('portal') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 4 12 14.01 9 13 2 20"></polyline>
                                <path d="M22 4h-7"></path>
                                <path d="M22 4v7"></path>
                            </svg>
                            Go to Dashboard
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
        <!-- Left Column: Basic Info & Stats -->
        <div class="profile-left">
            <!-- Contact Information Card -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Contact Information</h3>
                </div>
                <div class="info-grid">
                    @if($user->phone)
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <p class="info-value">{{ $user->phone }}</p>
                        </div>
                    @endif

                    @if($user->address)
                        <div class="info-item">
                            <span class="info-label">Address</span>
                            <p class="info-value">{{ $user->address }}</p>
                        </div>
                    @endif

                    @if($user->nif)
                        <div class="info-item">
                            <span class="info-label">NIF</span>
                            <p class="info-value">{{ $user->nif }}</p>
                        </div>
                    @endif

                    @if($user->date_of_birth)
                        <div class="info-item">
                            <span class="info-label">Date of Birth</span>
                            <p class="info-value">{{ $user->date_of_birth->format('F d, Y') }}</p>
                        </div>
                    @endif

                    <div class="info-item">
                        <span class="info-label">Member Since</span>
                        <p class="info-value">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>

                    @if($user->last_login)
                        <div class="info-item">
                            <span class="info-label">Last Login</span>
                            <p class="info-value">{{ $user->last_login->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Academic Stats Card (for students) -->
            @if($user->role === 'student' || !$user->role)
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Academic Statistics</h3>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <span class="stat-label">Enrolled Courses</span>
                            <p class="stat-value">{{ $courseCount }}</p>
                        </div>
                        <div class="stat-box">
                            <span class="stat-label">Average Grade</span>
                            <p class="stat-value">{{ $averageGrade > 0 ? $averageGrade : 'N/A' }}</p>
                        </div>
                        <div class="stat-box">
                            <span class="stat-label">GPA</span>
                            <p class="stat-value">{{ $gpa > 0 ? $gpa : 'N/A' }}</p>
                        </div>
                        <div class="stat-box">
                            <span class="stat-label">Total Credits</span>
                            <p class="stat-value">{{ $totalCredits }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Enrolled Courses (for students) -->
        @if($user->role === 'student' && $enrolledCourses->count() > 0)
            <div class="profile-right">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Enrolled Courses</h3>
                        <a href="{{ route('dashboard.student.subjects') }}" class="card-link">View all →</a>
                    </div>
                    <div class="courses-list">
                        @foreach($enrolledCourses->take(5) as $enrollment)
                            <div class="course-item">
                                <div class="course-info">
                                    <h4>{{ $enrollment->course->name }}</h4>
                                    <span class="course-code">{{ $enrollment->course->code ?? 'N/A' }}</span>
                                    <p class="course-meta">{{ $enrollment->course->department }} • {{ $enrollment->course->credits }} credits</p>
                                </div>
                                <div class="course-grade">
                                    @if($enrollment->grade)
                                        <span class="grade-badge {{ $enrollment->grade >= 17 ? 'grade-excellent' : 'grade-good' }}">{{ $enrollment->grade }}/20</span>
                                    @else
                                        <span class="grade-badge grade-pending">No grade</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.profile-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.profile-header {
    position: relative;
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.cover-image {
    height: 200px;
    width: 100%;
}

.profile-info-header {
    padding: 0 var(--spacing-lg) var(--spacing-lg);
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: var(--spacing-lg);
    align-items: flex-start;
    margin-top: -60px;
    position: relative;
    z-index: 2;
}

.profile-picture-container {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid var(--bg-dark-secondary);
    overflow: hidden;
    background: var(--bg-dark);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-picture {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-picture-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.profile-header-info {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    justify-content: flex-end;
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.profile-role {
    color: var(--text-dark-secondary);
    font-weight: 500;
    margin: 0;
}

.profile-email {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--text-dark-secondary);
    margin: 0;
}

.profile-email svg {
    flex-shrink: 0;
}

.profile-actions {
    display: flex;
    gap: var(--spacing-sm);
    justify-content: flex-end;
}

.profile-actions .btn {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.profile-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
}

.profile-left {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.profile-right {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-lg);
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.info-label {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.info-value {
    color: var(--text-dark);
    font-weight: 500;
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
}

.stat-box {
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-dark);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.stat-label {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.courses-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.course-item {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-dark);
    align-items: center;
}

.course-info h4 {
    color: var(--text-dark);
    font-weight: 600;
    margin: 0 0 var(--spacing-xs) 0;
}

.course-code {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
    margin-right: var(--spacing-xs);
}

.course-meta {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
    margin: 0;
}

.course-grade {
    display: flex;
    align-items: center;
}

.grade-badge {
    padding: 6px 12px;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
}

.grade-badge.grade-excellent {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.grade-badge.grade-good {
    background: rgba(14, 165, 233, 0.1);
    color: #0ea5e9;
}

.grade-badge.grade-pending {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

@media (max-width: 1024px) {
    .profile-info-header {
        grid-template-columns: auto 1fr;
    }

    .profile-actions {
        grid-column: 1 / -1;
    }

    .profile-content {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .profile-info-header {
        grid-template-columns: 1fr;
    }

    .cover-image {
        height: 120px;
    }

    .profile-picture-container {
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }

    .profile-header-info {
        text-align: center;
    }

    .profile-actions {
        flex-direction: column;
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@endsection
