@props(['guest', 'dependentes'])

<section class="confirmacao-presenca" id="confirmacao-presenca">
	<div class="container">

		<div class="confirmacao-presenca__shell">
			<div class="confirmacao-presenca__header">
				<h2 class="confirmacao-presenca__title">Confirmação de Presença</h2>

				<p class="confirmacao-presenca__desc">
					Estamos contando os dias para celebrar esse momento especial com você!
					Por favor, confirme sua presença até o dia <strong>05 de agosto de 2026</strong>.
				</p>
			</div>

			@if (session('presenca_salva'))
				<div class="confirmacao-presenca__feedback confirmacao-presenca__feedback--success">
					Presenças atualizadas com sucesso!
				</div>
			@endif

			<form action="{{ route('confirmar-presenca.confirmar') }}" method="POST" class="confirmacao-presenca__form">
				@csrf

				<div class="confirmacao-presenca__card">

					{{-- Minha presença --}}
					<div class="confirmacao-presenca__grupo">
						<div class="confirmacao-presenca__grupo-head">
							<h3 class="confirmacao-presenca__grupo-titulo">Confirmar Minha Presença</h3>
						</div>

						<div class="confirmacao-presenca__pessoa">
							<div class="confirmacao-presenca__pessoa-avatar">
								{{ \Illuminate\Support\Str::of($guest->nome_completo)->explode(' ')->take(2)->map(fn($parte) => mb_substr($parte, 0, 1))->implode('') }}
							</div>

							<div class="confirmacao-presenca__pessoa-info">
						<span class="confirmacao-presenca__pessoa-nome">{{ $guest->nome_completo }}</span>
						<span class="confirmacao-presenca__pessoa-tipo">{{ ucfirst($guest->tipo) }}</span>
							</div>

							<div class="confirmacao-presenca__pessoa-controls">
								<label class="rsvp-toggle" x-data="{ checked: {{ $guest->presenca_confirmada === 'S' ? 'true' : 'false' }} }">
									<input
										type="checkbox"
										name="confirmados[]"
										value="{{ $guest->id }}"
										@checked($guest->presenca_confirmada === 'S')
										x-model="checked"
									>

									<span
										class="rsvp-toggle__label"
										x-text="checked ? 'Estarei presente' : 'Não poderei comparecer'"
									>{{ $guest->presenca_confirmada === 'S' ? 'Estarei presente' : 'Não poderei comparecer' }}</span>

									<span class="rsvp-toggle__track" :class="{ 'rsvp-toggle__track--on': checked }">
										<span class="rsvp-toggle__thumb"></span>
									</span>
								</label>

								@if ($guest->sexo === 'F')
									<div class="rsvp-shoe-size" x-data="{ size: {{ $guest->numero_calcado ?? 36 }} }">
										<label class="rsvp-shoe-size__label">Número do calçado</label>
										<div class="rsvp-shoe-size__stepper">
											<button type="button" class="rsvp-shoe-size__btn rsvp-shoe-size__btn--minus" @click="size = Math.max(33, size - 1)">−</button>
											<span class="rsvp-shoe-size__value" x-text="size">{{ $guest->numero_calcado ?? 36 }}</span>
											<button type="button" class="rsvp-shoe-size__btn rsvp-shoe-size__btn--plus" @click="size = Math.min(42, size + 1)">+</button>
										</div>
										<input type="hidden" name="calcado[{{ $guest->id }}]" value="{{ $guest->numero_calcado ?? 36 }}" :value="size">
									</div>
								@endif
							</div>
						</div>
					</div>

					@if ($dependentes->isNotEmpty())
						<div class="confirmacao-presenca__grupo confirmacao-presenca__grupo--separado">
							<div class="confirmacao-presenca__grupo-head">
								<h3 class="confirmacao-presenca__grupo-titulo">Confirmar Presenças do Grupo</h3>
								<p class="confirmacao-presenca__grupo-desc">
									Confirme também a presença dos convidados do seu grupo.
								</p>
							</div>

							@foreach ($dependentes as $dep)
								<div class="confirmacao-presenca__pessoa">
									<div class="confirmacao-presenca__pessoa-avatar">
										{{ \Illuminate\Support\Str::of($dep->nome_completo)->explode(' ')->take(2)->map(fn($parte) => mb_substr($parte, 0, 1))->implode('') }}
									</div>

									<div class="confirmacao-presenca__pessoa-info">
										<span class="confirmacao-presenca__pessoa-nome">{{ $dep->nome_completo }}</span>
										<span class="confirmacao-presenca__pessoa-tipo">{{ ucfirst($dep->tipo) }}</span>
									</div>

									<div class="confirmacao-presenca__pessoa-controls">
										<label class="rsvp-toggle" x-data="{ checked: {{ $dep->presenca_confirmada === 'S' ? 'true' : 'false' }} }">
											<input
												type="checkbox"
												name="confirmados[]"
												value="{{ $dep->id }}"
												@checked($dep->presenca_confirmada === 'S')
												x-model="checked"
											>

											<span
												class="rsvp-toggle__label"
												x-text="checked ? 'Estará presente' : 'Não poderá comparecer'"
											>{{ $dep->presenca_confirmada === 'S' ? 'Estará presente' : 'Não poderá comparecer' }}</span>

											<span class="rsvp-toggle__track" :class="{ 'rsvp-toggle__track--on': checked }">
												<span class="rsvp-toggle__thumb"></span>
											</span>
										</label>

										@if ($dep->sexo === 'F')
											<div class="rsvp-shoe-size" x-data="{ size: {{ $dep->numero_calcado ?? 36 }} }">
												<label class="rsvp-shoe-size__label">Número do calçado</label>
												<div class="rsvp-shoe-size__stepper">
													<button type="button" class="rsvp-shoe-size__btn rsvp-shoe-size__btn--minus" @click="size = Math.max(33, size - 1)">−</button>
													<span class="rsvp-shoe-size__value" x-text="size">{{ $dep->numero_calcado ?? 36 }}</span>
													<button type="button" class="rsvp-shoe-size__btn rsvp-shoe-size__btn--plus" @click="size = Math.min(42, size + 1)">+</button>
												</div>
												<input type="hidden" name="calcado[{{ $dep->id }}]" value="{{ $dep->numero_calcado ?? 36 }}" :value="size">
											</div>
										@endif
									</div>
								</div>
							@endforeach
						</div>
					@endif

				</div>

				<div class="confirmacao-presenca__actions">
					<button type="submit" class="btn-presenca">Salvar Confirmações</button>
				</div>

			</form>
		</div>

	</div>
</section>
