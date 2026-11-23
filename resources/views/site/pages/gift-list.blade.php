@php
    $showGiftCheckout = true;
@endphp
{{-- [SISTEMA DE PRESENTES] Para habilitar, mude $showGiftCheckout para true no @php acima --}}

<x-site.app :current_page="'gift-list'">

    {{-- Dados dos presentes para Alpine.js --}}
    <script>
        window.__presentesData = @json($gifts->toArray());
        window.__giftCheckoutEnabled = @json($showGiftCheckout);
    </script>

    {{-- Cabeçalho da página --}}
    <section class="lista-presentes-hero">
        <div class="container">
            <h1 class="lista-presentes-hero__title">Lista de presentes</h1>
            <p class="lista-presentes-hero__text">
                Com muita alegria, compartilhamos com vocês a nossa lista de presentes, pensada com carinho para o nosso
                lar.
            </p>
            <p class="lista-presentes-hero__text lista-presentes-hero__text--highlight">
                Se quiser nos presentear de outra forma, saiba que cada gesto fará parte do início da nossa vida juntos
                e será lembrado com muito amor!
            </p>
        </div>
    </section>

    {{-- Filtros e ordenação --}}
    <section class="lista-presentes-page" x-data="giftList">
        <div class="container">
            <div class="lista-presentes-page__filters">
                <div class="lista-presentes-page__filter-group">
                    <label>Categoria:</label>
                    <select x-model="filtroCategoria" @change="filtrar()">
                        <option value="">Todas</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lista-presentes-page__filter-group">
                    <label>Ordenar:</label>
                    <select x-model="ordenacao" @change="filtrar()">
                        <option value="destaque">Destaques</option>
                        <option value="az">A-Z</option>
                        <option value="za">Z-A</option>
                        <option value="preco_asc">Preço: menor para maior</option>
                        <option value="preco_desc">Preço: maior para menor</option>
                    </select>
                </div>
            </div>

            <div class="lista-presentes-page__grid">
                <template x-for="presente in filtrados" :key="presente.id">
                    <div class="lista-presentes-page__item">
                        <img :src="presente.foto_url" :alt="presente.nome" class="lista-presentes-page__item-image">
                        <p class="lista-presentes-page__item-name" x-text="presente.nome"></p>

                        <template x-if="presente.cotavel !== 'S'">
                            <p class="lista-presentes-page__item-price" x-text="'R$ ' + formatCurrency(presente.valor)"></p>
                        </template>
                        <template x-if="presente.cotavel === 'S'">
                            <div>
                                <p class="lista-presentes-page__item-price"
                                    x-text="'R$ ' + formatCurrency(presente.valor_cota) + ' / cota'"></p>
                                <p class="lista-presentes-page__item-quotas">
                                    <span x-text="remainingQuotas(presente)"></span> cotas disponíveis
                                </p>
                            </div>
                        </template>

                        <template x-if="isGiftAvailable(presente)">
                            <a href="#" class="btn-primary" @click.prevent="openGiftModal(presente)">Quero Presentear</a>
                        </template>
                        <template x-if="!isGiftAvailable(presente)">
                            <a href="#" class="btn-primary btn-primary--unavailable">Já presenteado</a>
                        </template>
                    </div>
                </template>
            </div>

            <div class="lista-presentes-page__pagination" x-show="totalPages > 1">
                <template x-for="page in totalPages" :key="page">
                    <button class="lista-presentes-page__page-btn" :class="{ 'active': currentPage === page }"
                        @click="goToPage(page)" x-text="page">
                    </button>
                </template>
                <button class="lista-presentes-page__page-btn" @click="goToPage(totalPages)"
                    x-show="currentPage < totalPages">
                    Última página
                </button>
            </div>
        </div>
        {{-- Modal --}}
        <div class="gift-modal" x-show="showGiftModal" x-cloak>
            <div class="gift-modal__backdrop" @click="closeGiftModal()"></div>

            <div class="gift-modal__content gift-modal__content--elegant">
                <button type="button" class="gift-modal__close" @click="closeGiftModal()">×</button>

                <template x-if="selectedGift">
                    <div class="gift-modal__layout">
                        {{-- Topo --}}
                        <div class="gift-modal__header">
                            <div class="gift-modal__icon">
                                <svg width="22" height="22" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M44 24V40C44 41.0609 43.5786 42.0783 42.8284 42.8284C42.0783 43.5786 41.0609 44 40 44H8C6.93913 44 5.92172 43.5786 5.17157 42.8284C4.42143 42.0783 4 41.0609 4 40V24C3.46957 24 2.96086 23.7893 2.58579 23.4142C2.21071 23.0391 2 22.5304 2 22V16C2 14.9391 2.42143 13.9217 3.17157 13.1716C3.92172 12.4214 4.93913 12 6 12H12.34C12.114 11.3575 11.999 10.6811 12 10C12 8.4087 12.6321 6.88258 13.7574 5.75736C14.8826 4.63214 16.4087 4 18 4C20 4 21.76 5 22.86 6.48V6.46L24 8L25.14 6.46V6.48C26.24 5 28 4 30 4C31.5913 4 33.1174 4.63214 34.2426 5.75736C35.3679 6.88258 36 8.4087 36 10C36.001 10.6811 35.886 11.3575 35.66 12H42C43.0609 12 44.0783 12.4214 44.8284 13.1716C45.5786 13.9217 46 14.9391 46 16V22C46 22.5304 45.7893 23.0391 45.4142 23.4142C45.0391 23.7893 44.5304 24 44 24ZM8 40H22V24H8V40ZM40 40V24H26V40H40ZM18 8C17.4696 8 16.9609 8.21071 16.5858 8.58579C16.2107 8.96086 16 9.46957 16 10C16 10.5304 16.2107 11.0391 16.5858 11.4142C16.9609 11.7893 17.4696 12 18 12C18.5304 12 19.0391 11.7893 19.4142 11.4142C19.7893 11.0391 20 10.5304 20 10C20 9.46957 19.7893 8.96086 19.4142 8.58579C19.0391 8.21071 18.5304 8 18 8ZM30 8C29.4696 8 28.9609 8.21071 28.5858 8.58579C28.2107 8.96086 28 9.46957 28 10C28 10.5304 28.2107 11.0391 28.5858 11.4142C28.9609 11.7893 29.4696 12 30 12C30.5304 12 31.0391 11.7893 31.4142 11.4142C31.7893 11.0391 32 10.5304 32 10C32 9.46957 31.7893 8.96086 31.4142 8.58579C31.0391 8.21071 30.5304 8 30 8ZM6 16V20H22V16H6ZM26 16V20H42V16H26Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <h3 class="gift-modal__title">Presentear com carinho</h3>
                            <p class="gift-modal__subtitle">
                                Obrigado por fazer parte do início da nossa história.
                            </p>
                        </div>

                        {{-- Conteúdo principal --}}
                        <div class="gift-modal__main">
                            {{-- Coluna esquerda: card do presente --}}
                            <div class="gift-modal__gift-card">
                                <div class="gift-modal__gift-image-wrap">
                                    <img :src="selectedGift.foto_url" :alt="selectedGift.nome"
                                        class="gift-modal__gift-image">
                                </div>

                                <div class="gift-modal__gift-info">
                                    <h4 class="gift-modal__gift-name" x-text="selectedGift.descricao || selectedGift.nome"></h4>

                                    <p class="gift-modal__gift-description"
                                        x-text="({'Eletrodomésticos': 'Para deixar nosso lar ainda mais aconchegante', 'Eletrodoméstico': 'Para deixar nosso lar ainda mais aconchegante', 'Cozinha': 'Para os nossos momentos especiais à mesa', 'Louças': 'Para receber amigos e família com carinho', 'Louça': 'Para receber amigos e família com carinho', 'Limpeza': 'Para cuidar com carinho do nosso cantinho', 'Decoração': 'Para transformar nossa casa em um verdadeiro lar', 'Lua de mel': 'Para tornar nossa viagem ainda mais inesquecível', 'Geral': 'Um presente escolhido com carinho para o início do nosso lar'})[selectedGift.categoria] || 'Um presente escolhido com carinho para o início do nosso lar'"></p>

                                    <p class="gift-modal__gift-price">
                                        R$ <span x-text="formatCurrency(selectedGift.valor)"></span>
                                    </p>

                                    <template x-if="selectedGift.cotavel === 'S'">
                                        <div class="gift-modal__quota-box">
                                            <div class="gift-modal__quota-top">
                                                <span>Cotas disponíveis</span>
                                                <strong x-text="remainingQuotas(selectedGift)"></strong>
                                            </div>

                                            <label class="gift-modal__label">
                                                Quantidade de cotas
                                            </label>

                                            <div class="gift-modal__stepper">
                                                <button type="button" class="gift-modal__stepper-btn"
                                                    @click="form.quantity = Math.max(1, Number(form.quantity) - 1)">−</button>
                                                <span class="gift-modal__stepper-value"
                                                    x-text="form.quantity + (form.quantity == 1 ? ' cota' : ' cotas') + ' • R$ ' + formatCurrency(selectedGift.valor_cota)"></span>
                                                <button type="button" class="gift-modal__stepper-btn"
                                                    @click="form.quantity = Math.min(remainingQuotas(selectedGift), Number(form.quantity) + 1)">+</button>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="gift-modal__security-box">
                                        <svg width="15" height="15" viewBox="0 0 48 48" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M42.5 32.0002C42.5 29.1298 42.4971 27.1272 42.2939 25.6164C42.0966 24.1487 41.7349 23.3722 41.1816 22.8195L41.1807 22.8176C40.6281 22.2645 39.8512 21.9036 38.3838 21.7063C36.873 21.5031 34.8704 21.5002 32 21.5002H16C13.1296 21.5002 11.127 21.5031 9.61621 21.7063C8.14882 21.9036 7.37195 22.2645 6.81934 22.8176L6.81836 22.8195C6.26508 23.3722 5.90345 24.1487 5.70606 25.6164C5.50288 27.1272 5.5 29.1298 5.5 32.0002C5.5 34.8705 5.50288 36.8733 5.70606 38.384C5.87877 39.6679 6.1768 40.4232 6.61914 40.9621L6.81836 41.1809L6.81934 41.1819C7.37196 41.7351 8.14863 42.0958 9.61621 42.2932C11.127 42.4964 13.1296 42.5002 16 42.5002H22C22.8284 42.5002 23.5 43.1718 23.5 44.0002C23.4999 44.8285 22.8284 45.5002 22 45.5002H16C13.2145 45.5002 10.974 45.5031 9.2168 45.2668C7.41656 45.0247 5.89964 44.5067 4.69629 43.302V43.301C3.49298 42.098 2.97538 40.5827 2.7334 38.7834C2.49709 37.0262 2.5 34.7857 2.5 32.0002C2.5 29.2148 2.49714 26.9742 2.7334 25.217C2.97543 23.4174 3.49243 21.9006 4.69629 20.6975C5.89964 19.4928 7.41656 18.9747 9.2168 18.7326C9.61893 18.6786 10.0465 18.6386 10.5 18.6067V16.0002C10.5 15.2374 10.5624 14.4866 10.6875 13.7492C10.8261 12.9325 11.6003 12.3821 12.417 12.5207C13.2337 12.6594 13.7832 13.4344 13.6445 14.2512C13.5484 14.8177 13.5 15.4005 13.5 16.0002V18.507C14.2793 18.4998 15.1119 18.5002 16 18.5002H32C32.8881 18.5002 33.7207 18.4998 34.5 18.507V16.0002C34.5001 13.8676 33.851 11.7851 32.6387 10.0305C31.4263 8.27594 29.7078 6.93211 27.7129 6.17795C25.7178 5.42382 23.5397 5.29493 21.4697 5.80881C19.4 6.32273 17.5356 7.45449 16.125 9.05393C15.577 9.67525 14.6291 9.73469 14.0078 9.18674C13.3866 8.63877 13.3271 7.69082 13.875 7.06955C15.6887 5.01303 18.0858 3.55741 20.7471 2.8967C23.4085 2.236 26.2084 2.40171 28.7734 3.37131C31.3384 4.3409 33.5475 6.0685 35.1064 8.32443C36.6654 10.5804 37.5002 13.258 37.5 16.0002V18.6067C37.9535 18.6386 38.3811 18.6786 38.7832 18.7326C40.5834 18.9747 42.1004 19.4928 43.3037 20.6975H43.3027C44.5069 21.9007 45.0245 23.4171 45.2666 25.217C45.5029 26.9742 45.5 29.2148 45.5 32.0002C45.5 34.7857 45.5029 37.0262 45.2666 38.7834C45.0246 40.5829 44.5064 42.0979 43.3027 43.301L43.3037 43.302C42.1004 44.5067 40.5834 45.0247 38.7832 45.2668C37.026 45.5031 34.7855 45.5002 32 45.5002H30C29.1717 45.5002 28.5001 44.8285 28.5 44.0002C28.5 43.1718 29.1716 42.5002 30 42.5002H32C34.8704 42.5002 36.873 42.4964 38.3838 42.2932C39.8514 42.0958 40.628 41.7351 41.1807 41.1819L41.1816 41.1809C41.7349 40.6283 42.0965 39.8515 42.2939 38.384C42.4971 36.8733 42.5 34.8705 42.5 32.0002Z"/>
                                        </svg>
                                        <div>
                                            <strong>Pagamento seguro via Mercado Pago</strong>
                                            <span>Seus dados protegidos durante toda a transação.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Coluna direita: formulário --}}
                            <div class="gift-modal__form-side">
                                <form @submit.prevent="reserveGift()" class="gift-modal__form">
                                    {{-- Valor / contribuição --}}
                                    <template x-if="selectedGift.cotavel === 'N'">
                                        <div class="gift-modal__field">
                                            <label class="gift-modal__label">Valor do presente</label>
                                            <div class="gift-modal__price-highlight">
                                                R$ <span x-text="formatCurrency(selectedGift.valor)"></span>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="selectedGift.cotavel === 'S'">
                                        <div class="gift-modal__field">
                                            <label class="gift-modal__label">Sua contribuição</label>
                                            <div class="gift-modal__price-highlight">
                                                R$
                                                <span
                                                    x-text="formatCurrency((Number(selectedGift.valor_cota || 0) * Number(form.quantity || 1)))"></span>
                                            </div>
                                            <p class="gift-modal__helper">
                                                Cada contribuição faz parte do início da nossa história
                                            </p>
                                        </div>
                                    </template>

                                    <div class="gift-modal__field">
                                        <label for="guest_name" class="gift-modal__label">Seu nome</label>
                                        <div class="gift-modal__input-wrap">
                                            <svg width="16" height="16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M18.35 21.65C16.7833 20.0833 16 18.2 16 16C16 13.8 16.7833 11.9167 18.35 10.35C19.9167 8.78333 21.8 8 24 8C26.2 8 28.0833 8.78333 29.65 10.35C31.2167 11.9167 32 13.8 32 16C32 18.2 31.2167 20.0833 29.65 21.65C28.0833 23.2167 26.2 24 24 24C21.8 24 19.9167 23.2167 18.35 21.65ZM8 40V34.4C8 33.2667 8.292 32.2253 8.876 31.276C9.46 30.3267 10.2347 29.6013 11.2 29.1C13.2667 28.0667 15.3667 27.292 17.5 26.776C19.6333 26.26 21.8 26.0013 24 26C26.2 25.9987 28.3667 26.2573 30.5 26.776C32.6333 27.2947 34.7333 28.0693 36.8 29.1C37.7667 29.6 38.542 30.3253 39.126 31.276C39.71 32.2267 40.0013 33.268 40 34.4V40H8ZM12 36H36V34.4C36 34.0333 35.9087 33.7 35.726 33.4C35.5433 33.1 35.3013 32.8667 35 32.7C33.2 31.8 31.3833 31.1253 29.55 30.676C27.7167 30.2267 25.8667 30.0013 24 30C22.1333 29.9987 20.2833 30.224 18.45 30.676C16.6167 31.128 14.8 31.8027 13 32.7C12.7 32.8667 12.458 33.1 12.274 33.4C12.09 33.7 11.9987 34.0333 12 34.4V36ZM26.826 18.826C27.6087 18.042 28 17.1 28 16C28 14.9 27.6087 13.9587 26.826 13.176C26.0433 12.3933 25.1013 12.0013 24 12C22.8987 11.9987 21.9573 12.3907 21.176 13.176C20.3947 13.9613 20.0027 14.9027 20 16C19.9973 17.0973 20.3893 18.0393 21.176 18.826C21.9627 19.6127 22.904 20.004 24 20C25.096 19.996 26.038 19.6047 26.826 18.826Z" fill="currentColor"/>
                                            </svg>
                                            <input type="text" id="guest_name" x-model="form.guest_name"
                                                class="gift-modal__input" placeholder="Ex: João Silva" required>
                                        </div>
                                    </div>

                                    <div class="gift-modal__field">
                                        <label for="guest_email" class="gift-modal__label">Seu e-mail</label>
                                        <div class="gift-modal__input-wrap">
                                            <svg width="16" height="16" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M30.7371 0.448319C31.5226 -0.192445 32.6816 -0.14617 33.4139 0.586014L41.4139 8.58504C41.7889 8.96009 41.9998 9.46869 41.9998 9.9991C41.9998 10.5295 41.7889 11.0381 41.4139 11.4132L13.4139 39.4132C13.1348 39.6923 12.7794 39.8826 12.3924 39.96L2.39239 41.96C1.73668 42.0912 1.0586 41.886 0.585754 41.4132C0.112907 40.9403 -0.0922655 40.2622 0.0388786 39.6065L2.03888 29.6065L2.07306 29.463C2.1652 29.1318 2.34152 28.8293 2.58575 28.585L30.5858 0.586014L30.7371 0.448319ZM4.54864 37.4493L8.77618 36.6036L5.39435 33.2218L4.54864 37.4493ZM11.3279 33.4991L12.4998 34.671L32.1717 14.9991L30.9998 13.8272L11.3279 33.4991ZM7.32794 29.4991L8.49982 30.671L28.1717 10.9991L26.9998 9.82723L7.32794 29.4991ZM29.8279 6.9991L34.9998 12.171L37.1707 9.9991L31.9998 4.82723L29.8279 6.9991Z" fill="currentColor"/>
                                            </svg>
                                            <input type="email" id="guest_email" x-model="form.guest_email"
                                                class="gift-modal__input" placeholder="Ex: joao@email.com">
                                        </div>
                                    </div>

                                    <div class="gift-modal__field">
                                        <label for="message" class="gift-modal__label">Deixe uma mensagem
                                            (opcional)</label>
                                        <div class="gift-modal__input-wrap">
                                            <svg width="16" height="16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M44 24V40C44 41.0609 43.5786 42.0783 42.8284 42.8284C42.0783 43.5786 41.0609 44 40 44H8C6.93913 44 5.92172 43.5786 5.17157 42.8284C4.42143 42.0783 4 41.0609 4 40V24C3.46957 24 2.96086 23.7893 2.58579 23.4142C2.21071 23.0391 2 22.5304 2 22V16C2 14.9391 2.42143 13.9217 3.17157 13.1716C3.92172 12.4214 4.93913 12 6 12H12.34C12.114 11.3575 11.999 10.6811 12 10C12 8.4087 12.6321 6.88258 13.7574 5.75736C14.8826 4.63214 16.4087 4 18 4C20 4 21.76 5 22.86 6.48V6.46L24 8L25.14 6.46V6.48C26.24 5 28 4 30 4C31.5913 4 33.1174 4.63214 34.2426 5.75736C35.3679 6.88258 36 8.4087 36 10C36.001 10.6811 35.886 11.3575 35.66 12H42C43.0609 12 44.0783 12.4214 44.8284 13.1716C45.5786 13.9217 46 14.9391 46 16V22C46 22.5304 45.7893 23.0391 45.4142 23.4142C45.0391 23.7893 44.5304 24 44 24ZM8 40H22V24H8V40ZM40 40V24H26V40H40ZM18 8C17.4696 8 16.9609 8.21071 16.5858 8.58579C16.2107 8.96086 16 9.46957 16 10C16 10.5304 16.2107 11.0391 16.5858 11.4142C16.9609 11.7893 17.4696 12 18 12C18.5304 12 19.0391 11.7893 19.4142 11.4142C19.7893 11.0391 20 10.5304 20 10C20 9.46957 19.7893 8.96086 19.4142 8.58579C19.0391 8.21071 18.5304 8 18 8ZM30 8C29.4696 8 28.9609 8.21071 28.5858 8.58579C28.2107 8.96086 28 9.46957 28 10C28 10.5304 28.2107 11.0391 28.5858 11.4142C28.9609 11.7893 29.4696 12 30 12C30.5304 12 31.0391 11.7893 31.4142 11.4142C31.7893 11.0391 32 10.5304 32 10C32 9.46957 31.7893 8.96086 31.4142 8.58579C31.0391 8.21071 30.5304 8 30 8ZM6 16V20H22V16H6ZM26 16V20H42V16H26Z" fill="currentColor"/>
                                            </svg>
                                            <textarea id="message" rows="4" x-model="form.message" class="gift-modal__textarea" maxlength="2000"
                                                placeholder="Ex: Com muito carinho, desejamos uma vida linda para vocês!"></textarea>
                                        </div>
                                    </div>

                                    <div x-show="errorMessage" class="gift-modal__alert gift-modal__alert--error"
                                        x-text="errorMessage">
                                    </div>

                                    <div x-show="successMessage" class="gift-modal__alert gift-modal__alert--success"
                                        x-text="successMessage">
                                    </div>

                                    <div x-data="{ showComingSoon: false }">
                                        <div class="gift-modal__footer">
                                            @if ($showGiftCheckout)
                                                <button type="submit" class="gift-modal__submit" :disabled="loading">
                                                    <span x-show="!loading">Presentear</span>
                                                    <span x-show="loading">Reservando...</span>
                                                </button>
                                            @else
                                                <button type="button" class="gift-modal__submit" @click="showComingSoon = true">
                                                    Presentear
                                                </button>
                                            @endif
                                        </div>

                                        @if ($showGiftCheckout)
                                            <p class="gift-modal__tip">*Caso o pagamento não abra corretamente, tente em uma aba anônima.</p>
                                        @endif

                                        {{-- Coming Soon Popup --}}
                                        <div class="coming-soon-popup" x-show="showComingSoon" x-cloak>
                                            <div class="coming-soon-popup__backdrop" @click="showComingSoon = false"></div>
                                            <div class="coming-soon-popup__box">
                                                <h3 class="coming-soon-popup__title">Estamos finalizando os últimos detalhes</h3>
                                                <p class="coming-soon-popup__text">
                                                    Nosso sistema de presentes estará disponível em breve.<br>
                                                    Estamos preparando uma experiência segura e especial para vocês.
                                                </p>
                                                <p class="coming-soon-popup__note">
                                                    Obrigado pelo carinho e por fazer parte desse momento.
                                                </p>
                                                <button type="button" class="coming-soon-popup__btn" @click="showComingSoon = false">Entendi</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Rodapé decorativo --}}
                        <div class="gift-modal__bottom-note">
                            Obrigado por fazer parte desse momento.
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
</x-site.app>
