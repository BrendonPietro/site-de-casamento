{{-- Informações Importantes --}}
<section class="info-importantes">
	<div class="info-importantes__header">
		<img src="{{ Vite::asset('resources/images/site/informacoes/up_circle.png') }}" class="info-importantes__arc" alt="">
		<div class="container info-importantes__header-inner">
			<h2 class="info-importantes__title">Informações importantes</h2>
		</div>
	</div>

	<div class="container">
		<ul class="info-importantes__list">
			<li class="info-importantes__item">
				Há estacionamento no local da recepção, com serviço de valet gratuito;
			</li>
			<li class="info-importantes__item">
				Por ser em área verde, há um pequeno trecho de estrada de terra no caminho;
			</li>
			<li class="info-importantes__item">
				Para brindar e celebrar esse dia especial, teremos serviço de bar disponível. Queremos que todos aproveitem com segurança, portanto, se for beber não dirija!
			</li>
			<li class="info-importantes__item">
				Para maior comodidade, estamos organizando um serviço de transporte (van ou micro-ônibus) para ida e volta do local. Os detalhes e valores serão informados em breve. Se tiver interesse, pedimos que nos avise por aqui.
			</li>
		</ul>

		<div class="info-importantes__cta">
			<a href="{{ route('confirmar-presenca') }}#servico-van" class="info-importantes__btn">Tenho Interesse</a>
		</div>
	</div>
</section>
