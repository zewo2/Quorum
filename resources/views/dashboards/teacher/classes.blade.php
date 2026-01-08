@extends('layouts.debug')

@section('content')
    <h1>My Classes</h1>
    <p>Placeholder for teacher classes view. Show taught courses and roster here.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.teacher.index') }}">Back to dashboard</a>
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn secondary" href="{{ route('dashboard.teacher.attendance') }}">Attendance</a>
    </div>
    <div class="pill">Route: dashboard.teacher.classes</div>
@endsection
