@extends('layouts.be_master')

@section('title', 'My Assignments - Quorum')
@section('page-title', 'My Assignments')

@section('content')
<div class="assignments-page">
    <div class="dashboard-card summary-card">
        <div class="summary-item">
            <p class="stat-label">Tracked Assignments</p>
            <p class="stat-value">{{ $totalAssignments }}</p>
            <span class="stat-meta">Assignment module pending</span>
        </div>
        <div class="summary-item">
            <p class="stat-label">Pending</p>
            <p class="stat-value">{{ $pendingAssignments }}</p>
            <span class="stat-meta">To be submitted</span>
        </div>
        <div class="summary-item">
            <p class="stat-label">Active Subjects</p>
            <p class="stat-value">{{ $activeSubjectsCount }}</p>
            <span class="stat-meta">Enrolled and active</span>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Subject Assignment Status</h3>
            <a href="{{ route('dashboard.student.subjects') }}" class="card-link">View subjects →</a>
        </div>

        <div class="assignments-grid">
            @forelse($enrolledSubjects as $subject)
                <div class="assignment-card">
                    <div class="assignment-top">
                        <div>
                            <p class="eyebrow">{{ $subject->code ?? 'N/A' }}</p>
                            <h4>{{ $subject->name }}</h4>
                        </div>
                        <span class="status-chip {{ ($subject->status ?? 'active') === 'active' ? 'status-active' : 'status-muted' }}">
                            {{ ucfirst($subject->status ?? 'active') }}
                        </span>
                    </div>
                    <p class="card-sub">{{ $subject->credits }} ECTS • Year {{ $subject->year }} • Semester {{ $subject->semester }}</p>

                    <div class="placeholder-box">
                        <p class="placeholder-title">No assignment records yet</p>
                        <span class="placeholder-sub">This subject has no assignment data in the system at the moment.</span>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('dashboard.student.exams') }}" class="btn btn-secondary btn-small">Check Exams</a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    No enrolled subjects available.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.assignments-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.summary-card { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--spacing-md); border: 1px solid var(--border-dark); }
.summary-item { padding: var(--spacing-md); background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-dark); border-radius: var(--radius-md); }
.stat-label { color: var(--text-dark-secondary); }
.stat-value { font-size: 1.8rem; font-weight: 700; color: var(--text-dark); }
.stat-meta { color: var(--text-dark-secondary); font-size: 0.9rem; }

.assignments-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--spacing-md); }
.assignment-card { background: var(--bg-dark-secondary); border: 1px solid var(--border-dark); border-radius: var(--radius-lg); padding: var(--spacing-lg); display: flex; flex-direction: column; gap: var(--spacing-md); }
.assignment-top { display: flex; justify-content: space-between; align-items: center; gap: var(--spacing-md); }
.eyebrow { color: var(--text-dark-secondary); font-size: 0.85rem; letter-spacing: 0.04em; text-transform: uppercase; }
.card-sub { color: var(--text-dark-secondary); }

.status-chip { padding: 6px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; border: 1px solid transparent; }
.status-active { background: rgba(16, 185, 129, 0.12); color: #10b981; border-color: rgba(16, 185, 129, 0.24); }
.status-muted { background: rgba(148, 163, 184, 0.12); color: #94a3b8; border-color: rgba(148, 163, 184, 0.24); }

.placeholder-box { padding: var(--spacing-md); border-radius: var(--radius-md); background: rgba(79, 70, 229, 0.08); border: 1px dashed rgba(99, 102, 241, 0.35); }
.placeholder-title { color: var(--text-dark); font-weight: 600; }
.placeholder-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.card-actions { display: flex; justify-content: flex-end; }
.btn-small { padding: var(--spacing-sm) var(--spacing-md); font-size: 0.875rem; }

.empty-state { grid-column: 1 / -1; padding: var(--spacing-lg); text-align: center; color: var(--text-dark-secondary); border: 1px solid var(--border-dark); border-radius: var(--radius-md); }
</style>
@endpush
