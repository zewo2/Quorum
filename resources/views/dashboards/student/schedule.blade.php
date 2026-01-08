@extends('layouts.debug')

@section('content')
    <h1>My Schedule</h1>
    <p>Placeholder for student schedule view. Show enrolled classes and meeting times here.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.student.index') }}">Back to dashboard</a>
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn ghost" href="{{ route('dashboard.student.subjects') }}">My subjects</a>
    </div>
    <div class="pill">Route: dashboard.student.schedule</div>
@endsection
