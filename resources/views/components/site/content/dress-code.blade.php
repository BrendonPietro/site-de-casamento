{{--
	Dress Code: componente reutilizável para diferentes perfis
	Props:
	- $titulo: "Traje Sugerido: Esporte Fino" etc.
	- $descricao: texto descritivo do dress code
	- $dicas: array de strings com dicas do traje
--}}

@props([
	'titulo' => 'Traje Sugerido',
	'descricao' => '',
	'dicas' => [],
])

@php
	$isPadrinho = in_array($guestTipo ?? '', ['padrinho', 'madrinha']);
	$ilustracao = $isPadrinho
		? Vite::asset('resources/images/site/informacoes/dress_code_padrinhos.png')
		: Vite::asset('resources/images/site/informacoes/dress_code_convidados.png');
	$inspiracao = $isPadrinho
		? Vite::asset('resources/images/site/informacoes/dress_code_ideias_padrinhos.png')
		: Vite::asset('resources/images/site/informacoes/dress_code_ideias.png');
@endphp

<section class="dress-code">
	<div class="dress-code__circle">
		<img src="{{ Vite::asset('resources/images/site/informacoes/left_circle.png') }}" alt="">
	</div>

	<div class="container">
		<h2 class="dress-code__title">Dress code</h2>

		<div class="dress-code__content">
			<div class="dress-code__illustration">
				<img src="{{ $ilustracao }}" alt="Dress Code - {{ $titulo }}">
			</div>

			<div class="dress-code__details">
				<h3 class="dress-code__dress-name">{{ $titulo }}</h3>

				@if($descricao)
					<p class="dress-code__description">{{ $descricao }}</p>
				@endif

				@if(count($dicas) > 0)
					<ul class="dress-code__tips">
						@foreach($dicas as $dica)
							<li>{{ $dica }}</li>
						@endforeach
					</ul>
				@endif
			</div>
		</div>

		<div class="dress-code__extras">
			<div class="dress-code__inspiration">
				<p class="dress-code__inspiration-label">Outras inspirações:</p>
				<div class="dress-code__inspiration-image">
					<img src="{{ $inspiracao }}" alt="Inspirações - {{ $titulo }}">
				</div>
			</div>

			<div class="dress-code__manual">
				<p class="dress-code__inspiration-label">Manual dos Convidados</p>
				<ul class="dress-code__manual-list">
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/aprovado.svg') }}" alt="">
						<span>Confirme sua presença</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/sem-som.svg') }}" alt="">
						<span>Deixe o celular no silencioso</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/flor.svg') }}" alt="">
						<span>Branco é a cor da noiva</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/horario.svg') }}" alt="">
						<span>Seja pontual</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/brigadeiro.svg') }}" alt="">
						<span>Aguarde a liberação da mesa de doces</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/fotografia.svg') }}" alt="">
						<span>Não atrapalhe os fotógrafos</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/cartinha.svg') }}" alt="">
						<span>Convidado não convida</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/taca.svg') }}" alt="">
						<span>Aproveite bastante</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/buque.svg') }}" alt="">
						<span>Não leve a decoração para casa</span>
					</li>
					<li>
						<img src="{{ Vite::asset('resources/svg/site/manual_convidado/alianca.svg') }}" alt="">
						<span>Não saia sem se despedir dos noivos</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</section>
