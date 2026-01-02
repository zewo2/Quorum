@extends('layouts.debug')

@section('content')
	<h1>Home</h1>
	<p>This is a debug page to make sure all the main routes are wired up correctly.</p>
	<div class="actions">
		<a class="btn" href="{{ route('dashboard.admin.index') }}">Admin dashboard</a>
		<a class="btn secondary" href="{{ route('auth.login') }}">Login</a>
		<a class="btn ghost" href="{{ route('legal.terms') }}">Terms of use</a>
	</div>
	<div class="pill">Route: home</div>
@endsection
