<x-site.app :current_page="'home'">

	{{-- 1. Banner Hero (foto do casal) --}}
	<x-site.content.hero-banner />

	{{-- 2. Nomes do Casal (Letícia & Brendon em Parfumerie + texto) --}}
	<x-site.content.nomes-casal />

	{{-- 3. Data de Realização (imagem + data 05/09/26) --}}
	<x-site.content.data-realizacao />

	{{-- 3.1. Contagem Regressiva (logado) / 4. Convite Virtual (deslogado) --}}
	@auth('guest')
		<x-site.content.contagem-regressiva />
	@else
		<x-site.content.convite />
	@endauth

	{{-- 5. O Casal (galeria de fotos: 3 no desktop, carrossel no mobile) --}}
	<x-site.content.o-casal />

	{{-- 6. Confirmar Presença --}}
	<x-site.content.confirmar-presenca />

	{{-- 7. Lista de Presentes CTA (apenas mobile) --}}
	<x-site.content.lista-presentes-mobile />

</x-site.app>
