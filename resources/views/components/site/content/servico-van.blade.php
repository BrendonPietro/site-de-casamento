@props(['guest', 'dependentes'])

<section class="servico-van" id="servico-van">
	<div class="container">

		<div class="servico-van__shell">
			<div class="servico-van__header">
				<h2 class="servico-van__title">Serviço de Van</h2>

				<p class="servico-van__desc">
					Para quem preferir não ir de carro, estamos organizando vans para o dia do casamento!
					Será uma opção prática e tranquila para curtir a festa sem preocupações com o trajeto.
				</p>

				<p class="servico-van__desc">
					Marque seu interesse abaixo e entraremos em contato com mais detalhes sobre horários,
					pontos de embarque e valores assim que as informações estiverem confirmadas.
				</p>
			</div>

			@if (session('van_salva'))
				<div class="servico-van__feedback servico-van__feedback--success">
					Interesse na van atualizado com sucesso!
				</div>
			@endif

			<form action="{{ route('confirmar-presenca.van') }}" method="POST" class="servico-van__form">
				@csrf

				<div class="servico-van__card">
					<div class="servico-van__grupo">

						<div class="servico-van__pessoa">
							<div class="servico-van__pessoa-avatar">
								{{ \Illuminate\Support\Str::of($guest->nome_completo)->explode(' ')->take(2)->map(fn($parte) => mb_substr($parte, 0, 1))->implode('') }}
							</div>

							<div class="servico-van__pessoa-info">
						<span class="servico-van__pessoa-nome">{{ $guest->nome_completo }}</span>
						<span class="servico-van__pessoa-tipo">{{ ucfirst($guest->tipo) }}</span>
							</div>

							<label class="rsvp-toggle rsvp-toggle--light" x-data="{ checked: {{ $guest->interesse_van === 'S' ? 'true' : 'false' }} }">
								<input
									type="checkbox"
									name="van_ids[]"
									value="{{ $guest->id }}"
									@checked($guest->interesse_van === 'S')
									x-model="checked"
								>

								<span
									class="rsvp-toggle__label"
									x-text="checked ? 'Tenho interesse' : 'Não tenho interesse'"
								>{{ $guest->interesse_van === 'S' ? 'Tenho interesse' : 'Não tenho interesse' }}</span>

								<span class="rsvp-toggle__track" :class="{ 'rsvp-toggle__track--on': checked }">
									<span class="rsvp-toggle__thumb"></span>
								</span>
							</label>
						</div>

						@foreach ($dependentes as $dep)
							<div class="servico-van__pessoa">
								<div class="servico-van__pessoa-avatar">
									{{ \Illuminate\Support\Str::of($dep->nome_completo)->explode(' ')->take(2)->map(fn($parte) => mb_substr($parte, 0, 1))->implode('') }}
								</div>

								<div class="servico-van__pessoa-info">
									<span class="servico-van__pessoa-nome">{{ $dep->nome_completo }}</span>
									<span class="servico-van__pessoa-tipo">{{ ucfirst($dep->tipo) }}</span>
								</div>

								<label class="rsvp-toggle rsvp-toggle--light" x-data="{ checked: {{ $dep->interesse_van === 'S' ? 'true' : 'false' }} }">
									<input
										type="checkbox"
										name="van_ids[]"
										value="{{ $dep->id }}"
										@checked($dep->interesse_van === 'S')
										x-model="checked"
									>

									<span
										class="rsvp-toggle__label"
										x-text="checked ? 'Tem interesse' : 'Não tem interesse'"
									>{{ $dep->interesse_van === 'S' ? 'Tem interesse' : 'Não tem interesse' }}</span>

									<span class="rsvp-toggle__track" :class="{ 'rsvp-toggle__track--on': checked }">
										<span class="rsvp-toggle__thumb"></span>
									</span>
								</label>
							</div>
						@endforeach

					</div>
				</div>

				<div class="servico-van__actions">
					<button type="submit" class="btn-van">Salvar Interesse</button>
				</div>

			</form>
		</div>

	</div>
</section>
