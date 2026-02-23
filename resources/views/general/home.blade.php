@extends('layouts.fe_master')

@section('title', 'Quorum - Campus Management System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('chatbot/n8n-chat-widget.css') }}">
@endpush

@section('content')
<section class="hero">
    <div class="hero-background"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Quorum</h1>
            <p class="hero-subtitle">Your gateway to modern campus management and academic excellence</p>
            <div class="hero-actions">
                <a href="{{ route('portal') }}" class="btn btn-primary btn-lg">Access Portal</a>
                <a href="#about" class="btn btn-outline btn-lg">Learn More</a>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </div>
</section>

<section id="about" class="section section-about">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">About Quorum</h2>
            <p class="section-subtitle">Excellence in education through innovative technology</p>
        </div>

        <div class="about-grid">
            <div class="about-card">
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                    </svg>
                </div>
                <h3>Academic Excellence</h3>
                <p>Top-tier education with world-class faculty and cutting-edge research facilities</p>
            </div>

            <div class="about-card">
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <h3>Smart Management</h3>
                <p>Advanced timetable optimization using genetic algorithms for efficient scheduling</p>
            </div>

            <div class="about-card">
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3>Student-Centered</h3>
                <p>Comprehensive portal for grades, schedules, and academic resources at your fingertips</p>
            </div>
        </div>
    </div>
</section>

<section id="campus" class="section section-campus">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Campus</h2>
            <p class="section-subtitle">A modern learning environment designed for success</p>
        </div>

        <div class="campus-content">
            <div class="campus-map">
                <div class="map-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <p>Interactive Campus Map</p>
                    <small>Coming Soon</small>
                </div>
            </div>

            <div class="campus-features">
                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    </svg>
                    <div>
                        <h4>Modern Facilities</h4>
                        <p>State-of-the-art classrooms and laboratories</p>
                    </div>
                </div>

                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <div>
                        <h4>Library & Resources</h4>
                        <p>Extensive digital and physical collections</p>
                    </div>
                </div>

                <div class="feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="10 8 16 12 10 16 10 8"></polygon>
                    </svg>
                    <div>
                        <h4>Student Life</h4>
                        <p>Vibrant campus community and activities</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="blog" class="section section-news">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Latest News</h2>
            <p class="section-subtitle">Stay updated with campus announcements and events</p>
        </div>

        <div class="news-grid">
            <article class="news-card">
                <div class="news-image">
                    <div class="news-image-placeholder"></div>
                </div>
                <div class="news-content">
                    <span class="news-date">January 5, 2026</span>
                    <h3 class="news-title">New Semester Registration Now Open</h3>
                    <p class="news-excerpt">Students can now register for Spring 2026 semester courses through the portal. Early registration benefits available.</p>
                    <a href="#" class="news-link">Read more →</a>
                </div>
            </article>

            <article class="news-card">
                <div class="news-image">
                    <div class="news-image-placeholder"></div>
                </div>
                <div class="news-content">
                    <span class="news-date">January 3, 2026</span>
                    <h3 class="news-title">AI-Powered Timetable System Launches</h3>
                    <p class="news-excerpt">Our new genetic algorithm-based scheduling system ensures optimal class times and room allocations for all students.</p>
                    <a href="#" class="news-link">Read more →</a>
                </div>
            </article>

            <article class="news-card">
                <div class="news-image">
                    <div class="news-image-placeholder"></div>
                </div>
                <div class="news-content">
                    <span class="news-date">December 28, 2025</span>
                    <h3 class="news-title">Winter Break Schedule Announced</h3>
                    <p class="news-excerpt">Important dates and campus facility hours during the winter break period. Plan your vacation accordingly.</p>
                    <a href="#" class="news-link">Read more →</a>
                </div>
            </article>
        </div>
    </div>
</section>

<section id="contact" class="section section-contact">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Get in Touch</h2>
            <p class="section-subtitle">We're here to help with any questions</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div>
                        <h4>Campus Address</h4>
                        <p>123 University Avenue<br>Campus City, CC 12345</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h4>Email</h4>
                        <p>info@quorum.edu</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper">
                <form class="contact-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('chatbot/scripts.js') }}"></script>
    <script
        src="{{ asset('chatbot/n8n-chat-widget.js') }}"
        defer
        data-endpoint="http://localhost:5678/webhook/a9a7e16f-c23e-412a-9c8f-546523bfbab2"
        data-title="Quorium - AI Assistant"
        data-welcome="Hello! I'm Quorum's virtual assistant. How can I help you today?"
        data-placeholder="Type your message..."
        data-position="bottom-right"
    ></script>
@endpush

