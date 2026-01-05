@extends('layouts.debug')

@section('content')
    <h1>Student dashboard</h1>
    <p>Placeholder student view to validate routing and layout hooks.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.student.schedule') }}">My schedule</a>
        <a class="btn" href="{{ route('dashboard.student.subjects') }}">My subjects</a>
        <a class="btn ghost" href="{{ route('home') }}">Home</a>
    </div>
    <div class="pill">Route: dashboard.student.index</div>
@endsection
