@php
    $casalPhotoCount = 15;
    $linhaDoTempoPhotoCount = 8;
    $showLinhaDoTempo = false; // Mude para false para ocultar a seção
@endphp

{{-- O Casal: seção com galeria de fotos (swiper com 3 visíveis, meio maior) --}}
<section class="o-casal o-casal--bg-split" id="o-casal">
	<div class="container">
		<h2 class="o-casal__title">O Casal</h2>

        <p class="o-casal__description">
            Difícil não acreditar em histórias de amor quando se vive o mais bonito dos propósitos.<br />
            Por um motivo, a vida levou uma menina para uma escola que ela não queria ir e mal sabia ela que estava prestes a viver os momentos mais incríveis da vida dela e que mudariam tudo pra sempre. Um menino saiu de Vinhedo pra fazer o ensino médio em Campinas e 2016 seria o início da história que tem tanto pra contar e que ao mesmo tempo mal começou.<br />
            Hoje são quase 8 anos de muitas fases. E graças a elas estamos prestes a viver o início da nossa família. Com o tempo aprendemos que somos tão diferentes que isso nos torna completos. E ao mesmo tempo somos tão iguais que nos torna lar um do outro.
        </p>


		{{-- Swiper: 3 fotos visíveis, a do meio maior --}}
		<div class="o-casal__swiper">
			<div class="swiper" id="casal-swiper">
				<div class="swiper-wrapper">
					@for ($i = 1; $i <= $casalPhotoCount; $i++)
					<div class="swiper-slide">
						<img src="{{ Vite::asset('resources/images/site/casal/casal-' . $i . '.jpg') }}" alt="Letícia e Brendon">
					</div>
					@endfor
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</div>
</section>

{{-- [LINHA DO TEMPO] Para ocultar, mude $showLinhaDoTempo para false no @php acima --}}
@if ($showLinhaDoTempo)
{{-- Linha do Tempo: seção com galeria de fotos --}}
<section class="o-casal o-casal--bg-split-bottom" id="linha-do-tempo">
	<div class="container">
		<h2 class="o-casal__title"><span>Linha do Tempo</span></h2>
        <p class="o-casal__description">
            <span>O que temos de mais precioso fica nas nossas memórias. Antes de criarmos as mais importantes das nossas vidas, convidamos você a relembrar as que nos trouxeram até aqui.</span>
        </p>

		{{-- Swiper: 3 fotos visíveis, a do meio maior --}}
		<div class="o-casal__swiper">
			<div class="swiper" id="linha-swiper">
				<div class="swiper-wrapper">
					@for ($i = 1; $i <= $linhaDoTempoPhotoCount; $i++)
					<div class="swiper-slide">
						<img src="{{ Vite::asset('resources/images/site/linha-do-tempo/linha-' . $i . '.jpg') }}" alt="Letícia e Brendon">
					</div>
					@endfor
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</div>
</section>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
	new Swiper('#casal-swiper', {
		modules: [SwiperModules.Pagination, SwiperModules.Autoplay],
		slidesPerView: 1,
		centeredSlides: true,
		spaceBetween: 16,
		loop: true,
		pagination: {
			el: '#casal-swiper .swiper-pagination',
			clickable: true,
		},
		autoplay: {
			delay: 6000,
			disableOnInteraction: false,
		},
		breakpoints: {
			768: {
				slidesPerView: 3,
				spaceBetween: 24,
			}
		}
	});

	@if ($showLinhaDoTempo)
	new Swiper('#linha-swiper', {
		modules: [SwiperModules.Pagination, SwiperModules.Autoplay],
		slidesPerView: 1,
		centeredSlides: true,
		spaceBetween: 16,
		loop: true,
		pagination: {
			el: '#linha-swiper .swiper-pagination',
			clickable: true,
		},
		autoplay: {
			delay: 6000,
			disableOnInteraction: false,
		},
		breakpoints: {
			768: {
				slidesPerView: 3,
				spaceBetween: 24,
			}
		}
	});
	@endif
});
</script>
@endpush
