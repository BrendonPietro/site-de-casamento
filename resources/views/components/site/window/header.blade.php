@php
    //! MANTER após implementação. É sempre necessário ter alguma imagem rodando:
    $gallery_default = [
        'resources/images/site/introduction/placeholder/1.jpg',
        'resources/images/site/introduction/placeholder/2.jpg',
        'resources/images/site/introduction/placeholder/3.jpg',
        'resources/images/site/introduction/placeholder/4.jpg',
        'resources/images/site/introduction/placeholder/5.jpg',
        'resources/images/site/introduction/placeholder/6.jpg',
    ];

    $thumbnail_default = 'resources/images/site/header/1.jpg';

    //! Após implementação, remover variável abaixo:
    $socials_placeholder = [
        ['svg' => 'instagram', 'link' => $globalConfig->instagram],
        ['svg' => 'whatsapp', 'link' => $globalConfig->whatsapp_link],
        ['svg' => 'email', 'link' => $globalConfig->email],
    ];

    $pages_placeholder = [
        [
            'name' => 'Início', 
            'link' => route('home'), 
            'target' => '_self', 
            'route' => 'home'
        ],
        
        [
            'name' => 'Sobre Nós',
            'link' => route('about'),
            'target' => '_self',
            'route' => 'sobre'
        ],

        [
            'name' => 'Serviços',
            'link' => route('service'),
            'target' => '_self',
            'route' => 'servicos',
            'dropdown' => [
                [
                    'name' => 'Combate a Incêndio',
                    'link' => 'seguranca-contra-incendio',
                    'target' => '_self',
                    'icon' => 'fire-security'
                ],

                [
                    'name' => 'Trabalhos em Altura e Espaços Confinados',
                    'link' => 'trabalhos-em-altura-e-espaços-confinados',
                    'target' => '_self',
                    'icon' => 'high-ground'
                ],

                [
                    'name' => 'Manutenção e Infraestrutura Industrial',
                    'link' => 'manutencao-e-infraestrutura-industrial',
                    'target' => '_self',
                    'icon' => 'industrial-infrastructure'
                ],

                [
                    'name' => 'Treinamentos e Programas Ocupacionais',
                    'link' => 'treinamentos-e-programas-ocupacionais',
                    'target' => '_self',
                    'icon' => 'training-programs'
                ],

                [
                    'name' => 'Mão de Obra Especializada',
                    'link' => 'mao-de-obra-especializada',
                    'target' => '_self',
                    'icon' => 'specialized-labor'
                ],

                [
                    'name' => 'Comunicação Visual e Sinalização',
                    'link' => 'comunicacao-visual-e-sinalizacao',
                    'target' => '_self',
                    'icon' => 'visual-communication'
                ],

                [
                    'name' => 'Instalação e Manutenção de Controle de Temperatura',
                    'link' => 'instalacao-e-manutencao-de-controle-de-temperatura',
                    'target' => '_self',
                    'icon' => 'temperature-control'
                ]
            ]
        ],
        
        [
            'name' => 'Contato',
            'link' => route('contact'),
            'target' => '_self',
            'route' => 'contato'
        ],
    ];

    // Captura o segmento da URL atual e mapeia para identificador
    $currentSegment = Request::segment(1);
    $activePage = $currentSegment ?: 'home'; // Se estiver na raiz, considera 'home'
@endphp

@props([
    'logo' => 'default', // Nome do vetor da logotipo + tipografia;

    'gallery' => $gallery_default, // [image]
    'thumbnail' => $thumbnail_default, // [image]
    'socials' => $socials_placeholder, // ['svg', 'link']
    'pages' => $pages_placeholder, // ['name', 'link', 'target', 'route']

    'isFirstPage' => 0, // Parâmetro que define se é uma página inicial;
    'isBreadcrumb' => 0, // Parãmetro que define se é um breadcrumb (não compatível com 'isFirstPage');

    'pageSubtitle' => '', // Subtítulo da página;
    'pageTitle' => '', // Título da página;
])

<section id="introduction" class="relative dp-flex col nowrap v-center h-center {{ $isFirstPage ? 'h-100dvh' : 'h-80dvh mob:h-40dvh' }} w-100%">
    <nav x-data="{ currentlyActive: '{{ $activePage }}' }" id="mobile-navigation" x-bind:class="{ '!left(0dvw)': mobileMenuActive }" class="bg(c-bg-2) fixed top(0px) left(-150dvw) t-fast transition-transform h-100dvh w-100dvw pt-15dvh z-19">
        <ul class="dp-flex col v-top h-left gap-10px w-100% h-85dvh-max px-20px pb-30px overflow-y z-20">
            @foreach ($pages as $key => $page)
                <li class="w-100%">
                    <a :class="{ '!c(c-t1-500) bc(c-t1-500) fnt(wgt-bold) bb-3px-solid': currentlyActive === '{{ $page['route'] }}' }" class="c(c-s-4) fnt(wgt-regular) dp-flex row t-nowrap v-center h-left h-100% w-100% py-10px fs-18px t-0.2s hover:!c(c-t1-400)" target="{{ $page['target'] }}" href="{{ $page['link'] }}">
                        {{ $page['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <figure class="absolute bottom(40px) center(h) opacity-0.15">@svg("site.props.3", "w-300px h-auto z-19")</figure>
    </nav>

    <header class="absolute top(30px) mob:top(20px) dp-flex row nowrap v-center mob:v-stretch h-between gap-30px mob:gap-5px w-100% px-80px mob:px-20px z-20">
        <nav id="mobile-menu" class="mobile fx(bs,fx-box-shadow-1) bg(c-bg-2) rounded-100px dp-flex row v-center h-center gap-10px h-auto px-10px py-10px z-20">
            <a x-on:click="mobileMenuActive = !mobileMenuActive">@svg("site.nav.hamburguer", "f*(c-s-4) w-20px h-auto t-fast")</a>
        </nav>

        <a class="dp-flex row v-center h-center" href="{{ route('home') }}">@svg("site.logo.default-complex", "w-300px-min xl:w-100px-min xl:w-250px mob:w-190px h-auto")</a>

        <div class="desktop dp-flex row v-stretch h-right gap-20px">
            <nav x-data="{ currentlyActive: '{{ $activePage }}' }" id="navigation-menu" class="desktop bg(c-bg-2) rounded-100px dp-flex row v-center h-center gap-10px h-auto px-50px xl:px-30px">
                @foreach ($pages as $key => $page)
                    @php $hasDropdown = $page['dropdown'] ?? false; @endphp
                    <div @if($hasDropdown) x-data="dropdownMenu()" @endif @if($hasDropdown) @mouseenter="open()" @mouseleave="scheduleClose()" @endif :class="{ '!bc(c-t1-500) bb-3px-solid': currentlyActive === '{{ $page['route'] }}' }"  class="relative dp-flex row t-nowrap v-center h-center gap-10px h-100% px-15px">
                        <a :class="{ '!c(c-t1-500) fnt(wgt-bold)': currentlyActive === '{{ $page['route'] }}' }" @if($hasDropdown) @focus="open()" @keydown.escape.prevent.stop="forceClose()" @endif class="c(c-s-4) fnt(wgt-regular) dp-flex row t-nowrap v-center h-center gap-10px h-100% fs-16px xl:fs-14px t-0.2s hover:!c(c-t1-400)" target="{{ $page['target'] }}" href="{{ $page['link'] }}">
                            {{ $page['name'] }}
                            
                            @if($hasDropdown) <span class="dp-flex row v-center h-center t-0.2s rotate-0deg" :class="{ 'rotate-180deg': isOpen }">@svg('site.nav.dropdown')</span> @endif
                        </a>

                        @if($hasDropdown)
                            <nav x-cloak x-show="isOpen" @mouseenter="cancelClose()" @mouseleave="scheduleClose()" id="dropdown-{{ $key }}" class="absolute top(100%) left(0px) mt-10px w-460px z-6">
                                <ul class="bg(c-bg-2) fx(bs,fx-box-shadow-1) rounded-10px dp-flex col v-top h-left gap-10px w-100% p-20px">
                                    @foreach ($page['dropdown'] as $dKey => $dropdownItem)
                                        <li class="w-100%" x-data="{ selected: false }" @mouseenter="selected = true" @mouseleave="selected = false">
                                            <a class="bc(c-border-3) hover:!bc(c-t1-700) bg(c-bg-2) hover:bg(c-t1-500) c(c-s-4) hover:!c(c-s-1) fnt(wgt-bold) b-1px-solid rounded-10px dp-flex row nowrap v-center h-left gap-10px w-100% py-10px px-20px fs-16px xl:fs-14px t-fast" target="{{ $dropdownItem['target'] }}" href="{{ route('service.show', $dropdownItem['link']) }}"> 
                                                @if(!empty($dropdownItem['icon'])) 
                                                    <figure x-cloak x-show="!selected">@svg("site.nav.services.{$dropdownItem['icon']}.outline", 'f*(c-s-4) w-20px h-20px')</figure>
                                                    <figure x-cloak x-show="selected">@svg("site.nav.services.{$dropdownItem['icon']}.fill", 'f*(c-s-1) w-20px h-20px')</figure>
                                                @endif
                                                
                                                <span class="w-100% t-wrap lh-1.2em">{{ $dropdownItem['name'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        @endif
                    </div>
                @endforeach
            </nav>

            <nav id="social-menu" class="bg(c-bg-2) rounded-100px dp-flex row v-center h-center gap-10px px-50px xl:px-30px py-10px">
                @foreach($socials as $key => $social)
                    <figure class="hover:!f*(c-t1-600) w-30px h-30px xl:w-24px xl:h-24px t-fast"><a href="{{ $social['link'] }}" target="_blank">@svg("site.nav.social.{$social['svg']}", "f*(c-s-4) w-100% h-100% t-fast")</a></figure>
                @endforeach
            </nav>
        </div>
    </header>


    @if ($isFirstPage)
        <div id="primary-banner" class="relative h-100% w-100%">
            <div class="bg(c-surf-1) banner-item relative h-100% w-100%">
                <div class="banner-item-content absolute center(v) mob:center-reset left(80px) mob:left(0px) mob:bottom(80px) dp-flex col v-center h-left gap-30px mob:gap-20px w-50% mob:w-100% mob:px-20px z-2">
                    <h1 class="c(c-t1-050) fnt(fnt-1) fnt(wgt-black) fs-60px xl:fs-40px mob:fs-22px">A Proteção que <br class="desktop" />Sua Empresa Precisa</h1>

                    <div class="dp-flex col v-top h-left gap-15px mob:gap-5px">
                        <p class="c(c-s-1) fnt(wgt-regular) fs-18px xl:fs-16px mob:fs-12px lh-1.5em">Combinamos expertise em combate a incêndio, trabalhos em altura e espaços confinados para oferecer soluções seguras e sob medida.</p>
                        <p class="c(c-s-1) fnt(wgt-regular) fs-18px xl:fs-16px mob:fs-12px lh-1.5em">Nossa equipe certificada segue rigorosamente as normas NR e atua 24/7 com equipamentos de ponta, garantindo a integridade dos seus colaboradores e a continuidade das operações.</p>
                    </div>

                    <nav class="dp-flex row mob:col nowrap v-center h-left gap-30px mob:gap-15px">
                        <a href="{{route('about')}}" class="fnt(wgt-bold) btn-1 py-15px px-50px mob:py-10px mob:px-15px xl:fs-16px hover:opacity-0.8 t-0.2s">Saiba Mais</a>
                        <a href="{{ $globalConfig->whatsapp_link }}" target="_blank" class="fnt(wgt-bold) btn-2 py-15px px-50px mob:py-10px mob:px-15px xl:fs-16px hover:opacity-0.8 t-0.2s">Faça um Orçamento</a>
                    </nav>
                </div>

                <img src="{{ Vite::asset($thumbnail) }}" alt="Banner" class="fit-cover mob:obj-pos(65%,100%) mob:opacity-0.5 h-100% w-100%">
            </div>

            {{-- <nav id="banner-controls" class="bg(#EEEEEE66) !bc(c-border-1) b-1px-solid rounded-100px absolute center(v) right(80px) dp-flex col v-center h-center gap-15px py-20px px-15px z-2 bdfx-blur-4px group-hover:mc-pointer">
                <a class="fx(bs,fx-box-shadow-1) !bc(c-s-1) relative b-5px-solid rounded-100px w-25px h-25px remote-hover:!h-15px remote-hover:!w-15px remote-hover:!dp-flex remote-hover:!opacity-1">
                    <span class="fx(bs,fx-box-shadow-1) bg(c-s-1) absolute center(c) rounded-100px h-10px w-10px hover-ref t-0.2s"></span>
                </a>

                <a class="fx(bs,fx-box-shadow-1) !bc(c-s-1) relative b-5px-solid rounded-100px w-25px h-25px remote-hover:!h-15px remote-hover:!w-15px remote-hover:!dp-flex remote-hover:!opacity-1">
                    <span class="fx(bs,fx-box-shadow-1) bg(c-s-1) absolute center(c) rounded-100px opacity-0 h-10px w-10px hover-ref t-0.2s"></span>
                </a>
            </nav> --}}
        </div>
    @else
        <div id="primary-banner" class="relative h-100% w-100%">
            <div class="banner-item relative h-100% w-100%">
                <div class="banner-item-content absolute center(v) mob:center-reset mob:bottom(0px) mob:bg(c-grad-header-mobile) dp-flex col v-center h-left gap-30px {{ $isBreadcrumb ? 'mob:gap-0px' : 'mob:gap-5px' }} w-100% mob:w-55% sm:w-100% px-80px mob:px-20px mob:pb-20px">
                    <a href="{{ $isBreadcrumb ? route('service') : '#' }}" class="{{ $isBreadcrumb ? 'hover:!c(c-t1-400) hover:!f*(c-t1-400) mc-pointer t-0.2s' : '' }} mob:fx(ts,fx-text-shadow-2) dp-flex row v-center h-left gap-5px c(c-t1-200) fnt(wgt-medium) fs-26px mob:fs-14px lh-30px">
                        @if ($isBreadcrumb) @svg('site.nav.about.arrow', 'w-25px mob:w-15px h-25px mob:h-15px f*(c-t1-200) rotate-270deg') @endif
                        <span>{{ $pageTitle }}</span>
                    </a>
                    
                    <div class="dp-flex col v-top h-left">
                        <p class="mob:fx(ts,fx-text-shadow-2) c(c-s-1) fnt(wgt-black) fs-50px mob:fs-22px lh-50px mob:lh-1.3em">{!! $pageSubtitle !!}</p>
                    </div>
                </div>

                <img src="{{ Vite::asset($thumbnail) }}" alt="Banner" class="fit-cover mob:obj-pos(60%,100%) h-100% w-100%">
            </div>
        </div>
    @endif
</section>

@once
    @push('scripts')
        <script>
            function dropdownMenu() {
                return {
                    isOpen: false,
                    closeTimer: null,
                    open() {
                        this.cancelClose();
                        this.isOpen = true;
                    },
                    scheduleClose() {
                        this.cancelClose();
                        this.closeTimer = setTimeout(() => { this.isOpen = false; }, 180); // pequeno delay evita flicker
                    },
                    forceClose() {
                        this.cancelClose();
                        this.isOpen = false;
                    },
                    cancelClose() {
                        if (this.closeTimer) {
                            clearTimeout(this.closeTimer);
                            this.closeTimer = null;
                        }
                    }
                }
            }
        </script>
    @endpush
@endonce
