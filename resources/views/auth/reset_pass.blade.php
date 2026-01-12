@extends('layouts.fe_master')

@section('title', 'Forgot Password - Quorum')

@section('content')
<div class="auth-container">
	<div class="auth-card">
		<div class="auth-header">
			<h1>Reset Password</h1>
			<p>Enter your email address and we'll send you a link to reset your password</p>
		</div>

		@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
		@endif

		@if ($errors->any())
			<div class="alert alert-error">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form method="POST" action="{{ route('password.email') }}" class="auth-form">
			@csrf
			<div class="form-group">
				<label for="email">Email Address</label>
				<input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
			</div>

			<button type="submit" class="btn btn-primary" style="width: 100%;">Send Reset Link</button>
		</form>

		<div class="auth-footer">
			<p><a href="{{ route('login') }}">← Back to login</a></p>
			<p>Remember your password? <a href="{{ route('login') }}">Sign in here</a></p>
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

.form-group {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-sm);
}

.form-group label {
	color: var(--gray-700);
	font-weight: 500;
	font-size: 0.95rem;
}

.form-group input {
	padding: var(--spacing-md) var(--spacing-lg);
	border: 1px solid var(--gray-300);
	border-radius: var(--radius-md);
	font-size: 1rem;
	transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus {
	outline: none;
	border-color: var(--primary);
	box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-group input::placeholder {
	color: var(--gray-400);
}

.alert {
	padding: var(--spacing-md);
	border-radius: var(--radius-md);
	margin-bottom: var(--spacing-lg);
}

.alert-success {
	background: rgba(16, 185, 129, 0.1);
	color: #065f46;
	border: 1px solid rgba(16, 185, 129, 0.3);
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

.alert li {
	margin-bottom: 4px;
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

@media (max-width: 640px) {
	.auth-card {
		padding: var(--spacing-lg);
	}

	.auth-header h1 {
		font-size: 1.5rem;
	}
}
</style>
@endpush
@endsection
