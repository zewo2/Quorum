@extends('layouts.be_master')

@section('title', 'Exams - Quorum')
@section('page-title', 'Exams & Assessments')

@section('content')
<div class="exams-page">
	<div class="dashboard-card header-section">
		<div class="header-left">
			<label class="field">
				<span>Filter by Status</span>
				<select>
					<option>All Courses</option>
					<option>Active</option>
					<option>Completed</option>
				</select>
			</label>
			<label class="field">
				<span>Sort by</span>
				<select>
					<option>Status</option>
					<option>Course Name</option>
					<option>Grade</option>
				</select>
			</label>
		</div>
		<div class="header-actions">
			<a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary">Back to Subjects</a>
		</div>
	</div>

	<div class="exams-grid">
		@forelse($enrolledCourses as $enrollment)
			@php
				$nextExam = $nextExamByCourse->get($enrollment->course_id);
				$isUpcoming = $nextExam && $nextExam->exam_date->startOfDay()->gte($today);
			@endphp
			<div class="exam-card exam-{{ $enrollment->status }}">
				<div class="card-header-exam">
					<div>
						<h4>{{ $enrollment->course->name }}</h4>
						<span class="course-code">{{ $enrollment->course->code ?? 'N/A' }}</span>
					</div>
					<span class="exam-badge exam-{{ $enrollment->status }}">
						{{ ucfirst($enrollment->status) }}
					</span>
				</div>

				<div class="exam-details">
					<div class="detail-row">
						<span class="detail-icon">📚</span>
						<div>
							<span class="detail-label">Department</span>
							<p class="detail-value">{{ $enrollment->course->department }}</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">📊</span>
						<div>
							<span class="detail-label">Credits</span>
							<p class="detail-value">{{ $enrollment->course->credits }} credits</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">✓</span>
						<div>
							<span class="detail-label">Status</span>
							<p class="detail-value">{{ ucfirst($enrollment->status) }}</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">📝</span>
						<div>
							<span class="detail-label">Description</span>
							<p class="detail-value">{{ Str::limit($enrollment->course->description, 50) }}</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">🗓️</span>
						<div>
							<span class="detail-label">Next Exam</span>
							@if($nextExam)
								<p class="detail-value">
									{{ $nextExam->subject?->name ?? 'Subject' }} • {{ $nextExam->exam_date->format('M d, Y') }}
								</p>
							@else
								<p class="detail-value">No exam scheduled</p>
							@endif
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">⏰</span>
						<div>
							<span class="detail-label">Exam Time</span>
							@if($nextExam)
								<p class="detail-value">
									{{ $nextExam->start_time->format('H:i') }} - {{ $nextExam->end_time->format('H:i') }}
									@if($nextExam->room)
										• {{ $nextExam->room }}
									@endif
								</p>
							@else
								<p class="detail-value">TBA</p>
							@endif
						</div>
					</div>
				</div>

				@if($enrollment->grade)
					<div class="result-section">
						<div class="result-header">
							<span>Grade</span>
							<span class="grade-badge">{{ $enrollment->grade }}/20</span>
						</div>
						<div class="result-bar">
							<div class="result-fill" style="width: {{ ($enrollment->grade / 20) * 100 }}%;"></div>
						</div>
					</div>
				@else
					<div class="prep-section">
						<div class="prep-header">
							<span class="prep-label">Exam Status</span>
							<span class="prep-percent">{{ $isUpcoming ? 'Upcoming' : 'TBA' }}</span>
						</div>
						<div class="prep-tips">
							<a href="{{ route('dashboard.student.schedule') }}" class="action-link">View schedule →</a>
							<a href="{{ route('dashboard.student.subjects') }}" class="action-link">View subject →</a>
						</div>
					</div>
				@endif

				<div class="card-footer-exam">
					<a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary btn-small">Details</a>
					@if($enrollment->grade)
						<span class="btn btn-secondary btn-small" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: none; cursor: default;">Graded</span>
					@else
						<span class="btn btn-secondary btn-small" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: none; cursor: default;">Pending</span>
					@endif
				</div>
			</div>
		@empty
			<div style="padding: var(--spacing-lg); color: var(--text-dark-secondary); text-align: center; grid-column: 1 / -1;">
				No enrolled courses
			</div>
		@endforelse
	</div>

	<div class="dashboard-card stats-section">
		<div class="card-header">
			<h3>Summary</h3>
			<span class="chip">All Courses</span>
		</div>

		<div class="stats-grid">
			<div class="stat-box">
				<p class="stat-label">Total Courses</p>
				<p class="stat-value">{{ $enrolledCourses->count() }}</p>
				<span class="stat-meta">Enrolled courses</span>
			</div>
			<div class="stat-box">
				<p class="stat-label">Graded</p>
				<p class="stat-value">{{ $enrolledCourses->filter(fn($e) => $e->grade !== null)->count() }}</p>
				<span class="stat-meta">With grades</span>
			</div>
			<div class="stat-box">
				<p class="stat-label">Pending</p>
				<p class="stat-value">{{ $enrolledCourses->filter(fn($e) => $e->grade === null)->count() }}</p>
				<span class="stat-meta">Awaiting grades</span>
			</div>
			<div class="stat-box">
				<p class="stat-label">Average</p>
				<p class="stat-value">{{ $enrolledCourses->filter(fn($e) => $e->grade !== null)->count() > 0 ? round($enrolledCourses->filter(fn($e) => $e->grade !== null)->avg('grade'), 1) : 'N/A' }}</p>
				<span class="stat-meta">Out of 20</span>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
.exams-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.header-section { display: flex; gap: var(--spacing-lg); justify-content: space-between; align-items: flex-end; }
.header-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
.header-actions { display: flex; gap: var(--spacing-md); }

.exams-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--spacing-lg); }
.exam-card { background: var(--bg-dark-secondary); border: 1px solid var(--border-dark); border-radius: var(--radius-lg); padding: var(--spacing-lg); display: flex; flex-direction: column; gap: var(--spacing-md); }
.exam-upcoming { border-left: 4px solid var(--primary); }
.exam-completed { border-left: 4px solid var(--success); opacity: 0.9; }

.card-header-exam { display: flex; justify-content: space-between; align-items: flex-start; gap: var(--spacing-md); padding-bottom: var(--spacing-md); border-bottom: 1px solid var(--border-dark); }
.card-header-exam h4 { color: var(--text-dark); font-weight: 700; }
.course-code { color: var(--text-dark-secondary); font-size: 0.85rem; }
.exam-badge { padding: 6px 12px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.exam-badge.exam-upcoming { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.exam-badge.exam-completed { background: rgba(16, 185, 129, 0.12); color: #10b981; }

.exam-details { display: flex; flex-direction: column; gap: var(--spacing-sm); }
.detail-row { display: grid; grid-template-columns: auto 1fr; gap: var(--spacing-md); }
.detail-icon { font-size: 1.3rem; }
.detail-label { color: var(--text-dark-secondary); font-size: 0.85rem; }
.detail-value { color: var(--text-dark); font-weight: 500; }

.prep-section, .result-section { padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.prep-header, .result-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-sm); }
.prep-label, .result-header span { color: var(--text-dark-secondary); font-size: 0.9rem; }
.prep-percent { color: var(--text-dark); font-weight: 700; }
.grade-badge { padding: 4px 10px; border-radius: 999px; background: var(--primary); color: white; font-weight: 700; }

.prep-bar, .result-bar { height: 6px; background: rgba(255, 255, 255, 0.06); border-radius: 999px; margin-bottom: var(--spacing-sm); overflow: hidden; }
.prep-fill, .result-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--primary-light)); }

.prep-tips { display: flex; gap: var(--spacing-sm); margin-top: var(--spacing-sm); }
.action-link { color: var(--primary-light); text-decoration: none; font-size: 0.9rem; }

.card-footer-exam { display: flex; gap: var(--spacing-sm); }
.btn-small { flex: 1; padding: var(--spacing-sm) var(--spacing-md); font-size: 0.875rem; }

.stats-section { }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--spacing-lg); margin-top: var(--spacing-lg); }
.stat-box { padding: var(--spacing-lg); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); border: 1px solid var(--border-dark); }
.stat-label { color: var(--text-dark-secondary); font-size: 0.9rem; }
.stat-value { font-size: 1.6rem; font-weight: 700; color: var(--text-dark); }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.85rem; }

.chip { padding: 6px 12px; border-radius: 999px; border: 1px solid var(--border-dark); color: var(--text-dark-secondary); font-size: 0.85rem; }

@media (max-width: 960px) {
	.header-section { flex-direction: column; align-items: stretch; }
	.exams-grid { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
