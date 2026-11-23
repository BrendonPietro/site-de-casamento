@props([
    'current_page'    => '',
    'forced_scrolled' => false,
])

<header class="site-header" x-data="{ scrolled: {{ $forced_scrolled ? 'true' : 'false' }} }"
	x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
	:class="{ 'site-header--scrolled': scrolled }">

	<div class="site-header__container">
		{{-- Mobile Logo --}}
		<a href="{{ route('home') }}" class="site-header__mobile-logo">
			<img src="{{ Vite::asset('resources/svg/site/logo/lb_trigo.svg') }}" alt="LB" />
		</a>

		{{-- Desktop Nav --}}
		<nav class="site-header__nav">
			<a href="{{ route('home') }}" class="site-header__link {{ $current_page === 'home' ? 'site-header__link--active' : '' }}">
				Home
			</a>
			@guest('guest')
				<a href="{{ route('home') }}#convite" class="site-header__link">
					Convite Virtual
				</a>
			@endguest
			@guest('guest')
				<a href="{{ route('home') }}#o-casal" class="site-header__link">
					O Casal
				</a>
			@endguest
			@auth('guest')
				<a href="{{ route('informacoes') }}" class="site-header__link {{ $current_page === 'informacoes' ? 'site-header__link--active' : '' }}">
					Informações
				</a>
			@endauth
			@auth('guest')
				<a href="{{ route('confirmar-presenca') }}" class="site-header__link {{ $current_page === 'confirmar-presenca' ? 'site-header__link--active' : '' }}">
					Confirmação Presença
				</a>
			@endauth
			<a href="{{ route('gift-list') }}" class="site-header__link {{ $current_page === 'gift-list' ? 'site-header__link--active' : '' }}">
				Lista Presentes
			</a>

			@if (auth('guest')->user()?->isDono())
					<a href="{{ url('/painel') }}" class="site-header__link site-header__link--painel">
						Painel
					</a>
				@endif

				{{-- Profile Avatar (desktop) --}}
			@auth('guest')
				<div class="site-header__profile" x-data="{ profileOpen: false }">
					<button class="site-header__avatar"
							@click="profileOpen = !profileOpen"
							@click.outside="profileOpen = false">
						{{ mb_strtoupper(mb_substr(auth('guest')->user()->nome_completo, 0, 1)) }}
					</button>

					<div class="site-header__dropdown" x-show="profileOpen" x-cloak x-transition>
					    {{-- <a href="{{ route('confirmar-presenca') }}" class="site-header__dropdown-link" @click="profileOpen = false">
							Confirmar presença
						</a> --}}
						{{-- <a href="{{ route('informacoes') }}" class="site-header__dropdown-link" @click="profileOpen = false">
							Informações
						</a> --}}
						<form method="POST" action="{{ route('guest.logout') }}">
							@csrf
							<button type="submit" class="site-header__dropdown-link site-header__dropdown-link--danger">
								Sair
							</button>
						</form>
					</div>
				</div>
			@endauth
		</nav>

		{{-- Mobile Toggle --}}
		<button class="site-header__mobile-toggle" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Abrir menu">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>

	{{-- Mobile Menu --}}
	<div class="site-header__mobile-menu" :class="{ 'active': mobileMenuOpen }">
		<button class="site-header__mobile-close" @click="mobileMenuOpen = false" aria-label="Fechar menu">
			✕
		</button>
		<a href="{{ route('home') }}" class="site-header__mobile-link" @click="mobileMenuOpen = false">Home</a>
		<a href="{{ route('home') }}#o-casal" class="site-header__mobile-link" @click="mobileMenuOpen = false">O Casal</a>
		<a href="{{ route('gift-list') }}" class="site-header__mobile-link" @click="mobileMenuOpen = false">Lista Presentes</a>

		@auth('guest')
			<a href="{{ route('confirmar-presenca') }}" class="site-header__mobile-link" @click="mobileMenuOpen = false">Confirmação</a>
			<div class="site-header__mobile-divider"></div>
			<a href="{{ route('informacoes') }}" class="site-header__mobile-link" @click="mobileMenuOpen = false">Informações</a>
				@if (auth('guest')->user()?->isDono())
					<a href="{{ url('/painel') }}" class="site-header__mobile-link" @click="mobileMenuOpen = false">Painel</a>
				@endif
			<form method="POST" action="{{ route('guest.logout') }}" style="display:contents">
				@csrf
				<button type="submit" class="site-header__mobile-link site-header__mobile-link--logout">Sair</button>
			</form>
		@else
			<a href="{{ route('home') }}#convite" class="site-header__mobile-link" @click="mobileMenuOpen = false">Convite Virtual</a>
		@endauth
	</div>
</header>
