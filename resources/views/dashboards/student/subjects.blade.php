@extends('layouts.be_master')

@section('title', 'My Subjects - Quorum')
@section('page-title', 'My Subjects')

@section('content')
<div class="subjects-page">
    <div class="dashboard-card summary-card">
        <div>
            <p class="stat-label">Current GPA (dummy)</p>
            <p class="stat-value">3.6</p>
            <span class="stat-meta">Based on hardcoded values</span>
        </div>
        <div class="badge badge-success">15.8 / 20</div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Enrolled Subjects</h3>
            <a href="{{ route('dashboard.student.schedule') }}" class="card-link">View schedule →</a>
        </div>

        <div class="subjects-grid">
            @php
                $subjects = [
                    ['title' => 'Web Development', 'code' => 'CS210', 'prof' => 'Laura Mendes', 'grade' => 18, 'progress' => 82, 'color' => '#22c55e'],
                    ['title' => 'Data Structures', 'code' => 'CS220', 'prof' => 'Rui Costa', 'grade' => 17, 'progress' => 76, 'color' => '#0ea5e9'],
                    ['title' => 'Database Systems', 'code' => 'CS330', 'prof' => 'Helena Duarte', 'grade' => 16, 'progress' => 64, 'color' => '#f59e0b'],
                    ['title' => 'Data Ethics', 'code' => 'DS260', 'prof' => 'Andre Sousa', 'grade' => 15, 'progress' => 58, 'color' => '#a855f7'],
                ];
            @endphp

            @foreach($subjects as $subject)
                <div class="subject-card">
                    <div class="card-top">
                        <div>
                            <p class="eyebrow">{{ $subject['code'] }}</p>
                            <h4>{{ $subject['title'] }}</h4>
                        </div>
                        <span class="grade-chip" style="border-color: {{ $subject['color'] }}; color: {{ $subject['color'] }};">{{ $subject['grade'] }}/20</span>
                    </div>
                    <p class="card-sub">Instructor: {{ $subject['prof'] }}</p>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $subject['progress'] }}%; background: {{ $subject['color'] }};"></div>
                    </div>
                    <div class="progress-meta">
                        <span>{{ $subject['progress'] }}% complete</span>
                        <a href="#" class="card-link">Open subject</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="dashboard-grid detail-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Upcoming Assessments</h3>
                <span class="chip">January</span>
            </div>
            <div class="list">
                <div class="list-row">
                    <div>
                        <p class="item-title">Project 2 demo</p>
                        <span class="item-sub">Web Development • Jan 15</span>
                    </div>
                    <span class="badge badge-warning">7 days</span>
                </div>
                <div class="list-row">
                    <div>
                        <p class="item-title">Quiz: Trees & Graphs</p>
                        <span class="item-sub">Data Structures • Jan 12</span>
                    </div>
                    <span class="badge badge-secondary">Prep</span>
                </div>
                <div class="list-row">
                    <div>
                        <p class="item-title">Normalization lab</p>
                        <span class="item-sub">Database Systems • Jan 10</span>
                    </div>
                    <span class="badge badge-success">Ready</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Resources</h3>
                <a href="#" class="card-link">All files →</a>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">📄</div>
                    <div>
                        <p class="item-title">Syllabus pack</p>
                        <span class="item-sub">All subjects • PDF</span>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">🎥</div>
                    <div>
                        <p class="item-title">Lecture recordings</p>
                        <span class="item-sub">Web Dev • Week 1-4</span>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">🧭</div>
                    <div>
                        <p class="item-title">Study roadmap</p>
                        <span class="item-sub">Data Structures</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.subjects-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.summary-card { display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--border-dark); }
.stat-label { color: var(--text-dark-secondary); }
.stat-value { font-size: 2rem; font-weight: 700; color: var(--text-dark); }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.9rem; }

.subjects-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: var(--spacing-md); }
.subject-card { background: var(--bg-dark-secondary); border: 1px solid var(--border-dark); border-radius: var(--radius-lg); padding: var(--spacing-lg); display: flex; flex-direction: column; gap: var(--spacing-md); }
.card-top { display: flex; justify-content: space-between; align-items: center; }
.eyebrow { color: var(--text-dark-secondary); font-size: 0.85rem; letter-spacing: 0.04em; text-transform: uppercase; }
.card-sub { color: var(--text-dark-secondary); }
.grade-chip { padding: 6px 12px; border-radius: 999px; border: 1px solid var(--border-dark); font-weight: 700; }
.progress { width: 100%; height: 8px; background: rgba(255, 255, 255, 0.06); border-radius: var(--radius-md); overflow: hidden; }
.progress-bar { height: 100%; border-radius: var(--radius-md); }
.progress-meta { display: flex; justify-content: space-between; align-items: center; color: var(--text-dark-secondary); font-size: 0.9rem; }

.detail-grid { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
.list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.list-row { display: grid; grid-template-columns: 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.item-title { color: var(--text-dark); font-weight: 600; }
.item-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.resource-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); }
.resource-card { display: grid; grid-template-columns: auto 1fr; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }
.resource-icon { width: 44px; height: 44px; border-radius: var(--radius-md); background: rgba(79, 70, 229, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-secondary { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }

@media (max-width: 960px) {
    .summary-card { flex-direction: column; align-items: flex-start; gap: var(--spacing-sm); }
    .list-row { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
