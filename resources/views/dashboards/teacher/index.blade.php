@extends('layouts.be_master')

@section('title', 'Teacher Dashboard - Quorum')
@section('page-title', 'Teacher Dashboard')

@section('content')
<div class="dashboard-grid">
	<div class="dashboard-card card-welcome">
		<h2>Welcome, {{ auth()->user()->name }}</h2>
		<p>Manage your classes and student progress</p>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
				<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
			</svg>
		</div>
		<div class="stat-content">
			<h3>My Classes</h3>
			<p class="stat-value">{{ $classCount }}</p>
		</div>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
				<circle cx="9" cy="7" r="4"></circle>
				<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
				<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Total Students</h3>
			<p class="stat-value">{{ $totalStudents }}</p>
		</div>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
				<circle cx="8.5" cy="7" r="4"></circle>
				<polyline points="17 11 19 13 23 9"></polyline>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Attendance Rate</h3>
			<p class="stat-value">{{ $attendanceRate }}%</p>
		</div>
	</div>

	<div class="dashboard-card card-full">
		<div class="card-header">
			<h3>Today's Schedule</h3>
			<a href="#" class="card-link">Full schedule →</a>
		</div>
		<div class="schedule-list">
			<div class="schedule-item">
				<div class="schedule-time">09:00 - 10:30</div>
				<div class="schedule-details">
					<h4>Web Development - Year 2</h4>
					<span class="schedule-room">Room A-204 • 28 students</span>
				</div>
				<a href="#" class="schedule-action">Take Attendance</a>
			</div>
			<div class="schedule-item">
				<div class="schedule-time">11:00 - 12:30</div>
				<div class="schedule-details">
					<h4>Database Systems - Year 3</h4>
					<span class="schedule-room">Lab C-305 • 24 students</span>
				</div>
				<a href="#" class="schedule-action">Take Attendance</a>
			</div>
			<div class="schedule-item">
				<div class="schedule-time">14:00 - 15:30</div>
				<div class="schedule-details">
					<h4>Advanced Programming - Year 3</h4>
					<span class="schedule-room">Room B-101 • 32 students</span>
				</div>
				<a href="#" class="schedule-action">Take Attendance</a>
			</div>
		</div>
	</div>

	<div class="dashboard-card card-half">
		<div class="card-header">
			<h3>My Classes</h3>
			<a href="{{ route('dashboard.teacher.classes') }}" class="card-link">View all →</a>
		</div>
		<div class="classes-list">
			@forelse($teacherSubjects as $subject)
				<div class="class-item">
					<div class="class-info">
						<h4>{{ $subject->name }}</h4>
						<span class="class-meta">{{ $subject->course->name ?? 'General' }} • {{ $subject->course?->enrollments->count() ?? 0 }} students</span>
					</div>
				</div>
			@empty
				<div class="class-item">
					<p style="color: var(--text-dark-secondary); margin: 0;">No classes assigned</p>
				</div>
			@endforelse
		</div>
	</div>

	<div class="dashboard-card card-half">
		<div class="card-header">
			<h3>Pending Tasks</h3>
		</div>
		<div class="tasks-list">
			<div class="task-item">
				<div class="task-icon">📝</div>
				<div class="task-content">
					<p><strong>Grade Assignment #3</strong></p>
					<span class="task-meta">Web Development • 28 submissions</span>
				</div>
			</div>
			<div class="task-item">
				<div class="task-icon">📊</div>
				<div class="task-content">
					<p><strong>Submit Final Grades</strong></p>
					<span class="task-meta">Database Systems • Due Jan 15</span>
				</div>
			</div>
			<div class="task-item">
				<div class="task-icon">📋</div>
				<div class="task-content">
					<p><strong>Attendance Report</strong></p>
					<span class="task-meta">All classes • Due Jan 10</span>
				</div>
			</div>
		</div>
	</div>

	<div class="dashboard-card card-actions">
		<h3>Quick Actions</h3>
		<div class="actions-grid">
			<a href="{{ route('dashboard.teacher.classes') }}" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
					<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
				</svg>
				My Classes
			</a>
			<a href="{{ route('dashboard.teacher.attendance') }}" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
					<circle cx="8.5" cy="7" r="4"></circle>
					<polyline points="17 11 19 13 23 9"></polyline>
				</svg>
				Attendance
			</a>
			<a href="#" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
					<polyline points="14 2 14 8 20 8"></polyline>
				</svg>
				Grade Assignments
			</a>
			<a href="#" class="action-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
					<line x1="16" y1="2" x2="16" y2="6"></line>
					<line x1="8" y1="2" x2="8" y2="6"></line>
					<line x1="3" y1="10" x2="21" y2="10"></line>
				</svg>
				Schedule
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

.card-half {
	grid-column: span 2;
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

.schedule-list, .classes-list, .tasks-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
}

.schedule-item {
	display: grid;
	grid-template-columns: 100px 1fr auto;
	gap: var(--spacing-md);
	align-items: center;
	padding: var(--spacing-md);
	background: rgba(255, 255, 255, 0.03);
	border-radius: var(--radius-md);
}

.schedule-time {
	font-weight: 600;
	color: var(--primary-light);
	font-size: 0.9375rem;
}

.schedule-details h4 {
	color: var(--text-dark);
	font-weight: 600;
	margin-bottom: var(--spacing-xs);
}

.schedule-room {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
}

.schedule-action {
	padding: var(--spacing-sm) var(--spacing-md);
	background: var(--primary);
	color: white;
	text-decoration: none;
	border-radius: var(--radius-md);
	font-weight: 500;
	font-size: 0.875rem;
	transition: all 0.2s;
}

.schedule-action:hover {
	background: var(--primary-dark);
}

.class-item {
	padding: var(--spacing-md);
	background: rgba(255, 255, 255, 0.03);
	border-radius: var(--radius-md);
}

.class-info h4 {
	color: var(--text-dark);
	font-weight: 600;
	margin-bottom: var(--spacing-xs);
}

.class-meta {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
}

.task-item {
	display: flex;
	gap: var(--spacing-md);
	align-items: flex-start;
	padding: var(--spacing-md);
	background: rgba(255, 255, 255, 0.03);
	border-radius: var(--radius-md);
}

.task-icon {
	font-size: 1.5rem;
}

.task-content p {
	color: var(--text-dark);
	margin-bottom: var(--spacing-xs);
}

.task-meta {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
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

@media (max-width: 1200px) {
	.card-half {
		grid-column: span 1;
	}

	.schedule-item {
		grid-template-columns: 1fr;
		gap: var(--spacing-sm);
	}
}
</style>
@endpush
@endsection
