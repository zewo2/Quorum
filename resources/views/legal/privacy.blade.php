@extends('layouts.fe_master')

@section('title', 'Privacy Policy - Quorum')

@section('content')
<div class="legal-page">
	<div class="legal-container">
		<article class="legal-content">
			<h1>Privacy Policy</h1>
			<p class="last-updated">Last updated: January 8, 2026</p>

			<section>
				<h2>Introduction</h2>
				<p>Quorum Campus Management System ("we," "us," "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.</p>
			</section>

			<section>
				<h2>Information We Collect</h2>
				<h3>Direct Collection</h3>
				<p>We collect information you provide directly:</p>
				<ul>
					<li><strong>Account Information:</strong> Name, email, student/staff ID, phone number</li>
					<li><strong>Academic Data:</strong> Grades, attendance, course enrollments, transcript information</li>
					<li><strong>Communication:</strong> Messages, feedback, support inquiries</li>
					<li><strong>Profile Data:</strong> Photo, bio, preferences</li>
				</ul>
				<h3>Automatic Collection</h3>
				<p>When you use the platform, we automatically collect:</p>
				<ul>
					<li>IP address and device information</li>
					<li>Browser type and version</li>
					<li>Access times and pages visited</li>
					<li>Referring/exit pages</li>
				</ul>
			</section>

			<section>
				<h2>How We Use Your Information</h2>
				<p>We use collected information for:</p>
				<ul>
					<li>Providing and maintaining platform functionality</li>
					<li>Processing transactions and sending related confirmations</li>
					<li>Sending administrative updates and support messages</li>
					<li>Responding to your inquiries and requests</li>
					<li>Monitoring and analyzing usage patterns</li>
					<li>Detecting and preventing fraud or security issues</li>
					<li>Improving and personalizing user experience</li>
				</ul>
			</section>

			<section>
				<h2>Data Security</h2>
				<p>We implement comprehensive security measures to protect your personal information, including encryption, secure servers, and access controls. However, no method of transmission over the internet is 100% secure.</p>
			</section>

			<section>
				<h2>Data Retention</h2>
				<p>We retain personal data only for as long as necessary to fulfill the purposes outlined in this policy. Academic records are maintained according to institutional regulations. You may request deletion of your account and associated data at any time.</p>
			</section>

			<section>
				<h2>Your Rights</h2>
				<p>Depending on your jurisdiction, you may have the right to:</p>
				<ul>
					<li>Access your personal data</li>
					<li>Correct inaccurate information</li>
					<li>Request deletion of your data</li>
					<li>Export your data in a portable format</li>
					<li>Opt-out of certain processing</li>
				</ul>
			</section>

			<section>
				<h2>Contact Us</h2>
				<p>For privacy concerns or requests, contact: <strong>privacy@quorum.edu</strong> or call <strong>+1 (555) 123-4567</strong></p>
			</section>
		</article>

		<aside class="legal-sidebar">
			<div class="sidebar-box">
				<h3>Related Policies</h3>
				<ul>
					<li><a href="{{ route('legal.cookies') }}">Cookie Policy</a></li>
					<li><a href="{{ route('legal.terms') }}">Terms of Service</a></li>
				</ul>
			</div>
			<div class="sidebar-box">
				<h3>Your Privacy Rights</h3>
				<ul>
					<li><a href="#"> Privacy Settings</a></li>
					<li><a href="#">Data Download</a></li>
					<li><a href="#">Delete Account</a></li>
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
.legal-sidebar { display: flex; flex-direction: column; gap: var(--spacing-lg); }
.sidebar-box { background: rgba(255, 255, 255, 0.03); padding: var(--spacing-lg); border-radius: var(--radius-lg); border: 1px solid var(--border-dark); }
.sidebar-box h3 { font-size: 1rem; margin-bottom: var(--spacing-md); }
.sidebar-box ul { list-style: none; padding: 0; }
.sidebar-box li { margin-bottom: var(--spacing-sm); }
.sidebar-box a { color: var(--primary-light); text-decoration: none; }
.sidebar-box a:hover { color: var(--primary); }
@media (max-width: 768px) {
	.legal-container { grid-template-columns: 1fr; }
}
</style>
@endsection
