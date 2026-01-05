@extends('layouts.debug')

@section('content')
    <h1>Courses Management</h1>
    <p>Placeholder for admin course management. Add create, edit, delete controls here.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.admin.index') }}">Back to admin</a>
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn ghost" href="{{ route('dashboard.admin.users') }}">Manage users</a>
    </div>
    <div class="pill">Route: dashboard.admin.courses</div>
@endsection
