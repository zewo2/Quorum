@extends('layouts.be_master')

@section('title', 'Admin Dashboard - Quorum')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="dashboard-grid">
	<div class="dashboard-card card-welcome">
		<h2>Welcome, {{ auth()->user()->name }}</h2>
		<p>System administration and management overview</p>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
				<circle cx="9" cy="7" r="4"></circle>
				<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
				<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Total Users</h3>
			<p class="stat-value">1,245</p>
		</div>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
				<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Active Courses</h3>
			<p class="stat-value">87</p>
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
			<h3>Timetables</h3>
			<p class="stat-value">24</p>
		</div>
	</div>

	<div class="dashboard-card card-stat">
		<div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
			</svg>
		</div>
		<div class="stat-content">
			<h3>Rooms</h3>
			<p class="stat-value">156</p>
		</div>
	</div>

	<div class="dashboard-card card-full">
		<h3>Management</h3>
		<div class="admin-actions-grid">
			<a href="{{ route('dashboard.admin.users.index') }}" class="admin-action-card">
				<div class="admin-action-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="9" cy="7" r="4"></circle>
						<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
				</div>
				<h4>Manage Users</h4>
				<p>Students, teachers, and administrators</p>
			</a>

			<a href="{{ route('dashboard.admin.courses') }}" class="admin-action-card">
				<div class="admin-action-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
						<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
					</svg>
				</div>
				<h4>Manage Courses</h4>
				<p>Course catalog and enrollments</p>
			</a>

			<a href="{{ route('dashboard.admin.timetables') }}" class="admin-action-card">
				<div class="admin-action-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6"></line>
						<line x1="8" y1="2" x2="8" y2="6"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
					</svg>
				</div>
				<h4>Timetables</h4>
				<p>Schedule management and conflicts</p>
			</a>

			<a href="#" class="admin-action-card card-highlight">
				<div class="admin-action-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M18 20V10"></path>
						<path d="M12 20V4"></path>
						<path d="M6 20v-6"></path>
					</svg>
				</div>
				<h4>Genetic Algorithm</h4>
				<p>AI-powered schedule optimization</p>
			</a>
		</div>
	</div>

	<div class="dashboard-card card-half">
		<div class="card-header">
			<h3>Recent Activity</h3>
		</div>
		<div class="activity-list">
			<div class="activity-item">
				<div class="activity-icon">👤</div>
				<div class="activity-content">
					<p><strong>New user registered</strong></p>
					<span class="activity-time">5 minutes ago</span>
				</div>
			</div>
			<div class="activity-item">
				<div class="activity-icon">📚</div>
				<div class="activity-content">
					<p><strong>Course "Web Dev" updated</strong></p>
					<span class="activity-time">2 hours ago</span>
				</div>
			</div>
			<div class="activity-item">
				<div class="activity-icon">📅</div>
				<div class="activity-content">
					<p><strong>Timetable generated</strong></p>
					<span class="activity-time">5 hours ago</span>
				</div>
			</div>
		</div>
	</div>

	<div class="dashboard-card card-half">
		<div class="card-header">
			<h3>System Status</h3>
		</div>
		<div class="status-list">
			<div class="status-item">
				<div class="status-indicator status-success"></div>
				<span>Database</span>
				<span class="status-label">Healthy</span>
			</div>
			<div class="status-item">
				<div class="status-indicator status-success"></div>
				<span>API</span>
				<span class="status-label">Operational</span>
			</div>
			<div class="status-item">
				<div class="status-indicator status-warning"></div>
				<span>Storage</span>
				<span class="status-label">85% Full</span>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
.dashboard-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

.admin-actions-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
	gap: var(--spacing-md);
	margin-top: var(--spacing-md);
}

.admin-action-card {
	background: rgba(255, 255, 255, 0.03);
	border: 1px solid var(--border-dark);
	border-radius: var(--radius-lg);
	padding: var(--spacing-lg);
	text-decoration: none;
	color: inherit;
	transition: all 0.2s;
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
}

.admin-action-card:hover {
	background: rgba(255, 255, 255, 0.08);
	transform: translateY(-2px);
	border-color: var(--primary);
}

.card-highlight {
	border-color: var(--primary);
	background: rgba(79, 70, 229, 0.1);
}

.admin-action-icon {
	width: 56px;
	height: 56px;
	background: var(--primary);
	border-radius: var(--radius-md);
	display: flex;
	align-items: center;
	justify-content: center;
	color: white;
}

.admin-action-card h4 {
	color: var(--text-dark);
	font-size: 1.125rem;
	font-weight: 600;
}

.admin-action-card p {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
}

.activity-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
	margin-top: var(--spacing-md);
}

.activity-item {
	display: flex;
	gap: var(--spacing-md);
	align-items: flex-start;
}

.activity-icon {
	font-size: 1.5rem;
}

.activity-content p {
	color: var(--text-dark);
	margin-bottom: var(--spacing-xs);
}

.activity-time {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
}

.status-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
	margin-top: var(--spacing-md);
}

.status-item {
	display: flex;
	align-items: center;
	gap: var(--spacing-md);
	color: var(--text-dark);
}

.status-indicator {
	width: 12px;
	height: 12px;
	border-radius: 50%;
}

.status-success {
	background: var(--success);
	box-shadow: 0 0 8px var(--success);
}

.status-warning {
	background: var(--warning);
	box-shadow: 0 0 8px var(--warning);
}

.status-label {
	margin-left: auto;
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
}

@media (max-width: 1200px) {
	.card-half {
		grid-column: span 1;
	}
}
</style>
@endpush
@endsection
