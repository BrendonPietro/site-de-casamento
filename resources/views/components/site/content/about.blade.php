<section id="about" class="presentation relative dp-flex col v-center h-right gap-30px w-100% py-100px px-80px mob:p-20px">
	<div class="dp-flex col v-center h-right mob:h-center gap-30px w-45% mob:w-100% mob:z-2">
		<h1 class="c(c-t1-600) mob:!c(c-s-1) fnt(wgt-black) fs-50px xl:fs-45px mob:fs-30px t-right mob:t-center">Excelência Técnica a Serviço do Seu Negócio</h1>

		<div class="dp-flex col v-center h-right mob:h-center gap-15px mob:gap-10px w-100%">
			<p class="c(c-s-4) mob:!c(c-s-1) fnt(wgt-regular) fs-18px xl:fs-16px mob:fs-12px lh-1.3em t-right mob:t-center">A JRJ Assessoria é referência em <b>segurança do trabalho</b>, <b>manutenção industrial</b> e <b>alpinismo profissional</b>. Atuamos com soluções completas em proteção contra incêndio, trabalhos em altura, espaços confinados, treinamentos regulamentados e serviços de engenharia ocupacional.</p>
			<p class="c(c-s-4) mob:!c(c-s-1) fnt(wgt-regular) fs-18px xl:fs-16px mob:fs-12px lh-1.3em t-right mob:t-center">Com equipe própria altamente qualificada, tecnologia atualizada e compromisso ético, garantimos que cada projeto seja executado com qualidade, pontualidade e segurança.</p>
		</div>

		<a href="{{ $globalConfig->whatsapp_link }}" target="_blank" x-data="{ isDesktop: window.innerWidth >= 1024 }" x-init="window.addEventListener('resize', () => isDesktop = window.innerWidth >= 1024)" x-bind:class="{ 'dark': isDesktop }" class="btn-2 fnt(wgt-bold) py-15px px-50px fs-18px xl:fs-16px mob:fs-14px hover:opacity-0.8 t-0.2s">Conheça a Empresa</a>
	</div>
</section>
