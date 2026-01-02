@extends('layouts.debug')

@section('content')
    <h1>Teacher dashboard</h1>
    <p>Placeholder teacher workspace.</p>
    <div class="actions">
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn" href="{{ route('dashboard.admin.index') }}">Admin</a>
        <a class="btn" href="{{ route('dashboard.student.index') }}">Student</a>
        <a class="btn ghost" href="{{ route('legal.privacy') }}">Privacy</a>
    </div>
    <div class="pill">Route: dashboard.teacher.index</div>
@endsection
