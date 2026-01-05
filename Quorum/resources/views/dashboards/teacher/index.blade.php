@extends('layouts.debug')

@section('content')
    <h1>Teacher dashboard</h1>
    <p>Placeholder teacher workspace.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.teacher.classes') }}">My classes</a>
        <a class="btn" href="{{ route('dashboard.teacher.attendance') }}">Attendance</a>
        <a class="btn ghost" href="{{ route('home') }}">Home</a>
    </div>
    <div class="pill">Route: dashboard.teacher.index</div>
@endsection
