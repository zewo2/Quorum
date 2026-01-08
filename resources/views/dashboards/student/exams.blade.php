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
					<option>All Exams</option>
					<option>Upcoming</option>
					<option>In Progress</option>
					<option>Completed</option>
				</select>
			</label>
			<label class="field">
				<span>Sort by</span>
				<select>
					<option>Date (Nearest)</option>
					<option>Date (Latest)</option>
					<option>Subject</option>
				</select>
			</label>
		</div>
		<div class="header-actions">
			<button class="btn btn-secondary">Reset</button>
			<button class="btn btn-primary">Add to Calendar</button>
		</div>
	</div>

	<div class="exams-grid">
		@php
			$exams = [
				['title' => 'Final Exam: Web Development', 'code' => 'CS210', 'date' => 'Jan 22, 2026', 'time' => '09:00 - 11:00', 'room' => 'A-101', 'weight' => 35, 'type' => 'upcoming', 'prep' => 92],
				['title' => 'Midterm: Data Structures', 'code' => 'CS220', 'date' => 'Jan 15, 2026', 'time' => '14:00 - 16:00', 'room' => 'B-205', 'weight' => 30, 'type' => 'upcoming', 'prep' => 78],
				['title' => 'Lab Exam: Database Systems', 'code' => 'CS330', 'date' => 'Jan 12, 2026', 'time' => '11:00 - 13:00', 'room' => 'Lab C-302', 'weight' => 40, 'type' => 'upcoming', 'prep' => 85],
				['title' => 'Quiz: Linear Algebra', 'code' => 'MA210', 'date' => 'Jan 10, 2026', 'time' => '10:00 - 11:00', 'room' => 'A-305', 'weight' => 15, 'type' => 'upcoming', 'prep' => 72],
				['title' => 'Practical Exam: Data Ethics', 'code' => 'DS260', 'date' => 'Dec 20, 2025', 'time' => '14:00 - 15:30', 'room' => 'B-101', 'weight' => 25, 'type' => 'completed', 'grade' => 17],
				['title' => 'Midterm: Advanced Programming', 'code' => 'CS360', 'date' => 'Dec 18, 2025', 'time' => '09:00 - 11:00', 'room' => 'A-204', 'weight' => 35, 'type' => 'completed', 'grade' => 18],
			];
		@endphp

		@foreach($exams as $exam)
			<div class="exam-card exam-{{ $exam['type'] }}">
				<div class="card-header-exam">
					<div>
						<h4>{{ $exam['title'] }}</h4>
						<span class="course-code">{{ $exam['code'] }}</span>
					</div>
					<span class="exam-badge exam-{{ $exam['type'] }}">
						@if($exam['type'] === 'upcoming')
							Upcoming
						@else
							Completed
						@endif
					</span>
				</div>

				<div class="exam-details">
					<div class="detail-row">
						<span class="detail-icon">📅</span>
						<div>
							<span class="detail-label">Date</span>
							<p class="detail-value">{{ $exam['date'] }}</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">⏰</span>
						<div>
							<span class="detail-label">Time</span>
							<p class="detail-value">{{ $exam['time'] }}</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">📍</span>
						<div>
							<span class="detail-label">Location</span>
							<p class="detail-value">{{ $exam['room'] }}</p>
						</div>
					</div>
					<div class="detail-row">
						<span class="detail-icon">⚖️</span>
						<div>
							<span class="detail-label">Weight</span>
							<p class="detail-value">{{ $exam['weight'] }}% of grade</p>
						</div>
					</div>
				</div>

				@if($exam['type'] === 'upcoming')
					<div class="prep-section">
						<div class="prep-header">
							<span class="prep-label">Preparation</span>
							<span class="prep-percent">{{ $exam['prep'] }}%</span>
						</div>
						<div class="prep-bar">
							<div class="prep-fill" style="width: {{ $exam['prep'] }}%;"></div>
						</div>
						<div class="prep-tips">
							<a href="{{ route('dashboard.student.schedule') }}" class="action-link">Review schedule →</a>
							<a href="#" class="action-link">Study materials →</a>
						</div>
					</div>
				@else
					<div class="result-section">
						<div class="result-header">
							<span>Result</span>
							<span class="grade-badge">{{ $exam['grade'] }}/20</span>
						</div>
						<div class="result-bar">
							<div class="result-fill" style="width: {{ ($exam['grade'] / 20) * 100 }}%;"></div>
						</div>
					</div>
				@endif

				<div class="card-footer-exam">
					<button class="btn btn-secondary btn-small">Details</button>
					@if($exam['type'] === 'upcoming')
						<button class="btn btn-primary btn-small">View Materials</button>
					@else
						<button class="btn btn-secondary btn-small">View Results</button>
					@endif
				</div>
			</div>
		@endforeach
	</div>

	<div class="dashboard-card stats-section">
		<div class="card-header">
			<h3>Exam Statistics</h3>
			<span class="chip">All Exams</span>
		</div>

		<div class="stats-grid">
			<div class="stat-box">
				<p class="stat-label">Total Exams</p>
				<p class="stat-value">6</p>
				<span class="stat-meta">Completed & Upcoming</span>
			</div>
			<div class="stat-box">
				<p class="stat-label">Average Grade</p>
				<p class="stat-value">17.3</p>
				<span class="stat-meta">Out of 20</span>
			</div>
			<div class="stat-box">
				<p class="stat-label">Completed</p>
				<p class="stat-value">2</p>
				<span class="stat-meta">Average: 17.5</span>
			</div>
			<div class="stat-box">
				<p class="stat-label">Upcoming</p>
				<p class="stat-value">4</p>
				<span class="stat-meta">Starts in 4 days</span>
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
