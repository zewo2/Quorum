@extends('layouts.be_master')

@section('title', 'Exam Scheduling - Quorum')
@section('page-title', 'Exam Scheduling')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Exam Scheduling</h1>
            <p>Manage exam dates and times</p>
        </div>
        <a href="{{ route('dashboard.admin.exams.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Exam
        </a>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Scheduled Exams</h3>
            <span class="chip">{{ $exams->total() }} entries</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td><strong>{{ $exam->subject?->name ?? 'N/A' }}</strong></td>
                            <td>{{ $exam->subject?->course?->name ?? 'N/A' }}</td>
                            <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge">
                                    {{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}
                                </span>
                            </td>
                            <td>{{ $exam->room ?? 'TBA' }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('dashboard.admin.exams.edit', $exam) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('dashboard.admin.exams.destroy', $exam) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this exam?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-dark-secondary);">
                                No exams scheduled yet. <a href="{{ route('dashboard.admin.exams.create') }}">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exams->hasPages())
            <div class="pagination-wrapper">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
