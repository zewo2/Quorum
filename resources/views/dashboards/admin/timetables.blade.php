@extends('layouts.be_master')

@section('title', 'Timetables - Quorum')
@section('page-title', 'Timetables Management')

@section('content')
<div class="timetables-page">
	<div class="dashboard-card filters-card">
		<div class="filters-left">
			<label class="field">
				<span>Year</span>
				<select>
					<option>All Years</option>
					<option>Year 1</option>
					<option>Year 2</option>
					<option>Year 3</option>
				</select>
			</label>
			<label class="field">
				<span>Semester</span>
				<select>
					<option>Spring 2026</option>
					<option>Fall 2025</option>
				</select>
			</label>
			<label class="field">
				<span>Status</span>
				<select>
					<option>All</option>
					<option>Active</option>
					<option>Draft</option>
					<option>Optimized</option>
				</select>
			</label>
		</div>
		<div class="filters-actions">
			<button class="btn btn-secondary">Reset</button>
			<button class="btn btn-primary">Generate with AI</button>
		</div>
	</div>

	<div class="dashboard-grid summary-grid">
		<div class="dashboard-card stat-summary">
			<div class="stat-content">
				<p class="stat-label">Active Timetables</p>
				<p class="stat-value">12</p>
				<span class="stat-meta">All departments</span>
			</div>
		</div>
		<div class="dashboard-card stat-summary">
			<div class="stat-content">
				<p class="stat-label">Total Sessions</p>
				<p class="stat-value">342</p>
				<span class="stat-meta">Scheduled this semester</span>
			</div>
		</div>
		<div class="dashboard-card stat-summary">
			<div class="stat-content">
				<p class="stat-label">Room Conflicts</p>
				<p class="stat-value">0</p>
				<span class="stat-meta">After last optimization</span>
			</div>
		</div>
		<div class="dashboard-card stat-summary">
			<div class="stat-content">
				<p class="stat-label">Optimization Score</p>
				<p class="stat-value">94%</p>
				<span class="stat-meta">Genetic algorithm result</span>
			</div>
		</div>
	</div>

	<div class="dashboard-card table-section">
		<div class="card-header">
			<h3>Timetable Schedules</h3>
			<span class="chip">24 total records</span>
		</div>

		<div class="table-wrapper">
			<table class="data-table">
				<thead>
					<tr>
						<th>Department</th>
						<th>Year</th>
						<th>Status</th>
						<th>Total Sessions</th>
						<th>Rooms Used</th>
						<th>Optimization</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<div class="dept-cell">
								<div class="dept-dot" style="background: #4f46e5;"></div>
								<div>
									<p class="item-title">Computer Science</p>
									<span class="item-sub">5 programs</span>
								</div>
							</div>
						</td>
						<td>Year 2</td>
						<td><span class="badge badge-success">Active</span></td>
						<td>128</td>
						<td>42 labs, 18 classrooms</td>
						<td>
							<div class="score-bar">
								<div class="score-fill" style="width: 96%; background: #22c55e;"></div>
							</div>
							<span class="score-text">96%</span>
						</td>
						<td class="row-actions">
							<button class="icon-btn">👁</button>
							<button class="icon-btn">✏️</button>
							<button class="icon-btn">⬇️</button>
						</td>
					</tr>
					<tr>
						<td>
							<div class="dept-cell">
								<div class="dept-dot" style="background: #10b981;"></div>
								<div>
									<p class="item-title">Business & Economics</p>
									<span class="item-sub">4 programs</span>
								</div>
							</div>
						</td>
						<td>Year 3</td>
						<td><span class="badge badge-success">Active</span></td>
						<td>104</td>
						<td>24 classrooms</td>
						<td>
							<div class="score-bar">
								<div class="score-fill" style="width: 92%; background: #f59e0b;"></div>
							</div>
							<span class="score-text">92%</span>
						</td>
						<td class="row-actions">
							<button class="icon-btn">👁</button>
							<button class="icon-btn">✏️</button>
							<button class="icon-btn">⬇️</button>
						</td>
					</tr>
					<tr>
						<td>
							<div class="dept-cell">
								<div class="dept-dot" style="background: #0ea5e9;"></div>
								<div>
									<p class="item-title">Engineering</p>
									<span class="item-sub">3 programs</span>
								</div>
							</div>
						</td>
						<td>Year 1</td>
						<td><span class="badge badge-warning">Draft</span></td>
						<td>78</td>
						<td>32 labs</td>
						<td>
							<div class="score-bar">
								<div class="score-fill" style="width: 87%; background: #0ea5e9;"></div>
							</div>
							<span class="score-text">87%</span>
						</td>
						<td class="row-actions">
							<button class="icon-btn">👁</button>
							<button class="icon-btn">✏️</button>
							<button class="icon-btn">⬇️</button>
						</td>
					</tr>
					<tr>
						<td>
							<div class="dept-cell">
								<div class="dept-dot" style="background: #a855f7;"></div>
								<div>
									<p class="item-title">Sciences</p>
									<span class="item-sub">3 programs</span>
								</div>
							</div>
						</td>
						<td>Year 2</td>
						<td><span class="badge badge-success">Active</span></td>
						<td>98</td>
						<td>28 labs, 12 classrooms</td>
						<td>
							<div class="score-bar">
								<div class="score-fill" style="width: 95%; background: #22c55e;"></div>
							</div>
							<span class="score-text">95%</span>
						</td>
						<td class="row-actions">
							<button class="icon-btn">👁</button>
							<button class="icon-btn">✏️</button>
							<button class="icon-btn">⬇️</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="dashboard-grid info-grid">
		<div class="dashboard-card">
			<div class="card-header">
				<h3>Latest Optimization</h3>
				<span class="chip">Completed</span>
			</div>
			<div class="optimization-details">
				<div class="opt-row">
					<span>Run Date</span>
					<strong>Jan 5, 2026 • 14:32</strong>
				</div>
				<div class="opt-row">
					<span>Algorithm</span>
					<strong>Genetic Algorithm v3.2</strong>
				</div>
				<div class="opt-row">
					<span>Generations</span>
					<strong>500 iterations</strong>
				</div>
				<div class="opt-row">
					<span>Time Taken</span>
					<strong>12 minutes 34 seconds</strong>
				</div>
				<div class="opt-row">
					<span>Improvements</span>
					<strong>23 conflicts resolved</strong>
				</div>
				<button class="btn btn-primary btn-block" style="margin-top: var(--spacing-lg);">Run Optimization Again</button>
			</div>
		</div>

		<div class="dashboard-card">
			<div class="card-header">
				<h3>Room Utilization</h3>
				<span class="chip">This Semester</span>
			</div>
			<div class="utilization-chart">
				<div class="util-row">
					<span class="util-name">Classrooms</span>
					<div class="util-bar">
						<div class="util-fill" style="width: 72%;"></div>
					</div>
					<span class="util-percent">72%</span>
				</div>
				<div class="util-row">
					<span class="util-name">Labs</span>
					<div class="util-bar">
						<div class="util-fill" style="width: 88%;"></div>
					</div>
					<span class="util-percent">88%</span>
				</div>
				<div class="util-row">
					<span class="util-name">Lecture Halls</span>
					<div class="util-bar">
						<div class="util-fill" style="width: 62%;"></div>
					</div>
					<span class="util-percent">62%</span>
				</div>
				<div class="util-row">
					<span class="util-name">Studio Spaces</span>
					<div class="util-bar">
						<div class="util-fill" style="width: 45%;"></div>
					</div>
					<span class="util-percent">45%</span>
				</div>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
.timetables-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }

.filters-card { display: flex; gap: var(--spacing-lg); justify-content: space-between; align-items: flex-end; }
.filters-left { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--spacing-md); flex: 1; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); }
.filters-actions { display: flex; gap: var(--spacing-md); }

.summary-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
.stat-summary { border: 1px solid var(--border-dark); }
.stat-label { color: var(--text-dark-secondary); font-size: 0.9rem; }
.stat-value { font-size: 1.8rem; font-weight: 700; color: var(--text-dark); }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.85rem; }

.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; margin-top: var(--spacing-md); }
.data-table th, .data-table td { padding: var(--spacing-md); text-align: left; border-bottom: 1px solid var(--border-dark); }
.data-table th { background: rgba(255, 255, 255, 0.03); color: var(--text-dark); font-weight: 600; }

.dept-cell { display: flex; align-items: center; gap: var(--spacing-md); }
.dept-dot { width: 12px; height: 12px; border-radius: 50%; }
.item-title { color: var(--text-dark); font-weight: 600; }
.item-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }

.score-bar { width: 100%; height: 6px; background: rgba(255, 255, 255, 0.06); border-radius: 999px; margin-bottom: 4px; overflow: hidden; }
.score-fill { height: 100%; }
.score-text { color: var(--text-dark-secondary); font-size: 0.8rem; font-weight: 700; }

.row-actions { display: flex; gap: var(--spacing-sm); }
.icon-btn { width: 34px; height: 34px; border: 1px solid var(--border-dark); background: rgba(255, 255, 255, 0.04); border-radius: var(--radius-md); cursor: pointer; }

.chip { padding: 6px 12px; border-radius: 999px; border: 1px solid var(--border-dark); color: var(--text-dark-secondary); font-size: 0.85rem; background: rgba(255, 255, 255, 0.04); }

.info-grid { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }

.optimization-details { display: flex; flex-direction: column; gap: var(--spacing-md); }
.opt-row { display: flex; justify-content: space-between; align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.opt-row span { color: var(--text-dark-secondary); }
.opt-row strong { color: var(--text-dark); }

.utilization-chart { display: flex; flex-direction: column; gap: var(--spacing-md); }
.util-row { display: grid; grid-template-columns: 100px 1fr 50px; gap: var(--spacing-md); align-items: center; }
.util-name { color: var(--text-dark-secondary); }
.util-bar { height: 8px; background: rgba(255, 255, 255, 0.06); border-radius: 999px; overflow: hidden; }
.util-fill { background: linear-gradient(90deg, var(--primary), var(--primary-light)); height: 100%; }
.util-percent { color: var(--text-dark); font-weight: 700; text-align: right; }

.btn-block { width: 100%; }

@media (max-width: 960px) {
	.filters-card { flex-direction: column; align-items: stretch; }
	.row-actions { justify-content: center; }
}
</style>
@endpush
@endsection
