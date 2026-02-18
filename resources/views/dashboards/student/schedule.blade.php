@extends('layouts.be_master')

@section('title', 'My Schedule - Quorum')
@section('page-title', 'My Schedule')

@section('content')
<livewire:student.schedule-filters />
@endsection

@push('styles')
<style>
.schedule-page { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.filters-card { padding: var(--spacing-md); }
.filters-form { display: grid; grid-template-columns: 1fr auto; gap: var(--spacing-md); align-items: end; }
.filters-left { display: grid; grid-template-columns: repeat(2, minmax(180px, 1fr)) auto; gap: var(--spacing-md); align-items: end; }
.field { display: flex; flex-direction: column; gap: 6px; color: var(--text-dark-secondary); }
.field select { padding: var(--spacing-sm) var(--spacing-md); background: var(--bg-dark); border: 1px solid var(--border-dark); border-radius: var(--radius-md); color: var(--text-dark); color-scheme: dark; }
.field select option { background: var(--bg-dark); color: var(--text-dark); padding: 8px; }
.field select option:checked { background: var(--primary); color: white; }
.filters-actions { display: flex; gap: var(--spacing-sm); }
.quick-links { justify-self: end; }

.week-table { display: grid; gap: var(--spacing-sm); }
.week-row { display: grid; grid-template-columns: 2fr repeat(5, 1fr); gap: var(--spacing-sm); align-items: center; padding: var(--spacing-sm) var(--spacing-md); border: 1px solid var(--border-dark); border-radius: var(--radius-md); }
.week-head { font-weight: 700; color: var(--text-dark-secondary); background: rgba(255,255,255,0.02); }
.slot { color: var(--text-dark); font-weight: 600; }
.cell { color: var(--text-dark-secondary); }

.detail-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.timeline { display: flex; flex-direction: column; gap: var(--spacing-md); }
.timeline-row { display: grid; grid-template-columns: auto 1fr auto; gap: var(--spacing-md); align-items: center; }
.timeline-dot { width: 14px; height: 14px; border-radius: 50%; border: 3px solid var(--border-dark); }
.item-title { color: var(--text-dark); font-weight: 600; }
.item-sub { color: var(--text-dark-secondary); font-size: 0.9rem; }

.tasks-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.task-item { display: grid; grid-template-columns: 1fr auto; gap: var(--spacing-md); align-items: center; padding: var(--spacing-md); background: rgba(255, 255, 255, 0.03); border-radius: var(--radius-md); }

.badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-secondary { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }

.schedule-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
}

.schedule-day-column {
    display: flex;
    flex-direction: column;
}

.day-header {
    font-weight: 600;
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--primary);
    margin-bottom: var(--spacing-md);
    color: var(--text-dark);
    font-size: 0.95rem;
}

.schedule-slots {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.schedule-compact .schedule-slot {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: 0.8rem;
}

.schedule-compact .slot-time {
    font-size: 0.75rem;
}

.schedule-compact .slot-subject {
    font-size: 0.8rem;
}

.schedule-slot {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.10), rgba(99, 102, 241, 0.06));
    border-left: 3px solid var(--primary);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-sm);
    transition: all 0.2s ease;
}

.schedule-slot:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateX(2px);
}

.slot-time {
    font-weight: 600;
    color: var(--primary-light);
    font-size: 0.8rem;
    margin-bottom: var(--spacing-xs);
}

.slot-duration {
    font-weight: normal;
    color: var(--text-dark-secondary);
    font-size: 0.7rem;
}

.slot-details {
    margin-top: var(--spacing-xs);
}

.slot-subject {
    margin: 0;
    font-weight: 500;
    font-size: 0.85rem;
    color: var(--text-dark);
}

.slot-teacher {
    margin: var(--spacing-xs) 0 0;
    font-size: 0.75rem;
    color: var(--text-dark-secondary);
}

.slot-info {
    margin: var(--spacing-xs) 0 0;
    color: var(--text-dark-secondary);
    font-size: 0.7rem;
}

.no-classes {
    color: var(--text-dark-secondary);
    font-size: 0.8rem;
    text-align: center;
    padding: var(--spacing-md);
    margin: 0;
}

@media (max-width: 960px) {
    .filters-form { grid-template-columns: 1fr; }
    .filters-left { grid-template-columns: 1fr; }
    .quick-links { justify-content: flex-start; }
    .week-row { grid-template-columns: repeat(2, 1fr); }
    .slot { grid-column: 1 / -1; }
    .timeline-row, .task-item { grid-template-columns: 1fr; }
}
</style>
@endpush
