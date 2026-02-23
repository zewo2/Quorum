<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quorum - Campus Management')</title>
    <link rel="icon" type="image/png" href="{{ asset('Quorum_logo - no background.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="frontend">
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <span class="logo-text">Quorum</span>
                        <span class="logo-subtitle">Campus Management</span>
                    </a>
                </div>

                <nav class="main-nav">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <a href="#about" class="nav-link">About</a>
                    <a href="#campus" class="nav-link">Campus</a>
                    <a href="#blog" class="nav-link">News</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </nav>

                <div class="header-actions">
                    @auth
                        <a href="{{ route('portal') }}" class="btn btn-portal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            Portal
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                        <a href="{{ route('portal') }}" class="btn btn-portal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            Portal
                        </a>
                    @endauth
                </div>

                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Quorum</h3>
                    <p>Modern campus management for the digital age</p>
                </div>

                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#campus">Campus</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="{{ route('login') }}">Student Portal</a></li>
                        <li><a href="{{ route('login') }}">Faculty Portal</a></li>
                        <li><a href="#admissions">Admissions</a></li>
                        <li><a href="#library">Library</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('legal.terms') }}">Terms of Service</a></li>
                        <li><a href="{{ route('legal.cookies') }}">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Quorum Campus Management. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            this.classList.toggle('active');
            document.querySelector('.main-nav')?.classList.toggle('active');
        });
    </script>
</body>
</html>
