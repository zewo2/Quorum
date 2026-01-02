@extends('layouts.debug')

@section('content')
	<h1>Login</h1>
	<p>Placeholder login screen to confirm routing.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn secondary" href="{{ route('auth.register') }}">Register</a>
		<a class="btn ghost" href="{{ route('auth.password.reset') }}">Reset password</a>
	</div>
	<div class="pill">Route: auth.login</div>
@endsection
