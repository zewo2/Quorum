@extends('layouts.debug')

@section('content')
	<h1>Reset password</h1>
	<p>Placeholder reset page. Hook the form logic later.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('auth.login') }}">Login</a>
		<a class="btn secondary" href="{{ route('auth.password.new') }}">Set new password</a>
	</div>
	<div class="pill">Route: auth.password.reset</div>
@endsection
