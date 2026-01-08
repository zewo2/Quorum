@extends('layouts.debug')

@section('content')
	<h1>Set new password</h1>
	<p>Enter your new password below.</p>

	@if ($errors->any())
		<div style="color: #ef4444; padding: 12px; margin-bottom: 16px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
			<ul style="margin: 0; padding-left: 20px;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form method="POST" action="{{ route('password.update') }}" style="margin-bottom: 20px;">
		@csrf
		<input type="hidden" name="token" value="{{ $request->route('token') }}">

		<div style="margin-bottom: 16px;">
			<label for="email" style="display: block; margin-bottom: 6px; font-weight: 500;">Email</label>
			<input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
				style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #e2e8f0;">
		</div>
		<div style="margin-bottom: 16px;">
			<label for="password" style="display: block; margin-bottom: 6px; font-weight: 500;">New Password</label>
			<input type="password" id="password" name="password" required
				style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #e2e8f0;">
		</div>
		<div style="margin-bottom: 16px;">
			<label for="password_confirmation" style="display: block; margin-bottom: 6px; font-weight: 500;">Confirm Password</label>
			<input type="password" id="password_confirmation" name="password_confirmation" required
				style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #e2e8f0;">
		</div>
		<button type="submit" class="btn" style="width: 100%;">Reset password</button>
	</form>

	<div class="actions">
		<a class="btn ghost" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('login') }}">Back to login</a>
	</div>
	<div class="pill">Route: password.reset</div>
@endsection
