@extends('layouts.be_master')

@section('title', 'Student Dashboard - Quorum')
@section('page-title', 'My Dashboard')

@section('content')
<div class="dashboard-grid">
	<div class="dashboard-card card-welcome">
		<h2>Welcome back, {{ auth()->user()->name }}!</h2>
		<p>Here's an overview of your academic progress</p>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
				<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Enrolled Courses</h3>
			<p class="stat-value">{{ $courseCount }}</p>
		</div>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
				<polyline points="14 2 14 8 20 8"></polyline>
				<line x1="16" y1="13" x2="8" y2="13"></line>
				<line x1="16" y1="17" x2="8" y2="17"></line>
				<polyline points="10 9 9 9 8 9"></polyline>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Average Grade</h3>
			<p class="stat-value">{{ $averageGrade > 0 ? $averageGrade : 'N/A' }}</p>
		</div>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
				<line x1="16" y1="2" x2="16" y2="6"></line>
				<line x1="8" y1="2" x2="8" y2="6"></line>
				<line x1="3" y1="10" x2="21" y2="10"></line>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Total Credits</h3>
			<p class="stat-value">{{ $totalCredits }}</p>
		</div>
	</div>

	<div class="dashboard-card card-full">
		<div class="card-header">
			<h3>Recent Grades</h3>
			<a href="{{ route('dashboard.student.grades') }}" class="card-link">View all →</a>
		</div>
		<div class="grades-list">
			@forelse($enrolledCourses->filter(fn($e) => $e->grade !== null)->take(3) as $enrollment)
			<div class="grade-item">
				<div class="grade-info">
					<h4>{{ $enrollment->course->name }}</h4>
					<span class="grade-date">{{ $enrollment->created_at->format('F d, Y') }}</span>
				</div>
				<div class="grade-value {{ $enrollment->grade >= 17 ? 'grade-excellent' : 'grade-good' }}">{{ $enrollment->grade }}</div>
			</div>
			@empty
			<div style="padding: var(--spacing-md); color: var(--text-dark-secondary); text-align: center;">
				No grades yet
			</div>
			@endforelse
		</div>
	</div>

	<div class="dashboard-card card-full">
		<div class="card-header">
			<h3>My Courses</h3>
			<a href="{{ route('dashboard.student.subjects') }}" class="card-link">View all subjects →</a>
		</div>
		<div class="schedule-list">
			@forelse($enrolledCourses->take(3) as $enrollment)
			<div class="schedule-item">
				<div class="schedule-details">
					<h4>{{ $enrollment->course->name }}</h4>
					<span class="schedule-room">{{ $enrollment->course->department }} • {{ $enrollment->status }}</span>
				</div>
				<div style="text-align: right;">
					@if($enrollment->grade)
						<div class="grade-value {{ $enrollment->grade >= 17 ? 'grade-excellent' : 'grade-good' }}" style="margin: 0;">{{ $enrollment->grade }}</div>
					@else
						<span style="color: var(--text-dark-secondary); font-size: 0.875rem;">No grade yet</span>
					@endif
				</div>
			</div>
			@empty
			<div style="padding: var(--spacing-md); color: var(--text-dark-secondary); text-align: center;">
				No enrolled courses
			</div>
			@endforelse
		</div>
	</div>

	<div class="dashboard-card card-actions">
		<h3>Quick Actions</h3>
		<div class="actions-grid">
			<a href="{{ route('dashboard.student.subjects') }}" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
					<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
				</svg>
				My Subjects
			</a>
			<a href="{{ route('dashboard.student.schedule') }}" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
					<line x1="16" y1="2" x2="16" y2="6"></line>
					<line x1="8" y1="2" x2="8" y2="6"></line>
					<line x1="3" y1="10" x2="21" y2="10"></line>
				</svg>
				View Schedule
			</a>
			<a href="{{ route('dashboard.student.grades') }}" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
					<polyline points="14 2 14 8 20 8"></polyline>
				</svg>
				View Grades
			</a>
			<a href="{{ route('dashboard.student.exams') }}" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
					<polyline points="14 2 14 8 20 8"></polyline>
					<line x1="12" y1="18" x2="12" y2="12"></line>
					<line x1="9" y1="15" x2="15" y2="15"></line>
				</svg>
				Exam Schedule
			</a>
		</div>
	</div>
</div>

@push('styles')
<style>
.dashboard-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
	gap: var(--spacing-lg);
}

.dashboard-card {
	background: var(--bg-dark-secondary);
	border: 1px solid var(--border-dark);
	border-radius: var(--radius-lg);
	padding: var(--spacing-lg);
}

.card-welcome {
	grid-column: 1 / -1;
	background: linear-gradient(135deg, var(--primary), var(--primary-dark));
	border: none;
}

.card-welcome h2 {
	color: white;
	font-size: 1.75rem;
	margin-bottom: var(--spacing-xs);
}

.card-welcome p {
	color: rgba(255, 255, 255, 0.9);
	font-size: 1.125rem;
}

.card-stat {
	display: flex;
	gap: var(--spacing-md);
	align-items: center;
}

.stat-icon {
	width: 56px;
	height: 56px;
	border-radius: var(--radius-md);
	display: flex;
	align-items: center;
	justify-content: center;
	color: white;
}

.stat-content h3 {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
	font-weight: 500;
	margin-bottom: var(--spacing-xs);
}

.stat-value {
	font-size: 2rem;
	font-weight: 700;
	color: var(--text-dark);
}

.card-full {
	grid-column: 1 / -1;
}

.card-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: var(--spacing-lg);
}

.card-header h3 {
	font-size: 1.25rem;
	color: var(--text-dark);
	font-weight: 600;
}

.card-link {
	color: var(--primary-light);
	text-decoration: none;
	font-weight: 500;
	transition: color 0.2s;
}

.card-link:hover {
	color: var(--primary);
}

.grades-list, .schedule-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
}

.grade-item, .schedule-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: var(--spacing-md);
	background: rgba(255, 255, 255, 0.03);
	border-radius: var(--radius-md);
}

.grade-info h4, .schedule-details h4 {
	color: var(--text-dark);
	font-weight: 600;
	margin-bottom: var(--spacing-xs);
}

.grade-date, .schedule-room {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
}

.grade-value {
	font-size: 1.5rem;
	font-weight: 700;
	padding: var(--spacing-sm) var(--spacing-md);
	border-radius: var(--radius-md);
}

.grade-excellent {
	color: #10b981;
	background: rgba(16, 185, 129, 0.1);
}

.grade-good {
	color: #0ea5e9;
	background: rgba(14, 165, 233, 0.1);
}

.schedule-time {
	font-weight: 600;
	color: var(--primary-light);
	font-size: 0.9375rem;
}

.card-actions h3 {
	color: var(--text-dark);
	font-weight: 600;
	margin-bottom: var(--spacing-md);
}

.actions-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
	gap: var(--spacing-md);
}

.action-btn {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: var(--spacing-sm);
	padding: var(--spacing-lg);
	background: rgba(255, 255, 255, 0.03);
	border-radius: var(--radius-md);
	color: var(--text-dark);
	text-decoration: none;
	font-weight: 500;
	transition: all 0.2s;
}

.action-btn:hover {
	background: rgba(255, 255, 255, 0.08);
	transform: translateY(-2px);
}

.action-btn svg {
	color: var(--primary-light);
}
</style>
@endpush
@endsection
