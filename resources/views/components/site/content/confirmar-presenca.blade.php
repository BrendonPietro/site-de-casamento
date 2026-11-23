{{-- Confirmar Presença: visível por ora (depois só quando logado) --}}
<section class="confirmar-presenca" id="confirmar-presenca">
	<div class="container">
		<h2 class="confirmar-presenca__title">Confirmação de presença</h2>

		<p class="confirmar-presenca__description">
			Estamos contando os dias para celebrar esse momento com você!
		</p>

		<p class="confirmar-presenca__description">
			Por favor, confirme sua presença até o dia <strong>05 de agosto</strong> para que possamos preparar tudo com muito carinho.
		</p>

		<p class="confirmar-presenca__note">
			* A ausência de confirmação será entendida como impossibilidade de comparecimento.<br>
			** O convite é pessoal e intransferível. A entrada será permitida apenas para convidados listados.
		</p>

		<div class="confirmar-presenca__cta">
			@auth('guest')
				<a href="{{ route('confirmar-presenca') }}" class="btn-confirmar">Confirmar Presença</a>
			@else
				<a href="#" class="btn-confirmar" @click.prevent="showLoginModal = true">Confirmar Presença</a>
			@endauth
		</div>
	</div>
</section>
