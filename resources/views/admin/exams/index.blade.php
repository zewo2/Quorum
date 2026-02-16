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

    <livewire:admin.exam-filters />
</div>
@endsection
