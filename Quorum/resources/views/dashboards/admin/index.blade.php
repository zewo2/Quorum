@extends('layouts.debug')

@section('content')
    <h1>Admin dashboard</h1>
    <p>Placeholder admin view. Swap this out with real widgets once the backend is ready.</p>
    <div class="actions">
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn" href="{{ route('dashboard.teacher.index') }}">Teacher dashboard</a>
        <a class="btn" href="{{ route('dashboard.student.index') }}">Student dashboard</a>
        <a class="btn ghost" href="{{ route('auth.register') }}">Register user</a>
    </div>
    <div class="pill">Route: dashboard.admin.index</div>
@endsection
