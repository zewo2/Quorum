@extends('layouts.debug')

@section('content')
	<h1>Register</h1>
	<p>Placeholder registration screen for route verification.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn secondary" href="{{ route('auth.login') }}">Login</a>
		<a class="btn ghost" href="{{ route('auth.password.reset') }}">Reset password</a>
	</div>
	<div class="pill">Route: auth.register</div>
@endsection
