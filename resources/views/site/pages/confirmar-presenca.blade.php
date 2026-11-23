<x-site.app :current_page="'confirmar-presenca'">

	{{-- Spacer para compensar o header fixo --}}
	<div class="header-spacer"></div>

	{{-- 1. Formulário de confirmação de presença --}}
	<x-site.content.confirmacao-presenca :guest="$guest" :dependentes="$dependentes" />

	{{-- 2. Serviço de van --}}
	<x-site.content.servico-van :guest="$guest" :dependentes="$dependentes" />

</x-site.app>
