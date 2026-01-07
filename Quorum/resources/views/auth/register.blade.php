@extends('layouts.fe_master')

@section('title', 'Register - Quorum')

@section('content')
<div class="auth-container">
	<div class="auth-card">
		<div class="auth-header">
			<h1>Create Account</h1>
			<p>Join Quorum to access your academic portal</p>
		</div>

		@if ($errors->any())
			<div class="alert alert-error">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('register') }}" class="auth-form">
			@csrf
			<div class="form-group">
				<label for="name">Full Name</label>
				<input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
			</div>

			<div class="form-group">
				<label for="email">Email Address</label>
				<input type="email" id="email" name="email" value="{{ old('email') }}" required>
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" required>
			</div>

			<div class="form-group">
				<label for="password_confirmation">Confirm Password</label>
				<input type="password" id="password_confirmation" name="password_confirmation" required>
			</div>

			<button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
		</form>

		<div class="auth-footer">
			<p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
		</div>
	</div>
</div>

@push('styles')
<style>
.auth-container {
	min-height: calc(100vh - 200px);
	display: flex;
	align-items: center;
	justify-content: center;
	padding: var(--spacing-xl);
}

.auth-card {
	background: white;
	border-radius: var(--radius-xl);
	box-shadow: var(--shadow-xl);
	padding: var(--spacing-2xl);
	max-width: 450px;
	width: 100%;
}

.auth-header {
	text-align: center;
	margin-bottom: var(--spacing-xl);
}

.auth-header h1 {
	font-size: 2rem;
	font-weight: 700;
	color: var(--gray-900);
	margin-bottom: var(--spacing-sm);
}

.auth-header p {
	color: var(--gray-600);
	font-size: 1rem;
}

.auth-form {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-lg);
}

.alert {
	padding: var(--spacing-md);
	border-radius: var(--radius-md);
	margin-bottom: var(--spacing-lg);
}

.alert-error {
	background: rgba(239, 68, 68, 0.1);
	color: #b91c1c;
	border: 1px solid rgba(239, 68, 68, 0.3);
}

.alert ul {
	margin: 0;
	padding-left: 20px;
}

.auth-footer {
	margin-top: var(--spacing-xl);
	text-align: center;
	color: var(--gray-600);
}

.auth-footer p {
	margin: var(--spacing-sm) 0;
}

.auth-footer a {
	color: var(--primary);
	text-decoration: none;
	font-weight: 600;
}

.auth-footer a:hover {
	color: var(--primary-dark);
	text-decoration: underline;
}
</style>
@endpush
@endsection
