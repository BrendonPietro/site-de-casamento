@props([
    'title' => 'Título do CTA',
    'message' => 'Mensagem do CTA',
    'buttonText' => 'Texto do Botão',
    'buttonLink' => $globalConfig->whatsapp_link,
])

<section id="cta" class="relative !bg(c-border-2) dp-flex col v-center h-center w-100% border-box overflow-x-hidden py-100px mob:py-60px sm:py-30px px-80px mob:px-40px sm:px-20px" style="background-image: url('{{ Vite::asset('resources/images/site/test/40.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="dp-flex col v-center h-center gap-30px mob:gap-20px sm:gap-15px w-100%">
		<h1 class="c(c-t1-600) fnt(wgt-medium) fs-32px xl:fs-29px mob:fs-26px sm:fs-18px t-center mob:t-center">{!! $title !!}</h1>

		<div class="dp-flex col v-center h-center gap-15px sm:gap-10px w-100%">
			<p class="c(c-s-4) fnt(wgt-medium) fs-20px xl:fs-18px mob:fs-16px sm:fs-10px lh-1.3em t-center">{!! $message !!}</p>
		</div>

		<a href="{{ $buttonLink }}" target="_blank" class="fnt(wgt-bold) btn-1 dark py-15px mob:py-12px px-50px mob:px-35px sm:px-25px fs-18px xl:fs-16px mob:fs-14px hover:opacity-0.8 t-0.2s">{{ $buttonText }}</a>
	</div>
</section>
