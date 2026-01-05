@extends('layouts.debug')

@section('content')
	<h1>403 Forbidden</h1>
	<p>Access denied placeholder. Need to wire this page to authorization checks later.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('login') }}">Login</a>
		<a class="btn ghost" href="{{ route('dashboard.admin.index') }}">Admin dashboard</a>
	</div>
	<div class="pill">Route: errors.forbidden</div>
@endsection
