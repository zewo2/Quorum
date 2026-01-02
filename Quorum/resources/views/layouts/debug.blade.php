<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quorum Route Debug</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --muted: #94a3b8;
            --accent: #38bdf8;
            --accent-2: #f472b6;
            --border: #334155;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 10% 20%, #0b1223, #0f172a 40%), #0f172a;
            color: #e2e8f0;
        }
        a { color: inherit; text-decoration: none; }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .brand { font-weight: 700; letter-spacing: 0.04em; }
        nav { display: flex; flex-wrap: wrap; gap: 8px; }
        main.wrapper {
            max-width: 960px;
            margin: 32px auto;
            padding: 0 16px 48px;
        }
        .card {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.08), rgba(244, 114, 182, 0.08)), var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }
        h1 { margin: 0 0 12px; font-size: 28px; }
        p { color: var(--muted); margin: 0 0 18px; line-height: 1.6; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: var(--accent);
            color: #0b1223;
            font-weight: 600;
            cursor: pointer;
            transition: transform 120ms ease, box-shadow 120ms ease, background 120ms ease;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(56, 189, 248, 0.35); }
        .btn.ghost {
            background: transparent;
            border-color: var(--border);
            color: #e2e8f0;
        }
        .btn.secondary { background: var(--accent-2); box-shadow: 0 8px 20px rgba(244, 114, 182, 0.3); }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
        .pill { display: inline-block; padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border); color: var(--muted); font-size: 13px; }
        footer { text-align: center; color: var(--muted); padding: 24px 16px 32px; font-size: 14px; }
    </style>
</head>
<body>
<header>
    <div class="brand">Quorum Route Debug</div>
    <nav>
        <a class="btn ghost" href="{{ route('home') }}">Home</a>
        <a class="btn ghost" href="{{ route('dashboard.admin.index') }}">Admin</a>
        <a class="btn ghost" href="{{ route('dashboard.teacher.index') }}">Teacher</a>
        <a class="btn ghost" href="{{ route('dashboard.student.index') }}">Student</a>
        <a class="btn ghost" href="{{ route('auth.login') }}">Login</a>
        <a class="btn ghost" href="{{ route('auth.register') }}">Register</a>
        <a class="btn ghost" href="{{ route('legal.privacy') }}">Privacy</a>
    </nav>
</header>
<main class="wrapper">
    <section class="card">
        @yield('content')
    </section>
</main>
<footer>
    Quick placeholder screens for route debugging.
</footer>
</body>
</html>
