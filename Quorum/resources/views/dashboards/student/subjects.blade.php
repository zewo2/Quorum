@extends('layouts.debug')

@section('content')
    <h1>My Subjects</h1>
    <p>Placeholder for student subjects view. Display enrolled courses and grades here.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.student.index') }}">Back to dashboard</a>
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn ghost" href="{{ route('dashboard.student.schedule') }}">My schedule</a>
    </div>
    <div class="pill">Route: dashboard.student.subjects</div>
@endsection
