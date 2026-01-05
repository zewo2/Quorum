@extends('layouts.debug')

@section('content')
	<h1>Login</h1>
	<p>Sign in to access your dashboard.</p>

	@if ($errors->any())
		<div style="color: #ef4444; padding: 12px; margin-bottom: 16px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
			<ul style="margin: 0; padding-left: 20px;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form method="POST" action="{{ route('login') }}" style="margin-bottom: 20px;">
		@csrf
		<div style="margin-bottom: 16px;">
			<label for="email" style="display: block; margin-bottom: 6px; font-weight: 500;">Email</label>
			<input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
				style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #e2e8f0;">
		</div>
		<div style="margin-bottom: 16px;">
			<label for="password" style="display: block; margin-bottom: 6px; font-weight: 500;">Password</label>
			<input type="password" id="password" name="password" required
				style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #e2e8f0;">
		</div>
		<div style="margin-bottom: 16px;">
			<label style="display: flex; align-items: center; gap: 8px;">
				<input type="checkbox" name="remember">
				<span>Remember me</span>
			</label>
		</div>
		<button type="submit" class="btn" style="width: 100%;">Login</button>
	</form>

	<div class="actions">
		<a class="btn ghost" href="{{ route('home') }}">Home</a>
		<a class="btn secondary" href="{{ route('register') }}">Register</a>
		<a class="btn ghost" href="{{ route('password.request') }}">Forgot password?</a>
	</div>
	<div class="pill">Route: login</div>
@endsection
