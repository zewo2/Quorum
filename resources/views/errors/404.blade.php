@extends('layouts.debug')

@section('content')
	<h1>404 Not found</h1>
	<p>We could not find that resource.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('dashboard.student.index') }}">Student dashboard</a>
		<a class="btn ghost" href="{{ route('dashboard.admin.index') }}">Admin dashboard</a>
	</div>
	<div class="pill">Route: errors.not-found</div>
@endsection
