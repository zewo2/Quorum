@extends('layouts.be_master')

@section('title', 'Timetable Management - Quorum')
@section('page-title', 'Timetable Management')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('ga_apply_breakdown'))
        @php($report = session('ga_apply_breakdown'))
        <div class="dashboard-card" style="padding: 1rem; margin-top: -0.5rem;">
            <div class="card-header" style="margin-bottom: 0.75rem; padding-bottom: 0.75rem;">
                <h3 style="font-size: 1rem;">GA Apply Breakdown</h3>
            </div>
            <div style="display: grid; gap: 0.5rem; color: var(--text-dark-secondary); font-size: 0.9rem;">
                <div><strong>Created:</strong> {{ $report['created'] ?? 0 }}</div>
                <div><strong>Skipped:</strong> {{ $report['skipped_total'] ?? 0 }} (missing class: {{ $report['skipped_missing_class'] ?? 0 }}, no room: {{ $report['skipped_no_room'] ?? 0 }})</div>
                <div><strong>Conflicts:</strong> {{ $report['conflicts_total'] ?? 0 }} (room: {{ $report['conflicts_room'] ?? 0 }}, teacher: {{ $report['conflicts_teacher'] ?? 0 }}, course: {{ $report['conflicts_course'] ?? 0 }})</div>
                <div><strong>Over hour limit:</strong> {{ $report['over_legal_hour_limit'] ?? 0 }}</div>
            </div>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Timetable Management</h1>
            <p>Manage class schedules and time slots</p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; position: relative; z-index: 2;">
            <a href="{{ route('dashboard.admin.timetables.ga') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6"></path>
                    <path d="m15.88 8.12 4.24-4.24m0 16.24-4.24-4.24M8.12 8.12 3.88 3.88m0 16.24 4.24-4.24"></path>
                </svg>
                AI Scheduler
            </a>
            <a href="{{ route('dashboard.admin.timetables.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Schedule
            </a>
        </div>
    </div>

    <livewire:admin.timetable-filters />

@endsection
