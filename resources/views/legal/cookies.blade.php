@extends('layouts.fe_master')

@section('title', 'Cookie Policy - Quorum')

@section('content')
<div class="legal-page">
	<div class="legal-container">
		<article class="legal-content">
			<h1>Cookie Policy</h1>
			<p class="last-updated">Last updated: January 8, 2026</p>

			<section>
				<h2>What are Cookies?</h2>
				<p>Cookies are small text files stored on your device when you visit our website. They help us remember your preferences, understand how you use the Quorum platform, and improve your experience.</p>
			</section>

			<section>
				<h2>Types of Cookies We Use</h2>
				<div class="cookie-types">
					<div class="cookie-item">
						<h3>Essential Cookies</h3>
						<p>Required for basic site functionality. These enable user login, session management, and security features. Essential cookies are always active.</p>
					</div>
					<div class="cookie-item">
						<h3>Preference Cookies</h3>
						<p>Remember your choices such as language, theme (light/dark mode), and display preferences to provide a personalized experience.</p>
					</div>
					<div class="cookie-item">
						<h3>Analytics Cookies</h3>
						<p>Help us understand how users interact with the platform. This data is anonymized and used to improve features and performance.</p>
					</div>
					<div class="cookie-item">
						<h3>Marketing Cookies</h3>
						<p>Used to deliver targeted content and advertisements. These are only activated with explicit user consent.</p>
					</div>
				</div>
			</section>

			<section>
				<h2>Your Cookie Choices</h2>
				<p>You can control cookies through your browser settings. Most browsers allow you to:</p>
				<ul>
					<li>View what cookies are set</li>
					<li>Delete individual or all cookies</li>
					<li>Block cookies from specific sites</li>
					<li>Set preferences for third-party cookies</li>
				</ul>
				<p>Note: Disabling essential cookies may limit site functionality.</p>
			</section>

			<section>
				<h2>Third-Party Cookies</h2>
				<p>We may allow trusted partners to set cookies for analytics and security purposes. These partners are bound by confidentiality agreements and cannot use cookie data for their own marketing.</p>
			</section>

			<section>
				<h2>Contact Us</h2>
				<p>If you have questions about our cookie policy, please contact our privacy team at <strong>privacy@quorum.edu</strong></p>
			</section>
		</article>

		<aside class="legal-sidebar">
			<div class="sidebar-box">
				<h3>Related Policies</h3>
				<ul>
					<li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
					<li><a href="{{ route('legal.terms') }}">Terms of Service</a></li>
				</ul>
			</div>
			<div class="sidebar-box">
				<h3>Quick Links</h3>
				<ul>
					<li><a href="{{ route('home') }}">Home</a></li>
					<li><a href="#contact">Contact Us</a></li>
					<li><a href="#help">Help Center</a></li>
				</ul>
			</div>
		</aside>
	</div>
</div>

<style>
.legal-page { padding: var(--spacing-2xl) 0; }
.legal-container { max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 280px; gap: var(--spacing-2xl); }
.legal-content { line-height: 1.8; }
.legal-content h1 { font-size: 2.5rem; margin-bottom: var(--spacing-md); color: var(--text-dark); }
.last-updated { color: var(--text-dark-secondary); font-size: 0.95rem; margin-bottom: var(--spacing-xl); }
.legal-content section { margin-bottom: var(--spacing-2xl); padding-bottom: var(--spacing-xl); border-bottom: 1px solid var(--border-dark); }
.legal-content h2 { font-size: 1.5rem; margin-bottom: var(--spacing-md); color: var(--text-dark); }
.legal-content h3 { color: var(--text-dark); margin-bottom: var(--spacing-xs); }
.legal-content p { color: var(--text-dark-secondary); margin-bottom: var(--spacing-md); }
.legal-content ul { padding-left: var(--spacing-lg); color: var(--text-dark-secondary); }
.legal-content li { margin-bottom: var(--spacing-sm); }
.cookie-types { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-lg); margin-top: var(--spacing-md); }
.cookie-item { background: rgba(255, 255, 255, 0.03); padding: var(--spacing-lg); border-radius: var(--radius-lg); border: 1px solid var(--border-dark); }
.cookie-item h3 { margin-bottom: var(--spacing-sm); }
.legal-sidebar { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.sidebar-box { background: rgba(255, 255, 255, 0.03); padding: var(--spacing-lg); border-radius: var(--radius-lg); border: 1px solid var(--border-dark); }
.sidebar-box h3 { font-size: 1rem; margin-bottom: var(--spacing-md); }
.sidebar-box ul { list-style: none; padding: 0; }
.sidebar-box li { margin-bottom: var(--spacing-sm); }
.sidebar-box a { color: var(--primary-light); text-decoration: none; }
.sidebar-box a:hover { color: var(--primary); }
@media (max-width: 768px) {
	.legal-container { grid-template-columns: 1fr; }
	.cookie-types { grid-template-columns: 1fr; }
}
</style>
@endsection
