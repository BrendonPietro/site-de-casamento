<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="robots" content="ALL" />
		<meta name="googlebot" content="INDEX, FOLLOW" />
		<title>{{ $site->meta->title ?? 'Letícia & Brendon' }}</title>
		<meta name="description" content="{{ $site->meta->description ?? '' }}">
		<meta name="keywords" content="{{ $site->meta->keywords ?? '' }}">

		<link rel="shortcut icon" href="{{ Vite::asset('resources/svg/site/logo/lb_guia.svg') }}"/>

		<!-- Open Graph -->
		<meta property="og:title" content="{{ $site->meta->title ?? 'Letícia & Brendon' }}">
		<meta property="og:description" content="{{ $site->meta->description ?? '' }}">
		<meta property="og:type" content="website">
		<meta property="og:url" content="{{ url()->current() }}">
		<meta property="og:locale" content="pt_BR">

		@vite(['resources/scss/site/index.scss'])
		@vite(['resources/js/site/app.js'])
		@stack('head')
		<script>if (location.hash) { history.scrollRestoration = 'manual'; window.scrollTo(0, 0); }</script>
	</head>

	<body x-data="{ mobileMenuOpen: false, showLoginModal: {{ request()->has('login') && !auth('guest')->check() ? 'true' : 'false' }} }" :class="{ 'overflow-hidden': mobileMenuOpen || showLoginModal }" id="{{ $idBody ?? 'app' }}">
		@stack('before_content')
		@yield('content')
		@stack('after_content')
		@stack('scripts')

		<script>
		document.addEventListener('DOMContentLoaded', function () {
			if (location.hash) {
				var hashTarget = document.querySelector(location.hash);
				if (hashTarget) {
					setTimeout(function () {
						hashTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
					}, 80);
				}
			}

			document.addEventListener('click', function (e) {
				var link = e.target.closest('a[href]');
				if (!link) return;
				var href = link.getAttribute('href');
				var url;
				try { url = new URL(href, location.href); } catch (err) { return; }
				if (!url.hash) return;
				if (url.pathname !== location.pathname) return;
				var target = document.querySelector(url.hash);
				if (!target) return;
				e.preventDefault();
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
				history.pushState(null, '', url.hash);
			});
		});
		</script>
	</body>
</html>
