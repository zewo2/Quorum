@extends('layouts.debug')

@section('content')
	<h1>Reset password</h1>
	<p>Enter your email to receive a password reset link.</p>

	@if (session('status'))
		<div style="color: #10b981; padding: 12px; margin-bottom: 16px; background: rgba(16, 185, 129, 0.1); border-radius: 8px;">
			{{ session('status') }}
		</div>
	@endif

	@if ($errors->any())
		<div style="color: #ef4444; padding: 12px; margin-bottom: 16px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
			<ul style="margin: 0; padding-left: 20px;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form method="POST" action="{{ route('password.email') }}" style="margin-bottom: 20px;">
		@csrf
		<div style="margin-bottom: 16px;">
			<label for="email" style="display: block; margin-bottom: 6px; font-weight: 500;">Email</label>
			<input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
				style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #334155; background: #1e293b; color: #e2e8f0;">
		</div>
		<button type="submit" class="btn" style="width: 100%;">Send reset link</button>
	</form>

	<div class="actions">
		<a class="btn ghost" href="{{ route('home') }}">Home</a>
		<a class="btn" href="{{ route('login') }}">Back to login</a>
	</div>
	<div class="pill">Route: password.request</div>
@endsection
