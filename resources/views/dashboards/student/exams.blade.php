@extends('layouts.be_master')

@section('title', 'Exams - Quorum')
@section('content')
<div class="exams-page">
    <!-- Header and Stats -->
    <div class="header-section">
        <div class="header-left">
            <div>
                <h2 style="color: var(--text-dark); margin-bottom: var(--spacing-md);">Exams</h2>
                <p style="color: var(--text-dark-secondary); font-size: 0.9rem;">Track and manage your exam schedules</p>
            </div>
        </div>
    </div>

    <!-- Upcoming Exams Card -->
    @if($upcomingExams->isNotEmpty())
    <div class="upcoming-exams-card">
        <div class="card-header-main">
            <div>
                <h3 style="color: var(--text-dark); margin: 0;">Upcoming Exams</h3>
                <p style="color: var(--text-dark-secondary); font-size: 0.85rem; margin: var(--spacing-xs) 0 0 0;">{{ $upcomingExams->count() }} exam{{ $upcomingExams->count() != 1 ? 's' : '' }} scheduled</p>
            </div>
        </div>
        <div class="exams-list">
            @foreach($upcomingExams as $exam)
            <div class="exam-row">
                <div class="exam-info">
                    <div class="exam-subject">{{ $exam->subject->name }}</div>
                    <div class="exam-meta">
                        📅 {{ $exam->exam_date->format('M d, Y') }} at {{ $exam->start_time->format('H:i') }}
                        @if($exam->room)
                        • 📍 {{ $exam->room }}
                        @endif
                    </div>
                </div>
                <div class="exam-status">
                    @php
                        $examStart = \Carbon\Carbon::make("{$exam->exam_date} {$exam->start_time}");
                        $daysUntil = now()->diffInDays($examStart, false);
                    @endphp
                    @if($daysUntil > 7)
                        <span class="status-badge badge-upcoming">Scheduled</span>
                    @elseif($daysUntil > 0)
                        <span class="status-badge badge-soon">{{ $daysUntil }} day{{ $daysUntil != 1 ? 's' : '' }} away</span>
                    @else
                        <span class="status-badge badge-today">Today/This Week</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="upcoming-exams-card empty-state">
        <div style="text-align: center; padding: var(--spacing-xl) var(--spacing-lg);">
            <div style="font-size: 2rem; margin-bottom: var(--spacing-md);">📚</div>
            <h4 style="color: var(--text-dark); margin-bottom: var(--spacing-sm);">No Upcoming Exams</h4>
            <p style="color: var(--text-dark-secondary); font-size: 0.9rem;">You have no exams scheduled at the moment</p>
        </div>
    </div>
    @endif

    <!-- Subjects Section -->
    <div class="subjects-section">
        <div style="margin-bottom: var(--spacing-lg);">
            <h3 style="color: var(--text-dark); margin: 0 0 var(--spacing-sm) 0;">Enrolled Subjects</h3>
            <p style="color: var(--text-dark-secondary); font-size: 0.9rem;">{{ $enrolledSubjects->count() }} subject{{ $enrolledSubjects->count() != 1 ? 's' : '' }}</p>
        </div>

        @if($enrolledSubjects->isNotEmpty())
        <div class="subjects-grid">
            @foreach($enrolledSubjects as $subject)
            <div class="subject-exam-card">
                <div class="subject-header">
                    <h4 style="margin: 0;">{{ $subject->name }}</h4>
                    @if($subject->code)
                    <span class="subject-code">{{ $subject->code }}</span>
                    @endif
                </div>

                @if($subject->exams->isNotEmpty())
                <div class="subject-exams-list">
                    @foreach($subject->exams as $exam)
                    <div class="subject-exam-item">
                        <div style="flex: 1;">
                            <div class="exam-date">{{ $exam->exam_date->format('M d, Y') }}</div>
                            <div class="exam-time">{{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}</div>
                            @if($exam->room)
                            <div class="exam-room">Room: {{ $exam->room }}</div>
                            @endif
                        </div>
                        @php
                            $examStart = \Carbon\Carbon::make("{$exam->exam_date} {$exam->start_time}");
                            $isPast = $examStart < now();
                        @endphp
                        <span class="exam-status-badge {{ $isPast ? 'badge-past' : 'badge-future' }}">
                            {{ $isPast ? 'Past' : 'Upcoming' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="no-exams">
                    <p style="color: var(--text-dark-secondary); font-size: 0.9rem; margin: 0;">No exams scheduled</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: var(--spacing-xl) var(--spacing-lg); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-lg); border: 1px solid var(--border-dark);">
            <p style="color: var(--text-dark-secondary); margin: 0;">No enrolled subjects found</p>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.exams-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.header-section {
    display: flex;
    gap: var(--spacing-lg);
    align-items: flex-start;
}

.header-left {
    flex: 1;
}

/* Upcoming Exams Card */
.upcoming-exams-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    overflow: hidden;
}

.upcoming-exams-card.empty-state {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

.card-header-main {
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border-dark);
}

.card-header-main h3 {
    margin: 0;
    color: var(--text-dark);
}

.exams-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.exam-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.02);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-dark);
    transition: background 0.2s;
}

.exam-row:hover {
    background: rgba(255, 255, 255, 0.04);
}

.exam-info {
    flex: 1;
}

.exam-subject {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: var(--spacing-xs);
}

.exam-meta {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.exam-status {
    margin-left: var(--spacing-md);
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge-upcoming {
    background: rgba(59, 130, 246, 0.12);
    color: #3b82f6;
}

.badge-soon {
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
}

.badge-today {
    background: rgba(239, 68, 68, 0.12);
    color: #ef4444;
}

/* Subjects Section */
.subjects-section {
    margin-top: var(--spacing-lg);
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-lg);
}

.subject-exam-card {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.subject-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border-dark);
}

.subject-header h4 {
    color: var(--text-dark);
    font-weight: 700;
    margin: 0;
}

.subject-code {
    color: var(--text-dark-secondary);
    font-size: 0.8rem;
    background: rgba(255, 255, 255, 0.05);
    padding: 3px 8px;
    border-radius: var(--radius-sm);
    white-space: nowrap;
}

.subject-exams-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.subject-exam-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--border-dark);
}

.subject-exam-item:last-child {
    border-bottom: none;
}

.exam-date {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.95rem;
}

.exam-time {
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
    margin-top: 2px;
}

.exam-room {
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
    margin-top: 2px;
}

.exam-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge-future {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
}

.badge-past {
    background: rgba(107, 114, 128, 0.12);
    color: #9ca3af;
}

.no-exams {
    padding: var(--spacing-md) 0;
    text-align: center;
}

@media (max-width: 960px) {
    .subjects-grid {
        grid-template-columns: 1fr;
    }

    .exam-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .exam-status {
        margin-left: 0;
        margin-top: var(--spacing-md);
    }
}
</style>
@endpush
@endsection
