{{-- Login Modal Popup --}}
<div class="login-modal" x-show="showLoginModal" x-cloak x-transition.opacity>
	<div class="login-modal__overlay" @click="showLoginModal = false"></div>

	<div class="login-modal__content"
		 x-data="loginForm()"
		 x-trap.noscroll="showLoginModal"
		 @keydown.escape.window="showLoginModal = false">

		<button class="login-modal__close" @click="showLoginModal = false" aria-label="Fechar">✕</button>

		<h2 class="login-modal__title">Bem-vindo(a)</h2>
		<p class="login-modal__subtitle">Entre com seus dados para acessar o convite</p>

		<form @submit.prevent="submit">
			<div class="login-modal__field">
				<label class="login-modal__label" for="login-nome">Seu nome</label>
				<input class="login-modal__input"
					   id="login-nome"
					   type="text"
					   x-model="nome"
					   placeholder="Como está no convite"
					   required
					   autocomplete="name">
			</div>

			<div class="login-modal__field">
				<label class="login-modal__label" for="login-senha">Senha</label>
				<input class="login-modal__input"
					   id="login-senha"
					   type="password"
					   x-model="senha"
					   placeholder="Senha"
					   required
					   autocomplete="current-password">
			</div>

			<p class="login-modal__error" x-show="error" x-text="error" x-cloak></p>

			<button class="login-modal__submit" type="submit" :disabled="loading">
				<span x-show="!loading">Entrar</span>
				<span x-show="loading" x-cloak>Entrando...</span>
			</button>
		</form>

		<p class="login-modal__hint">Dica de senha: Telefone</p>
	</div>
</div>
