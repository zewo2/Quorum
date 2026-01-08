@extends('layouts.debug')

@section('content')
    <h1>Attendance</h1>
    <p>Placeholder for teacher attendance tracking. Record and manage student attendance here.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.teacher.index') }}">Back to dashboard</a>
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn ghost" href="{{ route('dashboard.teacher.classes') }}">My classes</a>
    </div>
    <div class="pill">Route: dashboard.teacher.attendance</div>
@endsection
