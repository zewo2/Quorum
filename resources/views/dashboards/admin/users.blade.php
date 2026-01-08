@extends('layouts.debug')

@section('content')
    <h1>Users Management</h1>
    <p>Placeholder for admin user management. Build the CRUD interface and filters here.</p>
    <div class="actions">
        <a class="btn" href="{{ route('dashboard.admin.index') }}">Back to admin</a>
        <a class="btn" href="{{ route('home') }}">Home</a>
        <a class="btn ghost" href="{{ route('dashboard.admin.courses') }}">Manage courses</a>
    </div>
    <div class="pill">Route: dashboard.admin.users</div>
@endsection
