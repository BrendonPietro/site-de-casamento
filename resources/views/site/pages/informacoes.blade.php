<x-site.app :current_page="'informacoes'">

	{{-- Spacer para compensar o header fixo --}}
	<div class="header-spacer"></div>

	{{-- 1. Cerimônia --}}
	<x-site.content.cerimonia />

	{{-- 2. Recepção --}}
	<x-site.content.recepcao />

	{{-- 3. Informações Importantes (valet, terra, bar, van) --}}
	<x-site.content.info-importantes />

	{{-- 4. Dress Code --}}
	<x-site.content.dress-code
		titulo="Traje Sugerido: Esporte Fino"
		descricao="Pensamos em um visual que combine elegância e conforto. Tecidos leves e fluidos, cores suaves e alegres serão muito bem-vindos."
		:dicas="[
			'Peças como jeans, bermudas, camisas de time, bonés e tênis esportivos não fazem parte do dress code e pedimos que não sejam utilizadas;',
			'Tons claros como branco, off white e nude estarão reservados para a noiva. Pedimos com carinho que explorem outras opções de cores;',
			'Verde oliva é a cor das madrinhas;',
			'Para manter a proposta delicada e sofisticada da celebração, pedimos que sejam evitados decotes, fendas e transparências;',
		]"
	/>

</x-site.app>
