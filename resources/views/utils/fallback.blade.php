<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>404 - Page Not Found</title>
	<style>
		:root {
			--bg-dark: #0f172a;
			--bg-dark-secondary: #1e293b;
			--border-dark: #334155;
			--text-dark: #f1f5f9;
			--text-dark-secondary: #94a3b8;
			--primary: #4f46e5;
			--primary-dark: #4338ca;
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			min-height: 100vh;
			font-family: Inter, "Segoe UI", sans-serif;
			background: radial-gradient(circle at top, #1e293b 0%, #0f172a 55%);
			color: var(--text-dark);
			display: grid;
			place-items: center;
			padding: 24px;
		}

		.fallback-card {
			width: min(760px, 100%);
			background: var(--bg-dark-secondary);
			border: 1px solid var(--border-dark);
			border-radius: 14px;
			padding: 32px;
			box-shadow: 0 20px 48px rgba(2, 6, 23, 0.45);
		}

		.code {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-weight: 700;
			letter-spacing: 0.08em;
			font-size: 0.78rem;
			color: #c7d2fe;
			border: 1px solid #3730a3;
			background: rgba(79, 70, 229, 0.15);
			border-radius: 999px;
			padding: 5px 11px;
			margin-bottom: 16px;
		}

		h1 {
			font-size: clamp(1.5rem, 3vw, 2rem);
			margin-bottom: 10px;
			line-height: 1.2;
		}

		p {
			color: var(--text-dark-secondary);
			font-size: 1rem;
			line-height: 1.55;
			margin-bottom: 22px;
		}

		.actions {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
		}

		.btn {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			text-decoration: none;
			border-radius: 10px;
			font-weight: 600;
			font-size: 0.92rem;
			padding: 10px 14px;
			border: 1px solid transparent;
			transition: all 0.2s ease;
		}

		.btn-primary {
			background: var(--primary);
			border-color: var(--primary);
			color: #ffffff;
		}

		.btn-primary:hover {
			background: var(--primary-dark);
			border-color: var(--primary-dark);
		}

		.btn-secondary {
			background: rgba(148, 163, 184, 0.08);
			border-color: var(--border-dark);
			color: var(--text-dark);
		}

		.btn-secondary:hover {
			background: rgba(148, 163, 184, 0.16);
			border-color: #475569;
		}
	</style>
</head>
<body>
	<main class="fallback-card">
		<span class="code">404</span>
		<h1>Page not found</h1>
		<p>The page you are trying to access does not exist or was moved. Use one of the options below to continue.</p>

		<div class="actions">
			<a class="btn btn-primary" href="{{ route('home') }}">Go to Home</a>
			<a class="btn btn-secondary" href="{{ route('portal') }}">Go to Portal</a>
			@guest
				<a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
			@endguest
		</div>
	</main>
</body>
</html>
