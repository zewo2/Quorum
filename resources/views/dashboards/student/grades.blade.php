@extends('layouts.be_master')

@section('title', 'My Grades - Quorum')
@section('page-title', 'My Grades')

@section('content')
<div class="grades-page">
	<div class="dashboard-card summary-card">
		<div class="summary-item">
			<p class="stat-label">Current GPA (dummy)</p>
			<p class="stat-value">3.6</p>
			<span class="stat-meta">Based on all assessments</span>
		</div>
		<div class="summary-item">
			<p class="stat-label">Cumulative Average</p>
			<p class="stat-value">15.8</p>
			<span class="stat-meta">Out of 20</span>
		</div>
		<div class="summary-item">
			<p class="stat-label">Grade Trend</p>
			<p class="stat-value">↑ +0.3</p>
			<span class="stat-meta">vs last month</span>
		</div>
	</div>

	<div class="dashboard-card">
		<div class="card-header">
			<h3>Grades by Subject</h3>
			<select class="filter-select">
				<option>All Time</option>
				<option>This Semester</option>
				<option>Last Month</option>
			</select>
		</div>

		<div class="grades-grid">
			@php
				$grades = [
					['subject' => 'Web Development', 'code' => 'CS210', 'assessments' => [
						['title' => 'Project 1: Landing Page', 'grade' => 19, 'weight' => 10],
						['title' => 'Quiz: HTML/CSS', 'grade' => 18, 'weight' => 5],
						['title' => 'Midterm Exam', 'grade' => 17, 'weight' => 35],
					]],
					['subject' => 'Data Structures', 'code' => 'CS220', 'assessments' => [
						['title' => 'Problem Set 1', 'grade' => 17, 'weight' => 10],
						['title' => 'Problem Set 2', 'grade' => 16, 'weight' => 10],
						['title' => 'Lab Work', 'grade' => 18, 'weight' => 25],
					]],
					['subject' => 'Database Systems', 'code' => 'CS330', 'assessments' => [
						['title' => 'Design Project', 'grade' => 16, 'weight' => 30],
						['title' => 'Implementation', 'grade' => 16, 'weight' => 35],
						['title' => 'Documentation', 'grade' => 17, 'weight' => 10],
					]],
				];
			@endphp

			@foreach($grades as $course)
				<div class="subject-grades">
					<div class="subject-header">
						<div>
							<h4>{{ $course['subject'] }}</h4>
							<span class="course-code">{{ $course['code'] }}</span>
						</div>
						@php
							$avgGrade = round(array_sum(array_column($course['assessments'], 'grade')) / count($course['assessments']), 1);
						@endphp
						<span class="grade-badge">{{ $avgGrade }}/20</span>
					</div>
					<div class="assessments-list">
						@foreach($course['assessments'] as $assessment)
							<div class="assessment-row">
								<div class="assessment-info">
									<p class="assessment-title">{{ $assessment['title'] }}</p>
									<span class="assessment-meta">Weight: {{ $assessment['weight'] }}%</span>
								</div>
								<div class="assessment-grade">
									<span class="grade-value">{{ $assessment['grade'] }}/20</span>
									<div class="grade-bar">
										<div class="grade-fill" style="width: {{ ($assessment['grade'] / 20) * 100 }}%;"></div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endforeach
		</div>
	</div>

	<div class="dashboard-grid detail-grid">
		<div class="dashboard-card">
			<div class="card-header">
				<h3>Grade Distribution</h3>
				<span class="chip">This Semester</span>
			</div>
			<div class="distribution">
				<div class="dist-row">
					<span class="dist-label">A+ (19-20)</span>
					<div class="dist-bar"><div class="dist-fill" style="width: 20%;"></div></div>
					<span class="dist-count">2</span>
				</div>
				<div class="dist-row">
					<span class="dist-label">A (17-18)</span>
					<div class="dist-bar"><div class="dist-fill" style="width: 40%;"></div></div>
					<span class="dist-count">4</span>
				</div>
				<div class="dist-row">
					<span class="dist-label">B+ (15-16)</span>
					<div class="dist-bar"><div class="dist-fill" style="width: 35%;"></div></div>
					<span class="dist-count">3</span>
				</div>
				<div class="dist-row">
					<span class="dist-label">B (13-14)</span>
					<div class="dist-bar"><div class="dist-fill" style="width: 5%;"></div></div>
					<span class="dist-count">1</span>
				</div>
			</div>
		</div>

		<div class="dashboard-card">
			<div class="card-header">
				<h3>Upcoming Assessments</h3>
				<a href="{{ route('dashboard.student.schedule') }}" class="card-link">View schedule →</a>
			</div>
			<div class="upcoming-list">
				<div class="upcoming-item">
					<div class="pending-dot"></div>
					<div>
						<p class="item-title">Final Exam: Web Development</p>
						<span class="item-sub">CS210 • Jan 22 • Weight: 35%</span>
					</div>
					<span class="badge badge-warning">14 days</span>
				</div>
				<div class="upcoming-item">
					<div class="pending-dot"></div>
					<div>
						<p class="item-title">Project 3: Database Design</p>
						<span class="item-sub">CS330 • Jan 15 • Weight: 30%</span>
					</div>
					<span class="badge badge-secondary">7 days</span>
				</div>
				<div class="upcoming-item">
					<div class="pending-dot"></div>
					<div>
						<p class="item-title">Problem Set 3</p>
						<span class="item-sub">CS220 • Jan 12 • Weight: 10%</span>
					</div>
					<span class="badge badge-success">4 days</span>
				</div>
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
