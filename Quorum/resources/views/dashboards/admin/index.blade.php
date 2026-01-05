@extends('layouts.debug')

@section('content')
    <h1>Admin dashboard</h1>
    <p>Placeholder admin view. Swap this out with real widgets once the backend is ready.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.admin.users') }}">Manage users</a>
        <a class="btn" href="{{ route('dashboard.admin.courses') }}">Manage courses</a>
        <a class="btn secondary" href="{{ route('register') }}">Register user</a>
        <a class="btn ghost" href="{{ route('home') }}">Home</a>
    </div>
    <div class="pill">Route: dashboard.admin.index</div>
@endsection
