@php
    $services_default = [
        [
            'image' => 'resources/images/site/services/seguranca-contra-incendio.jpg',
            'icon' => 'fire-security', // Nome do Grupo SVG;
            'title' => 'Segurança<br/>Contra Incêndio',
            'slug' => 'seguranca-contra-incendio',
            'description' => 'Projetos e instalação de sistemas de combate a incêndio com garantia e conformidade.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],

        [
            'image' => 'resources/images/site/services/trabalhos-em-altura-e-espaços-confinados.jpg',
            'icon' => 'high-ground', // Nome do Grupo SVG;
            'title' => 'Trabalhos em Altura e<br/>Espaços Confinados',
            'slug' => 'trabalhos-em-altura-e-espaços-confinados',
            'description' => 'Acesso por corda, linhas de vida e manutenção segura em áreas de difícil acesso.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],

        [
            'image' => 'resources/images/site/services/manutencao-e-infraestrutura-industrial.jpg',
            'icon' => 'industrial-infrastructure', // Nome do Grupo SVG;
            'title' => 'Manutenção e<br/>Infraestrutura Industrial',
            'slug' => 'manutencao-e-infraestrutura-industrial',
            'description' => 'Infraestrutura elétrica, reparos e serviços que garantem eficiência e durabilidade.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],

        [
            'image' => 'resources/images/site/services/treinamentos-e-programas-ocupacionais.jpg',
            'icon' => 'training-programs', // Nome do Grupo SVG;
            'title' => 'Treinamentos e<br/>Programas Ocupacionais',
            'slug' => 'treinamentos-e-programas-ocupacionais',
            'description' => 'Cursos e laudos em NRs para equipes mais seguras, qualificadas e em conformidade.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],

        [
            'image' => 'resources/images/site/services/mao-de-obra-especializada.jpg',
            'icon' => 'specialized-labor', // Nome do Grupo SVG;
            'title' => 'Mão de Obra<br/>Especializada',
            'slug' => 'mao-de-obra-especializada',
            'description' => 'Alpinistas, técnicos e resgatistas treinados para atuar em altura e espaços de risco.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],

        [
            'image' => 'resources/images/site/services/comunicacao-visual-e-sinalizacao.jpg',
            'icon' => 'visual-communication', // Nome do Grupo SVG;
            'title' => 'Comunicação Visual<br/>e Sinalização',
            'slug' => 'comunicacao-visual-e-sinalizacao',
            'description' => 'Totens, fachadas e sinalização personalizada com qualidade e impacto visual.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],
        
        [
            'image' => 'resources/images/site/services/instalação-e-manutenção-de-controle-de-temperatura.jpg',
            'icon' => 'temperature-control', // Nome do Grupo SVG;
            'title' => 'Instalação e Manutenção<br/>de Controle de Temperatura',
            'slug' => 'instalacao-e-manutencao-de-controle-de-temperatura',
            'description' => 'Conforto térmico com segurança técnica, qualidade do ar e eficiência operacional.',
            'selected' => false, // Define se o serviço está selecionado (true/false);
        ],
    ];
@endphp

@props([
    'type' => 'default', // ['banner', 'default'] - Define o layout do componente;
    'services' => $services_default, // ['image', 'icon', 'title', 'description']
])

@if ($type == 'banner')
    <section id="services" class="bg(c-bg-2) dp-flex col v-top h-center w-100% gap-30px p-80px mob:p-20px">
        <div class="title dp-flex col v-center h-center gap-15px">
            <h2 class="c(c-s-2) fnt(wgt-medium) fs-26px mob:fs-20px t-center">Produtos & Serviços</h2>
            <h1 class="c(c-t1-600) fnt(wgt-black) fs-50px mob:fs-30px t-center">Serviços em Segurança do Trabalho, <br class="desktop"/>Manutenções e Alpinismo Industrial</h1>
        </div>

        <div class="services-swiper-shell relative w-100%">
            <div id="services-banner" class="relative swiper w-100% !pb-60px">
                <div class="swiper-wrapper">
                    @foreach ($services as $index => $service)
                        <div class="swiper-slide">
                            <div x-data="{ selected: {{ $service['selected'] ? 'true' : 'false' }} }" @mouseenter="selected = true" @mouseleave="selected = false" class="service-item rounded-10px dp-flex col v-top h-center gap-70px w-100% p-10px pb-20px overflow-y-hidden mc-pointer">
                                <a href="{{ !empty($service['routeName']) ? route($service['routeName'], $service['routeParams'] ?? []) : route('service.show', $service['slug']) }}" class="w-100% dp-flex col v-top h-center gap-70px" style="text-decoration: none; color: inherit;">
                                    <figure class="relative w-100%">
                                        <img class="bc(c-t1-600) b-1px-solid rounded-5px w-100% h-200px fit-cover-center" src="{{ Vite::asset($service['image']) }}" alt="{{ $service['title'] }}">
                                        <div x-bind:class="{ 'bg(c-bg-2) !bc(c-t1-600) c(c-t1-600)': !selected, 'bg(c-t1-600) !bc(c-t1-700) c(c-s-1)': selected }" class="service-icon absolute center(h) bottom(-50px) b-3px-solid rounded-100px dp-flex row v-center h-center gap-10px w-100px h-100px p-10px">
                                            <span x-show="!selected" x-cloak>
                                                @svg('site.nav.services.' . $service['icon'] . '.outline', 'w-70px h-70px')
                                            </span>
                                            <span x-show="selected" x-cloak>
                                                @svg('site.nav.services.' . $service['icon'] . '.fill', 'w-70px h-70px')
                                            </span>
                                        </div>
                                    </figure>

                                    <div class="details dp-flex col v-top h-center gap-20px w-100% px-10px">
                                        <h1 class="c(c-t1-600) fnt(wgt-bold) w-100% fs-20px xl:fs-18px t-center">{!! $service['title'] !!}</h1>
                                        <hr class="b-0px h-1px w-60% opacity-0.8 m-0px"/>
                                        <p class="c(c-s-3) fnt(wgt-regular) w-100% fs-16px xl:fs-14px t-center lh-1.2em">{{ $service['description'] }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Pagination -->
                <div class="swiper-pagination absolute bottom(20px) center(h) dp-flex row v-center h-center gap-15px"></div>
            </div>

            <!-- Navigation colocada FORA do container interno -->
            <button type="button" class="desktop btt-prev services-swiper-button absolute center(v) left(-65px) bg(c-bg-2) bc(transparent) dp-flex row v-center h-center w-50px h-50px" aria-label="Anterior">
                @svg("site.nav.arrow", "w-50px h-50px f*(c-s-2) hover:!f*(c-t1-600) t-fast mc-pointer")
            </button>

            <button type="button" class="desktop btt-next services-swiper-button absolute center(v) right(-65px) bg(c-bg-2) bc(transparent) dp-flex row v-center h-center w-50px h-50px" aria-label="Próximo">
                @svg("site.nav.arrow", "w-50px h-50px rotate-180deg f*(c-s-2) hover:!f*(c-t1-600) t-fast mc-pointer")
            </button>
        </div>
    </section>

    @push('scripts')
        <script type="module">
            /**
             * Swiper initialization for #services-banner
             * Problema das setas não funcionando normalmente está ligado a:
             *  1. Falta de modules (Navigation/Pagination/Autoplay) na config com build modular
             *  2. Duplicidade de instâncias (reinicialização antes do DOM pronto)
             *  3. Selectors não encontrando elementos (escopo ou id incorreto)
             */
            const initServicesBannerSwiper = () => {
                const el = document.querySelector('#services-banner');
                if (!el || el.__swiper) return; // evita reinicializações múltiplas

                // Detecta se os modules estão disponíveis (build modular)
                const hasModular = typeof window.Swiper !== 'undefined' && typeof window.SwiperNavigation !== 'undefined';

                const config = {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    centeredSlides: false,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '#services-banner .swiper-pagination',
                        clickable: true,
                        renderBullet: function (index, className) {
                            return '<span class="' + className + '"></span>';
                        },
                    },
                    navigation: {
                        nextEl: '.services-swiper-shell > .btt-next',
                        prevEl: '.services-swiper-shell > .btt-prev',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 25,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1280: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                    },
                };

                // Se build modular, adicionar modules explicitamente
                if (hasModular) {
                    config.modules = [window.SwiperNavigation, window.SwiperPagination, window.SwiperAutoplay];
                }

                const instance = new Swiper('#services-banner', config);
                el.__swiper = instance;

                // ---- Animated Bullet Indicator (Moving Ring) ----
                try {
                    const pag = el.querySelector('.swiper-pagination');
                    if (pag && !pag.querySelector('.swiper-pagination-indicator')) {
                        const indicator = document.createElement('span');
                        indicator.className = 'swiper-pagination-indicator mob:opacity-0';
                        pag.appendChild(indicator);

                        const updateIndicator = () => {
                            const active = pag.querySelector('.swiper-pagination-bullet-active');
                            if (!active) return;
                            const pagRect = pag.getBoundingClientRect();
                            const actRect = active.getBoundingClientRect();
                            const sizeVar = getComputedStyle(el).getPropertyValue('--bullet-indicator-size').trim();
                            const size = parseFloat(sizeVar || '15');
                            const left = (actRect.left - pagRect.left) + (actRect.width - size) / 2;
                            const top = (actRect.top - pagRect.top) + (actRect.height - size) / 2;
                            indicator.style.width = size + 'px';
                            indicator.style.height = size + 'px';
                            indicator.style.transform = `translate(${left}px, ${top}px)`;
                        };

                        // Inicializa após pequeno delay para garantir layout
                        setTimeout(updateIndicator, 30);
                        instance.on('slideChangeTransitionStart', updateIndicator);
                        window.addEventListener('resize', () => requestAnimationFrame(updateIndicator));
                    }
                } catch (e) { /* silencioso */ }
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initServicesBannerSwiper);
            } else {
                initServicesBannerSwiper();
            }

            // Fallback caso assets carreguem atrasados
            setTimeout(() => {
                const el = document.querySelector('#services-banner');
                if (el && !el.__swiper && typeof window.Swiper !== 'undefined') {
                    initServicesBannerSwiper();
                }
            }, 1500);
        </script>
    @endpush

@elseif($type == 'default')
    <section id="services" class="bg(c-bg-2) dp-flex col v-top h-left w-100%">
        @php
            // Verifica se estamos em uma página de serviço específico
            $currentServiceSlug = request()->route('slug');
            $isServicePage = !empty($currentServiceSlug);
            $gridClasses = $isServicePage ? 'col-3 mob:col-2 lg:col-2 sm:col-1' : 'col-4 mob:col-3 lg:col-2 sm:col-1';
        @endphp

        <nav id="services-banner" class="dp-grid {{ $gridClasses }} gap-x-10px gap-y-30px mob:gap-y-15px w-100%">
            @foreach ($services as $index => $service)
                {{-- Pula o serviço atual baseado no slug da URL --}}
                @if (request()->route('slug') === $service['slug'])
                    @continue
                @endif

                <div x-data="{ selected: {{ $service['selected'] ? 'true' : 'false' }} }" @mouseenter="selected = true" @mouseleave="selected = false" class="service-item rounded-10px dp-flex col v-top h-center gap-70px w-100% p-10px pb-20px overflow-y-hidden mc-pointer">
                    @if (!empty($service['routeName']) || !empty($service['slug']))
                        <a href="{{ !empty($service['routeName']) ? route($service['routeName'], $service['routeParams'] ?? []) : route('service.show', $service['slug']) }}" class="w-100% dp-flex col v-top h-center gap-70px" style="text-decoration: none; color: inherit;">
                    @endif

                    <figure class="relative w-100%">
                        <img class="!bc(c-t1-600) b-1px-solid rounded-5px w-100% h-200px fit-cover-center"
                            src="{{ Vite::asset($service['image']) }}" alt="{{ $service['title'] }}">
                        <div x-bind:class="{ 'bg(c-bg-2) !bc(c-t1-600) c(c-t1-600)': !selected, 'bg(c-t1-600) !bc(c-t1-700) c(c-s-1)': selected }" class="service-icon absolute center(h) bottom(-50px) b-3px-solid rounded-100px dp-flex row v-center h-center gap-10px w-100px h-100px p-10px">
                            <span x-show="!selected" x-cloak>
                                @svg('site.nav.services.' . $service['icon'] . '.outline', 'w-70px h-70px')
                            </span>
                            <span x-show="selected" x-cloak>
                                @svg('site.nav.services.' . $service['icon'] . '.fill', 'w-70px h-70px')
                            </span>
                        </div>
                    </figure>

                    <div class="details dp-flex col v-top h-center gap-20px w-100% px-10px">
                        <h1 class="c(c-t1-600) fnt(wgt-bold) w-100% fs-20px mob:fs-18px t-center">{!! $service['title'] !!}
                        </h1>
                        <hr class="b-0px h-1px w-60% opacity-0.8 m-0px" />
                        <p class="c(c-s-3) fnt(wgt-regular) w-100% fs-16px mob:fs-14px t-center lh-1.2em">
                            {{ $service['description'] }}</p>
                    </div>

                    @if (!empty($service['routeName']) || !empty($service['slug']))
                        </a>
                    @endif
                </div>
            @endforeach
        </nav>
    </section>

@endif

<style>
    [x-cloak]{display:none!important}
    .service-icon svg [fill]:not([fill="none"]){fill:currentColor!important}
    .service-icon svg [stroke]:not([stroke="none"]){stroke:currentColor!important}

    #services-banner {
        --bullet-indicator-size: 15px;

        @media screen and (max-width: 1023px) {
            --bullet-indicator-size: 5px;
        }
    }

    #services-banner .swiper-pagination-bullet {
        background: var(--c-bg-2);
        border: 3px solid var(--c-t1-600);
        transition: var(--t-fast);
        cursor: var(--mc-pointer);

        border-radius:100px;
        position: relative;
        width: 25px;
        height: 25px;
        opacity: 1;
        z-index: 2;

        @media screen and (max-width: 1023px) {
            width: 15px;
            height: 15px;
        }
    }
    #services-banner .swiper-pagination-bullet:after {
        background: var(--c-t1-600);
        transition: var(--t-fast);

        content: "";
        border-radius: 100px;
        position: absolute;
        width: 0;
        height: 0;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    #services-banner .swiper-pagination-bullet:hover:after {
        width: 20px;
        height: 20px;

        @media screen and (max-width: 1023px) {
            width: 10px;
            height: 10px;
        }
    }

    #services-banner .swiper-pagination-bullet-active:after {
        width:15px;
        height:15px;

        @media screen and (max-width: 1023px) {
            width: 10px;
            height: 10px;
        }
    }

    #services-banner .swiper-pagination-indicator {
        background: var(--c-t1-600);
        border: 3px solid var(--c-t1-600);
        width: var(--bullet-indicator-size);
        height: var(--bullet-indicator-size);

        border-radius: 100px;
        position: absolute;
        box-sizing: border-box;
        top: 0;
        left: 0;
        pointer-events: none;
        animation: bulletPulse 5s ease-in-out infinite;
        transition: transform .55s cubic-bezier(.65,.15,.25,1);
        will-change: transform;
        z-index: 1;
    }
    @keyframes bulletPulse { 0%,60%{box-shadow:0 0 0 0 rgba(59,132,188,0)} 70%{box-shadow:0 0 0 4px rgba(59,132,188,.12)} 100%{box-shadow:0 0 0 0 rgba(59,132,188,0)} }
</style>
