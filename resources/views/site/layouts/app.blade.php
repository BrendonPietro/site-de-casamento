@extends('site.layouts.global')

@section('content')
	<x-site.content.header :current_page="$currentPage ?? ''" :forced-scrolled="$forcedScrolled ?? false"/>

	<main>
		{{ $slot }}
	</main>

	<x-site.content.footer/>

	@guest('guest')
		<x-site.content.login-modal/>
	@endguest
@endsection
