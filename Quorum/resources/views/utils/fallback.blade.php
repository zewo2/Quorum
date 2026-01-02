@extends('layouts.debug')

@section('content')
	<h1>Fallback route</h1>
	<p>This is the fallback view for unmatched routes.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('dashboard.teacher.index') }}">Teacher dashboard</a>
		<a class="btn ghost" href="{{ route('auth.login') }}">Login</a>
	</div>
	<div class="pill">Route: fallback (404)</div>
@endsection
