@extends('layouts.be_master')

@section('title', 'Admin Dashboard - Quorum')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="modern-dashboard">
	<div class="welcome-banner">
		<div class="welcome-content">
			<div class="welcome-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
				</svg>
			</div>
			<div>
				<h2 class="welcome-title">Welcome back, {{ auth()->user()->name }}</h2>
				<p class="welcome-subtitle">Here's what's happening with your system today</p>
			</div>
		</div>
		<div class="welcome-decoration">
			<div class="decoration-circle"></div>
			<div class="decoration-circle"></div>
			<div class="decoration-circle"></div>
		</div>
	</div>

	<div class="stats-grid">

		<div class="stat-card stat-primary">
			<div class="stat-card-inner">
				<div class="stat-info">
					<p class="stat-label">Total Users</p>
					<h3 class="stat-value">{{ $totalUsers }}</h3>
					<span class="stat-change positive">↑ 12% from last month</span>
				</div>
				<div class="stat-icon-wrapper stat-icon-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="9" cy="7" r="4"></circle>
						<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
				</div>
			</div>
		</div>

		<div class="stat-card stat-success">
			<div class="stat-card-inner">
				<div class="stat-info">
					<p class="stat-label">Active Courses</p>
					<h3 class="stat-value">{{ $activeCourses }}</h3>
					<span class="stat-change positive">↑ 5% from last month</span>
				</div>
				<div class="stat-icon-wrapper stat-icon-success">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
						<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
					</svg>
				</div>
			</div>
		</div>

		<div class="stat-card stat-warning">
			<div class="stat-card-inner">
				<div class="stat-info">
					<p class="stat-label">Enrollments</p>
					<h3 class="stat-value">{{ $totalEnrollments }}</h3>
					<span class="stat-change positive">↑ 8% from last month</span>
				</div>
				<div class="stat-icon-wrapper stat-icon-warning">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle>
						<polyline points="17 11 19 13 23 9"></polyline>
					</svg>
				</div>
			</div>
		</div>

		<div class="stat-card stat-danger">
			<div class="stat-card-inner">
				<div class="stat-info">
					<p class="stat-label">Departments</p>
					<h3 class="stat-value">{{ $departmentCount }}</h3>
					<span class="stat-change neutral">No change</span>
				</div>
				<div class="stat-icon-wrapper stat-icon-danger">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
					</svg>
				</div>
			</div>
		</div>
	</div>

	<div class="management-section">
		<div class="section-header">
			<h3>Quick Actions</h3>
			<p>Manage your system efficiently</p>
		</div>
		<div class="action-cards-grid">
			<a href="{{ route('dashboard.admin.users.index') }}" class="action-card">
				<div class="action-card-icon action-icon-primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="9" cy="7" r="4"></circle>
						<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
				</div>
				<div class="action-card-content">
					<h4>Manage Users</h4>
					<p>Students, teachers, and administrators</p>
				</div>
				<div class="action-card-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>

			<a href="{{ route('dashboard.admin.courses') }}" class="action-card">
				<div class="action-card-icon action-icon-success">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
						<path d="M6 12v5c3 3 9 3 12 0v-5"></path>
					</svg>
				</div>
				<div class="action-card-content">
					<h4>Manage Courses</h4>
					<p>Academic programs and classes</p>
				</div>
				<div class="action-card-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>

			<a href="{{ route('dashboard.admin.timetables.index') }}" class="action-card">
				<div class="action-card-icon action-icon-info">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6"></line>
						<line x1="8" y1="2" x2="8" y2="6"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
					</svg>
				</div>
				<div class="action-card-content">
					<h4>Manage Timetables</h4>
					<p>Schedule and class assignments</p>
				</div>
				<div class="action-card-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>

			<a href="{{ route('dashboard.admin.exams.index') }}" class="action-card">
				<div class="action-card-icon action-icon-warning">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
						<polyline points="14 2 14 8 20 8"></polyline>
						<line x1="16" y1="13" x2="8" y2="13"></line>
						<line x1="16" y1="17" x2="8" y2="17"></line>
						<polyline points="10 9 9 9 8 9"></polyline>
					</svg>
				</div>
				<div class="action-card-content">
					<h4>Manage Exams</h4>
					<p>Exam dates and scheduling</p>
				</div>
				<div class="action-card-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>

			<a href="{{ route('dashboard.admin.rooms.index') }}" class="action-card">
				<div class="action-card-icon action-icon-danger">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="9" y1="3" x2="9" y2="21"></line>
					</svg>
				</div>
				<div class="action-card-content">
					<h4>Manage Rooms</h4>
					<p>Campus rooms and facilities</p>
				</div>
				<div class="action-card-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>

			<a href="{{ route('dashboard.admin.timetables.ga') }}" class="action-card action-card-featured">
				<div class="action-card-icon action-icon-purple">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="3"></circle>
						<path d="M12 1v6m0 6v6"></path>
						<path d="m15.88 8.12 4.24-4.24m0 16.24-4.24-4.24M8.12 8.12 3.88 3.88m0 16.24 4.24-4.24"></path>
					</svg>
				</div>
				<div class="action-card-content">
					<h4>Schedule Generator <span class="badge-ai">AI</span></h4>
					<p>Generate optimal timetables automatically</p>
				</div>
				<div class="action-card-arrow">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</div>
			</a>
		</div>
	</div>

	<div class="dashboard-bottom-grid">
		<div class="info-card activity-card">
			<div class="info-card-header">
				<h3>Recent Activity</h3>
				<span class="view-all-link">View all</span>
			</div>
			<div class="activity-list">
				<div class="activity-item">
					<div class="activity-icon-wrapper activity-icon-primary">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
							<circle cx="12" cy="7" r="4"></circle>
						</svg>
					</div>
					<div class="activity-content">
						<p class="activity-title">New user registered</p>
						<span class="activity-time">5 minutes ago</span>
					</div>
				</div>
				<div class="activity-item">
					<div class="activity-icon-wrapper activity-icon-success">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
							<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
						</svg>
					</div>
					<div class="activity-content">
						<p class="activity-title">Course "Web Development" updated</p>
						<span class="activity-time">2 hours ago</span>
					</div>
				</div>
				<div class="activity-item">
					<div class="activity-icon-wrapper activity-icon-info">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
							<line x1="16" y1="2" x2="16" y2="6"></line>
							<line x1="8" y1="2" x2="8" y2="6"></line>
							<line x1="3" y1="10" x2="21" y2="10"></line>
						</svg>
					</div>
					<div class="activity-content">
						<p class="activity-title">Timetable generated</p>
						<span class="activity-time">5 hours ago</span>
					</div>
				</div>
				<div class="activity-item">
					<div class="activity-icon-wrapper activity-icon-warning">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
							<polyline points="14 2 14 8 20 8"></polyline>
						</svg>
					</div>
					<div class="activity-content">
						<p class="activity-title">New exam scheduled</p>
						<span class="activity-time">1 day ago</span>
					</div>
				</div>
			</div>
		</div>

		<div class="info-card system-status-card">
			<div class="info-card-header">
				<h3>System Status</h3>
				<span class="status-badge status-all-good">All Systems Operational</span>
			</div>
			<div class="status-list">
				<div class="status-item">
					<div class="status-item-info">
						<div class="status-indicator-wrapper">
							<div class="status-indicator status-success"></div>
							<span class="status-name">Database Connection</span>
						</div>
						<span class="status-label status-healthy">Healthy</span>
					</div>
					<div class="status-bar">
						<div class="status-bar-fill" style="width: 98%;"></div>
					</div>
				</div>
				<div class="status-item">
					<div class="status-item-info">
						<div class="status-indicator-wrapper">
							<div class="status-indicator status-success"></div>
							<span class="status-name">API Services</span>
						</div>
						<span class="status-label status-healthy">Operational</span>
					</div>
					<div class="status-bar">
						<div class="status-bar-fill" style="width: 100%;"></div>
					</div>
				</div>
				<div class="status-item">
					<div class="status-item-info">
						<div class="status-indicator-wrapper">
							<div class="status-indicator status-warning"></div>
							<span class="status-name">Storage Space</span>
						</div>
						<span class="status-label status-warning-text">85% Used</span>
					</div>
					<div class="status-bar status-bar-warning">
						<div class="status-bar-fill" style="width: 85%;"></div>
					</div>
				</div>
				<div class="status-item">
					<div class="status-item-info">
						<div class="status-indicator-wrapper">
							<div class="status-indicator status-success"></div>
							<span class="status-name">Cache System</span>
						</div>
						<span class="status-label status-healthy">Optimal</span>
					</div>
					<div class="status-bar">
						<div class="status-bar-fill" style="width: 95%;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
/* Modern Dashboard Layout */
.modern-dashboard {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-xl);
}

/* Welcome Banner */
.welcome-banner {
	position: relative;
	background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%);
	border-radius: var(--radius-xl);
	padding: var(--spacing-xl);
	overflow: hidden;
	box-shadow: 0 20px 40px -12px rgba(99, 102, 241, 0.35);
}

.welcome-content {
	position: relative;
	z-index: 2;
	display: flex;
	align-items: center;
	gap: var(--spacing-lg);
}

.welcome-icon {
	width: 64px;
	height: 64px;
	background: rgba(255, 255, 255, 0.15);
	backdrop-filter: blur(10px);
	border-radius: var(--radius-lg);
	display: flex;
	align-items: center;
	justify-content: center;
	color: white;
}

.welcome-title {
	color: white;
	font-size: 1.875rem;
	font-weight: 700;
	margin: 0 0 var(--spacing-xs) 0;
	letter-spacing: -0.025em;
}

.welcome-subtitle {
	color: rgba(255, 255, 255, 0.85);
	font-size: 1.0625rem;
	margin: 0;
}

.welcome-decoration {
	position: absolute;
	right: -20px;
	top: 50%;
	transform: translateY(-50%);
	display: flex;
	gap: var(--spacing-md);
	opacity: 0.1;
}

.decoration-circle {
	width: 80px;
	height: 80px;
	border-radius: 50%;
	background: white;
}

.decoration-circle:nth-child(2) {
	width: 120px;
	height: 120px;
	margin-top: -20px;
}

.decoration-circle:nth-child(3) {
	width: 100px;
	height: 100px;
	margin-top: 20px;
}

/* Stats Grid */
.stats-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
	gap: var(--spacing-lg);
}

/* Stat Cards */
.stat-card {
	position: relative;
	background: var(--bg-dark-secondary);
	border: 1px solid var(--border-dark);
	border-radius: var(--radius-xl);
	padding: var(--spacing-xl);
	overflow: hidden;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card::before {
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 3px;
	background: linear-gradient(90deg, transparent, var(--primary), transparent);
	opacity: 0;
	transition: opacity 0.3s;
}

.stat-card:hover {
	border-color: var(--primary);
	transform: translateY(-4px);
	box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.4);
}

.stat-card:hover::before {
	opacity: 1;
}

.stat-card-inner {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	gap: var(--spacing-lg);
}

.stat-info {
	flex: 1;
}

.stat-label {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
	font-weight: 500;
	text-transform: uppercase;
	letter-spacing: 0.05em;
	margin: 0 0 var(--spacing-md) 0;
}

.stat-value {
	color: var(--text-dark);
	font-size: 2.25rem;
	font-weight: 700;
	margin: 0 0 var(--spacing-sm) 0;
	line-height: 1;
}

.stat-change {
	display: inline-flex;
	align-items: center;
	font-size: 0.8125rem;
	font-weight: 600;
	padding: 0.25rem 0.5rem;
	border-radius: var(--radius-sm);
	background: rgba(16, 185, 129, 0.1);
}

.stat-change.positive {
	color: var(--success);
	background: rgba(16, 185, 129, 0.1);
}

.stat-change.negative {
	color: var(--danger);
	background: rgba(239, 68, 68, 0.1);
}

.stat-change.neutral {
	color: var(--text-dark-secondary);
	background: rgba(148, 163, 184, 0.1);
}

.stat-icon-wrapper {
	width: 64px;
	height: 64px;
	border-radius: var(--radius-lg);
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.stat-icon-primary {
	background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(79, 70, 229, 0.15));
	color: #6366f1;
}

.stat-icon-success {
	background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.15));
	color: #10b981;
}

.stat-icon-warning {
	background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(217, 119, 6, 0.15));
	color: #f59e0b;
}

.stat-icon-danger {
	background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.15));
	color: #ef4444;
}

/* Management Section */
.management-section {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-lg);
}

.section-header {
	padding: 0 var(--spacing-xs);
}

.section-header h3 {
	color: var(--text-dark);
	font-size: 1.5rem;
	font-weight: 700;
	margin: 0 0 var(--spacing-xs) 0;
}

.section-header p {
	color: var(--text-dark-secondary);
	font-size: 0.9375rem;
	margin: 0;
}

/* Action Cards Grid */
.action-cards-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
	gap: var(--spacing-lg);
}

/* Action Cards */
.action-card {
	position: relative;
	background: var(--bg-dark-secondary);
	border: 1px solid var(--border-dark);
	border-radius: var(--radius-xl);
	padding: var(--spacing-xl);
	text-decoration: none;
	display: flex;
	align-items: center;
	gap: var(--spacing-lg);
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	overflow: hidden;
}

.action-card::after {
	content: '';
	position: absolute;
	inset: 0;
	background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.02));
	opacity: 0;
	transition: opacity 0.3s;
}

.action-card:hover {
	border-color: var(--primary);
	transform: translateY(-2px);
	box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.3);
}

.action-card:hover::after {
	opacity: 1;
}

.action-card:hover .action-card-arrow {
	transform: translateX(4px);
	color: var(--primary);
}

.action-card-featured {
	border-color: rgba(139, 92, 246, 0.3);
	background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(124, 58, 237, 0.05));
}

.action-card-featured:hover {
	border-color: #8b5cf6;
	box-shadow: 0 12px 24px -8px rgba(139, 92, 246, 0.3);
}

.action-card-icon {
	width: 56px;
	height: 56px;
	border-radius: var(--radius-lg);
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
	position: relative;
	z-index: 2;
}

.action-icon-primary {
	background: linear-gradient(135deg, #6366f1, #4f46e5);
	color: white;
	box-shadow: 0 8px 16px -4px rgba(99, 102, 241, 0.4);
}

.action-icon-success {
	background: linear-gradient(135deg, #10b981, #059669);
	color: white;
	box-shadow: 0 8px 16px -4px rgba(16, 185, 129, 0.4);
}

.action-icon-info {
	background: linear-gradient(135deg, #3b82f6, #2563eb);
	color: white;
	box-shadow: 0 8px 16px -4px rgba(59, 130, 246, 0.4);
}

.action-icon-warning {
	background: linear-gradient(135deg, #f59e0b, #d97706);
	color: white;
	box-shadow: 0 8px 16px -4px rgba(245, 158, 11, 0.4);
}

.action-icon-danger {
	background: linear-gradient(135deg, #ef4444, #dc2626);
	color: white;
	box-shadow: 0 8px 16px -4px rgba(239, 68, 68, 0.4);
}

.action-icon-purple {
	background: linear-gradient(135deg, #8b5cf6, #7c3aed);
	color: white;
	box-shadow: 0 8px 16px -4px rgba(139, 92, 246, 0.4);
}

.action-card-content {
	flex: 1;
	position: relative;
	z-index: 2;
}

.action-card h4 {
	color: var(--text-dark);
	font-size: 1.0625rem;
	font-weight: 600;
	margin: 0 0 var(--spacing-xs) 0;
	display: flex;
	align-items: center;
	gap: var(--spacing-sm);
}

.badge-ai {
	display: inline-flex;
	align-items: center;
	font-size: 0.625rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.05em;
	padding: 0.1875rem 0.5rem;
	border-radius: var(--radius-sm);
	background: linear-gradient(135deg, #8b5cf6, #7c3aed);
	color: white;
}

.action-card p {
	color: var(--text-dark-secondary);
	font-size: 0.875rem;
	margin: 0;
	line-height: 1.5;
}

.action-card-arrow {
	color: var(--text-dark-secondary);
	transition: all 0.3s;
	flex-shrink: 0;
	position: relative;
	z-index: 2;
}

/* Dashboard Bottom Grid */
.dashboard-bottom-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
	gap: var(--spacing-xl);
}

/* Info Cards */
.info-card {
	background: var(--bg-dark-secondary);
	border: 1px solid var(--border-dark);
	border-radius: var(--radius-xl);
	padding: var(--spacing-xl);
}

.info-card-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: var(--spacing-lg);
	padding-bottom: var(--spacing-lg);
	border-bottom: 1px solid var(--border-dark);
}

.info-card-header h3 {
	color: var(--text-dark);
	font-size: 1.25rem;
	font-weight: 700;
	margin: 0;
}

.view-all-link {
	color: var(--primary);
	font-size: 0.875rem;
	font-weight: 600;
	cursor: pointer;
	transition: color 0.2s;
}

.view-all-link:hover {
	color: var(--primary-light);
}

.status-badge {
	display: inline-flex;
	align-items: center;
	gap: 0.375rem;
	font-size: 0.75rem;
	font-weight: 600;
	padding: 0.375rem 0.75rem;
	border-radius: var(--radius-md);
}

.status-all-good {
	background: rgba(16, 185, 129, 0.1);
	color: var(--success);
}

/* Activity List */
.activity-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-md);
}

.activity-item {
	display: flex;
	align-items: flex-start;
	gap: var(--spacing-md);
	padding: var(--spacing-md);
	border-radius: var(--radius-lg);
	transition: background 0.2s;
}

.activity-item:hover {
	background: rgba(255, 255, 255, 0.02);
}

.activity-icon-wrapper {
	width: 40px;
	height: 40px;
	border-radius: var(--radius-md);
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.activity-icon-primary {
	background: rgba(99, 102, 241, 0.1);
	color: #6366f1;
}

.activity-icon-success {
	background: rgba(16, 185, 129, 0.1);
	color: #10b981;
}

.activity-icon-info {
	background: rgba(59, 130, 246, 0.1);
	color: #3b82f6;
}

.activity-icon-warning {
	background: rgba(245, 158, 11, 0.1);
	color: #f59e0b;
}

.activity-content {
	flex: 1;
}

.activity-title {
	color: var(--text-dark);
	font-size: 0.9375rem;
	font-weight: 500;
	margin: 0 0 0.25rem 0;
}

.activity-time {
	color: var(--text-dark-secondary);
	font-size: 0.8125rem;
}

/* System Status */
.status-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-lg);
}

.status-item {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
}

.status-item-info {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.status-indicator-wrapper {
	display: flex;
	align-items: center;
	gap: var(--spacing-md);
}

.status-indicator {
	width: 10px;
	height: 10px;
	border-radius: 50%;
}

.status-success {
	background: var(--success);
	box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
	animation: pulse-success 2s infinite;
}

.status-warning {
	background: var(--warning);
	box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
	animation: pulse-warning 2s infinite;
}

.status-name {
	color: var(--text-dark);
	font-size: 0.9375rem;
	font-weight: 500;
}

.status-label {
	font-size: 0.8125rem;
	font-weight: 600;
}

.status-healthy {
	color: var(--success);
}

.status-warning-text {
	color: var(--warning);
}

.status-bar {
	height: 6px;
	background: rgba(148, 163, 184, 0.1);
	border-radius: var(--radius-sm);
	overflow: hidden;
}

.status-bar-fill {
	height: 100%;
	background: linear-gradient(90deg, #10b981, #059669);
	border-radius: var(--radius-sm);
	transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.status-bar-warning .status-bar-fill {
	background: linear-gradient(90deg, #f59e0b, #d97706);
}

/* Animations */
@keyframes pulse-success {
	0%, 100% {
		box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
	}
	50% {
		box-shadow: 0 0 20px rgba(16, 185, 129, 0.8);
	}
}

@keyframes pulse-warning {
	0%, 100% {
		box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
	}
	50% {
		box-shadow: 0 0 20px rgba(245, 158, 11, 0.8);
	}
}

/* Responsive Design */
@media (max-width: 1200px) {
	.stats-grid {
		grid-template-columns: repeat(2, 1fr);
	}

	.action-cards-grid {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media (max-width: 768px) {
	.stats-grid {
		grid-template-columns: 1fr;
	}

	.action-cards-grid {
		grid-template-columns: 1fr;
	}

	.dashboard-bottom-grid {
		grid-template-columns: 1fr;
	}

	.welcome-title {
		font-size: 1.5rem;
	}

	.welcome-subtitle {
		font-size: 0.9375rem;
	}

	.stat-value {
		font-size: 1.875rem;
	}
}
</style>
@endpush
@endsection

