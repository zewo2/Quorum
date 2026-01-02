@extends('layouts.debug')

@section('content')
	<h1>Terms</h1>
	<p>Placeholder terms of use content for debugging route flow.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn ghost" href="{{ route('legal.cookies') }}">Cookies</a>
		<a class="btn ghost" href="{{ route('legal.privacy') }}">Privacy</a>
	</div>
	<div class="pill">Route: legal.terms</div>
@endsection
