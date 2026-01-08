@extends('layouts.fe_master')

@section('title', 'Terms of Service - Quorum')

@section('content')
<div class="legal-page">
	<div class="legal-container">
		<article class="legal-content">
			<h1>Terms of Service</h1>
			<p class="last-updated">Last updated: January 8, 2026</p>

			<section>
				<h2>1. Acceptance of Terms</h2>
				<p>By accessing and using the Quorum Campus Management System, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
			</section>

			<section>
				<h2>2. Use License</h2>
				<p>Permission is granted to temporarily access and use the Quorum platform for lawful purposes only. You agree not to:</p>
				<ul>
					<li>Modify or copy any content</li>
					<li>Use the site for any commercial purpose</li>
					<li>Attempt to gain unauthorized access</li>
					<li>Transmit obscene, offensive, or illegal material</li>
					<li>Disrupt the normal flow of dialogue within our website</li>
					<li>Harass or cause distress or inconvenience to any person</li>
				</ul>
			</section>

			<section>
				<h2>3. Disclaimer of Warranties</h2>
				<p>The materials on Quorum are provided "as is." Quorum makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property.</p>
			</section>

			<section>
				<h2>4. Limitations of Liability</h2>
				<p>In no event shall Quorum or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Quorum.</p>
			</section>

			<section>
				<h2>5. Accuracy of Materials</h2>
				<p>The materials appearing on Quorum's website could include technical, typographical, or photographic errors. Quorum does not warrant that any of the materials on its website are accurate, complete, or current.</p>
			</section>

			<section>
				<h2>6. Links</h2>
				<p>Quorum has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Quorum of the site.</p>
			</section>

			<section>
				<h2>7. Modifications</h2>
				<p>Quorum may revise these terms of service for its website at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms of service.</p>
			</section>

			<section>
				<h2>8. Governing Law</h2>
				<p>These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which Quorum operates, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
			</section>

			<section>
				<h2>9. Contact Information</h2>
				<p>If you have any questions about these Terms of Service, please contact us at <strong>legal@quorum.edu</strong> or <strong>+1 (555) 123-4567</strong></p>
			</section>
		</article>

		<aside class="legal-sidebar">
			<div class="sidebar-box">
				<h3>Related Policies</h3>
				<ul>
					<li><a href="{{ route('legal.privacy') }}">Privacy Policy</a></li>
					<li><a href="{{ route('legal.cookies') }}">Cookie Policy</a></li>
				</ul>
			</div>
			<div class="sidebar-box">
				<h3>Need Help?</h3>
				<ul>
					<li><a href="#">FAQs</a></li>
					<li><a href="#">Support</a></li>
					<li><a href="#">Accessibility</a></li>
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
