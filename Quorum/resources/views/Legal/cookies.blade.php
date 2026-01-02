@extends('layouts.debug')

@section('content')
	<h1>Cookies</h1>
	<p>Placeholder copy about how cookies will be described to users. Swap with policy text later.</p>
	<div class="actions">
		<a class="btn" href="{{ route('home') }}">Home</a>
		<a class="btn ghost" href="{{ route('legal.privacy') }}">Privacy</a>
		<a class="btn ghost" href="{{ route('legal.terms') }}">Terms</a>
	</div>
	<div class="pill">Route: legal.cookies</div>
@endsection
