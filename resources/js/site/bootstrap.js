import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

Alpine.plugin(focus);

window.Alpine = Alpine;

// Registrar componente Alpine para login
Alpine.data('loginForm', () => ({
	nome: '',
	senha: '',
	error: '',
	loading: false,

	async submit() {
		this.loading = true;
		this.error = '';

		try {
			const response = await fetch('/guest/login', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'Accept': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
				},
				body: JSON.stringify({
					nome_completo: this.nome,
					senha: this.senha,
				}),
			});

			const data = await response.json();

			if (!response.ok || !data.success) {
				throw new Error(data.message || 'Nome ou senha incorretos.');
			}

			window.location.href = '/informacoes';
		} catch (e) {
			this.error = e.message;
		} finally {
			this.loading = false;
		}
	}
}));

// Registrar componente Alpine para lista de presentes
Alpine.data('giftList', () => ({
	todosPresentes: window.__presentesData || [],
	filtroCategoria: '',
	ordenacao: 'destaque',
	currentPage: 1,
	perPage: 12,
	filtrados: [],
	totalPages: 1,
	_allFiltered: [],

	showGiftModal: false,
	selectedGift: null,
	loading: false,
	errorMessage: '',
	successMessage: '',

	form: {
		guest_name: '',
		guest_email: '',
		message: '',
		quantity: 1,
	},

	init() {
		this.filtrar();
	},

	filtrar() {
		let resultado = [...this.todosPresentes];

		if (this.filtroCategoria) {
			resultado = resultado.filter(p => p.categoria === this.filtroCategoria);
		}

		switch (this.ordenacao) {
			case 'az':
				resultado.sort((a, b) => a.nome.localeCompare(b.nome));
				break;
			case 'za':
				resultado.sort((a, b) => b.nome.localeCompare(a.nome));
				break;
			case 'preco_asc':
				resultado.sort((a, b) => parseFloat(a.valor) - parseFloat(b.valor));
				break;
			case 'preco_desc':
				resultado.sort((a, b) => parseFloat(b.valor) - parseFloat(a.valor));
				break;
			case 'destaque':
				resultado.sort((a, b) => {
					if (a.favorito === 'S' && b.favorito !== 'S') return -1;
					if (a.favorito !== 'S' && b.favorito === 'S') return 1;
					return a.nome.localeCompare(b.nome);
				});
				break;
		}

		this.currentPage = 1;
		this.totalPages = Math.ceil(resultado.length / this.perPage);
		this._allFiltered = resultado;
		this.paginar();
	},

	paginar() {
		const start = (this.currentPage - 1) * this.perPage;
		this.filtrados = (this._allFiltered || []).slice(start, start + this.perPage);
	},

	goToPage(page) {
		this.currentPage = page;
		this.paginar();
	},

	formatCurrency(value) {
		return Number(value).toFixed(2).replace('.', ',');
	},

	remainingQuotas(gift) {
		if (gift.cotavel !== 'S') return 0;
		return Math.max(0, Number(gift.num_cotas || 0) - Number(gift.cotas_vendidas || 0));
	},

	isGiftAvailable(gift) {
		if (gift.vendido === 'S') return false;
		if (gift.cotavel === 'S') return this.remainingQuotas(gift) > 0;
		if (gift.reservado === 'S' && gift.reservado_ate) {
			const expiresAt = new Date(gift.reservado_ate);
			return expiresAt <= new Date();
		}
		return true;
	},

	openGiftModal(gift) {
		this.selectedGift = gift;
		this.showGiftModal = true;
		this.loading = false;
		this.errorMessage = '';
		this.successMessage = '';

		this.form = {
			guest_name: '',
			guest_email: '',
			message: '',
			quantity: 1,
		};
	},

	closeGiftModal() {
		this.showGiftModal = false;
		this.selectedGift = null;
		this.loading = false;
		this.errorMessage = '';
		this.successMessage = '';
	},

	async reserveGift() {
		if (!this.selectedGift) return;

		this.loading = true;
		this.errorMessage = '';
		this.successMessage = '';

		const payload = {
			guest_name: this.form.guest_name,
			guest_email: this.form.guest_email,
			message: this.form.message,
		};

		if (this.selectedGift.cotavel === 'S') {
			payload.quantity = Number(this.form.quantity || 1);
		}

		try {
			const response = await fetch(`/api/gifts/${this.selectedGift.id}/reserve`, {
				method: 'POST',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
				},
				body: JSON.stringify(payload),
			});

			const result = await response.json();

			if (!response.ok || !result.success) {
				throw new Error(result.message || 'Não foi possível reservar o presente.');
			}

			this.successMessage = 'Redirecionando para o pagamento...';

			// Próxima etapa: redirecionar para o checkout do Mercado Pago
			setTimeout(() => {
				window.location.href = result.data.checkout_url;
			}, 1000);

		} catch (error) {
			this.errorMessage = error.message;
		} finally {
			this.loading = false;
		}
	},
}));

Alpine.start();

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };

import FlipDown from './flipdown.js';
import 'flipdown/dist/flipdown.css';

// Initialize FlipDown countdown on DOM ready
document.addEventListener('DOMContentLoaded', () => {
	const el = document.getElementById('flipdown');
	if (el) {
		const weddingDate = new Date('2026-09-05T10:30:00').getTime() / 1000;
		new FlipDown(weddingDate, 'flipdown', {
			theme: 'dark',
			headings: ['Dias', 'Horas', 'Minutos', 'Segundos'],
		}).start();
	}
});
