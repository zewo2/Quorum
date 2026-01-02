@extends('layouts.debug')

@section('content')
    <h1>Student dashboard</h1>
    <p>Placeholder student view to validate routing and layout hooks.</p>
    <div class="actions">
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn" href="{{ route('dashboard.teacher.index') }}">Teacher</a>
        <a class="btn" href="{{ route('dashboard.admin.index') }}">Admin</a>
        <a class="btn secondary" href="{{ route('auth.login') }}">Login</a>
    </div>
    <div class="pill">Route: dashboard.student.index</div>
@endsection
