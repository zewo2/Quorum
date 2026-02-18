@extends('layouts.be_master')

@section('title', 'My Subjects - Quorum')
@section('page-title', 'My Subjects')

@section('content')
<div class="subjects-page">
    <div class="dashboard-card summary-card">
        <div>
            <p class="stat-label">Current GPA</p>
            <p class="stat-value">{{ $gpa }}</p>
            <span class="stat-meta">Based on graded enrollments</span>
        </div>
        <div class="summary-course">
            <p class="stat-label">Current Enrolled Course</p>
            @if($currentCourse)
                <p class="item-title">{{ $currentCourse->name }}</p>
                <span class="item-sub">{{ $currentCourse->code ?? 'N/A' }} • {{ $currentCourse->department }} • {{ $currentCourse->total_years }} years</span>
            @else
                <p class="item-title">No active enrollment</p>
                <span class="item-sub">Enroll in a course to see details</span>
            @endif
        </div>
        <div class="badge badge-success">{{ $averageGrade }} / 20</div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Enrolled Subjects</h3>
            <a href="{{ route('dashboard.student.schedule') }}" class="card-link">View schedule →</a>
        </div>

        <div class="subjects-grid">
            @forelse($enrolledSubjects as $subject)
                <div class="subject-card">
                    <div class="card-top">
                        <div>
                            <p class="eyebrow">{{ $subject->code ?? 'N/A' }}</p>
                            <h4>{{ $subject->name }}</h4>
                        </div>
                        <span class="grade-chip" style="border-color: #0ea5e9; color: #0ea5e9;">Y{{ $subject->year }} • S{{ $subject->semester }}</span>
                    </div>
                    <p class="card-sub">{{ \Illuminate\Support\Str::limit($subject->description ?? 'No description available.', 90) }}</p>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%; background: {{ ($subject->status ?? 'active') === 'active' ? '#0ea5e9' : '#9ca3af' }};"></div>
                    </div>
                    <div class="progress-meta">
                        <span>{{ ucfirst($subject->status ?? 'active') }}</span>
                        <span style="color: var(--text-dark-secondary);">{{ $subject->credits }} ECTS • Year {{ $subject->year }} • Semester {{ $subject->semester }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: var(--spacing-lg); text-align: center; color: var(--text-dark-secondary);">
                    No enrolled subjects
                </div>
            @endforelse
        </div>
    </div>

    <div class="dashboard-grid detail-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Subject Info</h3>
                <span class="chip">{{ $enrolledSubjects->count() }} subjects</span>
            </div>
            <div class="list subject-info-list">
                @forelse($enrolledSubjects as $subject)
                <div class="list-row">
                    <div>
                        <p class="item-title">{{ $subject->name }}</p>
                        <span class="item-sub">{{ $subject->credits }} ECTS • Year {{ $subject->year }} • Semester {{ $subject->semester }}</span>
                    </div>
                    @if(($subject->status ?? 'active') === 'active')
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">{{ ucfirst($subject->status ?? 'inactive') }}</span>
                    @endif
                </div>
                @empty
                <div style="padding: var(--spacing-md); color: var(--text-dark-secondary); text-align: center;">
                    No enrolled subjects
                </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card" style="margin-top: 0.6cm">
            <div class="card-header">
                <h3>Academic Summary</h3>
                <a href="{{ route('dashboard.student.grades') }}" class="card-link">View details →</a>
            </div>
            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-icon">📚</div>
                    <div>
                        <p class="item-title">{{ $enrolledSubjects->count() }}</p>
                        <span class="item-sub">Enrolled subjects</span>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">⭐</div>
                    <div>
                        <p class="item-title">{{ $gpa }}</p>
                        <span class="item-sub">Current GPA</span>
                    </div>
                </div>
                <div class="resource-card">
                    <div class="resource-icon">📊</div>
                    <div>
                        <p class="item-title">{{ $averageGrade }}</p>
                        <span class="item-sub">Average grade</span>
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
.summary-course { min-width: 300px; }
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
.subject-info-list { max-height: 340px; overflow-y: auto; padding-right: 4px; }
.subject-info-list::-webkit-scrollbar { width: 8px; }
.subject-info-list::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.04); border-radius: 999px; }
.subject-info-list::-webkit-scrollbar-thumb { background: var(--border-dark); border-radius: 999px; }
.subject-info-list::-webkit-scrollbar-thumb:hover { background: var(--primary); }
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
