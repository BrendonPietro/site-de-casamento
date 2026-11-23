{{-- Convite Virtual: exibe o convite em PNG --}}
<section class="convite" id="convite">
	<div class="container">
		<p class="convite__description">
			De todas as pessoas que já passaram pelas nossas vidas, reunimos para viver conosco o dia mais importante das nossas vidas aquelas que foram parte essencial da construção desse amor. Que sempre torceram por nós e que sabemos que continuarão brindando todas as alegrias conosco.
		</p>

		<p class="convite__description">
			Agradecemos imensamente por cada coração que vibrará junto ao nosso neste dia. Você é nosso(a) convidado(a) de honra!
		</p>

		<div class="convite__image-wrapper">
			@auth('guest')
				<a href="#" class="convite__link">
			@else
				<a href="#" class="convite__link" @click.prevent="showLoginModal = true">
			@endauth
					<img src="{{ Vite::asset('resources/images/site/convite.png') }}"
						 alt="Convite Virtual - Letícia e Brendon">
					<span class="convite__cta-text">Clique aqui</span>
				</a>
		</div>
	</div>
</section>
