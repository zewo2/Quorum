@extends('layouts.be_master')

@section('title', 'My Grades - Quorum')
@section('page-title', 'My Grades')

@section('content')
<div class="grades-page">
	<div class="dashboard-card summary-card">
		<div class="summary-item">
			<p class="stat-label">Current GPA</p>
			<p class="stat-value">{{ $gpa }}</p>
			<span class="stat-meta">Based on all courses</span>
		</div>
		<div class="summary-item">
			<p class="stat-label">Cumulative Average</p>
			<p class="stat-value">{{ $averageGrade }}</p>
			<span class="stat-meta">Out of 20</span>
		</div>
		<div class="summary-item">
			<p class="stat-label">Grade Range</p>
			<p class="stat-value">{{ $highestGrade > 0 ? $lowestGrade . '-' . $highestGrade : 'N/A' }}</p>
			<span class="stat-meta">Lowest to Highest</span>
		</div>
	</div>

	<div class="dashboard-card">
		<div class="card-header">
			<h3>Grades by Course</h3>
		</div>

		<div class="grades-grid">
			@forelse($enrolledCourses as $enrollment)
				<div class="subject-grades">
					<div class="subject-header">
						<div>
							<h4>{{ $enrollment->course->name }}</h4>
							<span class="course-code">{{ $enrollment->course->code ?? 'N/A' }}</span>
						</div>
					@if($enrollment->final_grade)
						<span class="grade-badge">{{ $enrollment->final_grade }}/20</span>
						@else
							<span class="grade-badge" style="background: #6b7280;">No Grade</span>
						@endif
					</div>
					<div class="assessments-list">
						<div class="assessment-row">
							<div class="assessment-info">
								<p class="assessment-title">{{ $enrollment->course->name }}</p>
								<span class="assessment-meta">Status: {{ $enrollment->status }}</span>
							</div>
							<div class="assessment-grade">
							@if($enrollment->final_grade)
								<span class="grade-value">{{ $enrollment->final_grade }}/20</span>
								<div class="grade-bar">
									<div class="grade-fill" style="width: {{ ($enrollment->final_grade / 20) * 100 }}%;"></div>
									</div>
								@else
									<span class="grade-value" style="color: #9ca3af;">Pending</span>
									<div class="grade-bar">
										<div class="grade-fill" style="width: 0%;"></div>
									</div>
								@endif
							</div>
						</div>
						<div class="assessment-row">
							<div class="assessment-info">
								<p class="assessment-title">Credits</p>
								<span class="assessment-meta">{{ $enrollment->course->department }}</span>
							</div>
							<div class="assessment-grade">
								<span class="grade-value">{{ $enrollment->course->total_years }} years</span>
							</div>
						</div>
					</div>
				</div>
			@empty
				<div style="grid-column: 1 / -1; padding: var(--spacing-lg); text-align: center; color: var(--text-dark-secondary);">
					No enrolled courses with grades yet
				</div>
			@endforelse
		</div>
	</div>

	<div class="dashboard-grid detail-grid">
		<div class="dashboard-card" style="margin-bottom: 0.6cm">
			<div class="card-header">
				<h3>Grade Statistics</h3>
				<span class="chip">All Courses</span>
			</div>
			<div class="distribution">
				<div class="dist-row">
					<span class="dist-label">Average</span>
					<div class="dist-bar"><div class="dist-fill" style="width: {{ $averageGrade > 0 ? ($averageGrade / 20) * 100 : 0 }}%;"></div></div>
					<span class="dist-count">{{ $averageGrade }}</span>
				</div>
				<div class="dist-row">
					<span class="dist-label">Highest</span>
					<div class="dist-bar"><div class="dist-fill" style="width: {{ $highestGrade > 0 ? ($highestGrade / 20) * 100 : 0 }}%;"></div></div>
					<span class="dist-count">{{ $highestGrade > 0 ? $highestGrade : 'N/A' }}</span>
				</div>
				<div class="dist-row">
					<span class="dist-label">Lowest</span>
					<div class="dist-bar"><div class="dist-fill" style="width: {{ $lowestGrade > 0 ? ($lowestGrade / 20) * 100 : 0 }}%;"></div></div>
					<span class="dist-count">{{ $lowestGrade > 0 ? $lowestGrade : 'N/A' }}</span>
				</div>
				<div class="dist-row">
					<span class="dist-label">GPA (4.0)</span>
					<div class="dist-bar"><div class="dist-fill" style="width: {{ $gpa > 0 ? ($gpa / 4) * 100 : 0 }}%;"></div></div>
					<span class="dist-count">{{ $gpa }}</span>
				</div>
			</div>
		</div>

		<div class="dashboard-card">
			<div class="card-header">
				<h3>Course Summary</h3>
				<a href="{{ route('dashboard.student.subjects') }}" class="card-link">View subjects →</a>
			</div>
			<div class="upcoming-list">
				@forelse($enrolledCourses as $enrollment)
				<div class="upcoming-item">
					<div class="pending-dot" style="background: {{ $enrollment->final_grade ? ($enrollment->final_grade >= 17 ? '#10b981' : '#0ea5e9') : '#9ca3af' }};"></div>
					<div>
						<p class="item-title">{{ $enrollment->course->name }}</p>
						<span class="item-sub">{{ $enrollment->course->code ?? 'N/A' }} • {{ $enrollment->course->department }}</span>
					</div>
					@if($enrollment->final_grade)
						<span class="badge badge-success">{{ $enrollment->final_grade }}/20</span>
					@else
						<span class="badge badge-secondary">Pending</span>
					@endif
				</div>
				@empty
				<div style="padding: var(--spacing-md); color: var(--text-dark-secondary); text-align: center;">
					No enrolled courses
				</div>
				@endforelse
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
.grades-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.summary-card { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-lg); border: 1px solid var(--border-dark); }
.summary-item { padding: var(--spacing-lg); }
.stat-label { color: var(--text-dark-secondary); font-size: 0.9rem; }
.stat-value { font-size: 1.8rem; font-weight: 700; color: var(--text-dark); }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.85rem; }

.filter-select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }

.grades-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: var(--spacing-lg); margin-top: var(--spacing-md); }
.subject-grades { background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-lg); padding: var(--spacing-lg); }
.subject-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-md); border-bottom: 1px solid var(--border-dark); }
.subject-header h4 { color: var(--text-dark); font-weight: 700; }
.course-code { color: var(--text-dark-secondary); font-size: 0.85rem; margin-left: var(--spacing-sm); }
.grade-badge { padding: 6px 12px; border-radius: 999px; background: var(--primary); color: white; font-weight: 700; }

.assessments-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.assessment-row { display: flex; justify-content: space-between; gap: var(--spacing-md); align-items: center; }
.assessment-info { flex: 1; }
.assessment-title { color: var(--text-dark); font-weight: 600; }
.assessment-meta { color: var(--text-dark-secondary); font-size: 0.85rem; }
.assessment-grade { display: flex; flex-direction: column; gap: var(--spacing-xs); align-items: flex-end; }
.grade-value { color: var(--text-dark); font-weight: 700; }
.grade-bar { width: 100px; height: 4px; background: rgba(255, 255, 255, 0.1); border-radius: 999px; overflow: hidden; }
.grade-fill { background: linear-gradient(90deg, var(--primary), var(--primary-light)); height: 100%; }

.detail-grid { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
.distribution { display: flex; flex-direction: column; gap: var(--spacing-md); }
.dist-row { display: grid; grid-template-columns: 100px 1fr 40px; gap: var(--spacing-md); align-items: center; }
.dist-label { color: var(--text-dark-secondary); font-size: 0.9rem; }
.dist-bar { height: 8px; background: rgba(255, 255, 255, 0.06); border-radius: 999px; overflow: hidden; }
.dist-fill { background: var(--primary); height: 100%; }
.dist-count { color: var(--text-dark); font-weight: 600; text-align: right; }

.upcoming-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.upcoming-item { display: grid; grid-template-columns: auto 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.pending-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--primary); }
.item-title { color: var(--text-dark); font-weight: 600; }
.item-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.badge { padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-secondary { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }

.chip { padding: 6px 12px; border-radius: 999px; border: 1px solid var(--border-dark); color: var(--text-dark-secondary); font-size: 0.85rem; }

@media (max-width: 960px) {
	.grades-grid { grid-template-columns: 1fr; }
	.assessment-row { flex-direction: column; align-items: flex-start; }
	.upcoming-item { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
