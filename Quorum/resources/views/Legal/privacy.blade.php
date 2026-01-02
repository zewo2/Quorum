@extends('layouts.debug')

@section('content')
	<h1>Privacy</h1>
	<p>Placeholder privacy notice. Later I have to replace this text with the real compliance copy.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn ghost" href="{{ route('legal.cookies') }}">Cookies</a>
		<a class="btn ghost" href="{{ route('legal.terms') }}">Terms</a>
	</div>
	<div class="pill">Route: legal.privacy</div>
@endsection
