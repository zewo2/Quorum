@extends('layouts.debug')

@section('content')
	<h1>Set new password</h1>
	<p>Placeholder new password screen for debugging flows after reset.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('auth.login') }}">Back to login</a>
		<a class="btn ghost" href="{{ route('auth.register') }}">Register</a>
	</div>
	<div class="pill">Route: auth.password.new</div>
@endsection
