@props([
    'type' => '', // ['carousel', 'simple'] - Define o layout do componente;
    'banners' => [], // Lista com img, title, desc, btn, href_btn
    'banner' => null, // Item único opcional (alternativa a banners[0])
    'id' => 'site-banner', // Texto alternativo para imagens (apenas se type for 'default' e houver apenas um banner
])

@if ($type == 'simple')
    @php($item = $banner ?? ($banners[0] ?? null))
    @if ($item)
        <!-- Card com imagem, overlay e conteúdo -->
        <div
            class="relative w-100% !bc(c-border-3) b-1px-solid rounded-10px overflow-hidden h-400px md:h-320px sm:h-260px">
            <figure class="absolute t-0 l-0 w-100% h-100%">
                <img src="{{ Vite::asset($item['img']) }}"
                    @if(!empty($item['alt']))
                    alt="{{ $item['alt'] }}"
                    @endif class="dp-block w-100% h-100% fit-cover mob:obj-pos(65%,100%)" loading="lazy">
            </figure>

            <div class="absolute t-0 l-0 w-100% h-100% banner-overlay"></div>

            <div class="relative dp-flex col v-center h-left gap-20px h-100% p-50px lg:p-20px">
                <h1 class="c(c-s-1) fnt(wgt-bold) fs-32px xl:fs-29px mob:fs-26px lg:fs-22px">
                    <b>{{ $item['title'] ?? '' }}</b>
                </h1>
                @if (!empty($item['desc']))
                    <div class="dp-flex col v-center h-left gap-10px w-60%">
                        <p class="c(c-s-1) fnt(wgt-medium) fs-18px mob:fs-14px lg:fs-12px lh-1.3em t-left">
                            {!! $item['desc'] !!}
                        </p>
                    </div>
                @endif
                @if (!empty($item['btn']))
                    <a href="{{ $item['href_btn'] ?? '#' }}" target="_blank"
                        class="fnt(wgt-bold) btn-1 dark py-15px lg:py-10px px-50px lg:px-30px fs-18px lg:fs-12px hover:opacity-0.8 t-0.2s">
                        {{ $item['btn'] }}
                    </a>
                @endif
            </div>
        </div>
    @endif
<!-- Banner dinâmico -->
@elseif ($type == 'carousel')
    <div id="{{ $id }}-wrapper" class="site-banner-wrapper relative !bc(c-border-3) b-1px-solid rounded-10px overflow-hidden border-box">
        <div id="{{ $id }}" class="site-banner swiper h-400px mob:h-320px sm:h-260px w-100% overflow-hidden border-box">
            <div class="swiper-wrapper">
                @foreach ($banners as $banner)
                    <div class="swiper-slide relative h-400px mob:h-320px sm:h-260px" data-bg="{{ Vite::asset($banner['img']) }}">
                        <div class="banner-caption absolute center(v) md:center-reset md:bottom(0px) left(65px) mob:left(30px) md:left(0px) dp-flex col gap-10px mob:gap-8px sm:gap-6px px-30px mob:px-20px sm:px-15px py-30px mob:py-20px w-35% mob:w-65% sm:w-85%">
                            <h3 class="c(c-t1-200) fnt(wgt-bold) fs-32px xl:fs-29px mob:fs-26px sm:fs-20px">{{ $banner['title'] }}</h3>
                            @if (!empty($banner['desc']))
                                <p class="c(c-s-1) fnt(wgt-medium) fs-18px xl:fs-16px mob:fs-14px sm:fs-10px lh-1.4em">{!! $banner['desc'] !!}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dots (direita) -->
            <div class="swiper-pagination desktop"></div>
            <!-- Scrollbar vertical (para telas menores que 650px) -->
            <div class="swiper-scrollbar mobile"></div>

            <!-- Controles verticais (esquerda) -->
            <div class="mob:hidden nav-vertical">
                <div class="swiper-button-prev" aria-label="Anterior">
                    @svg('site.nav.about.arrow', 'w-30px h-30px f*(c-s-1)')
                </div>
                <div class="swiper-button-next" aria-label="Próximo">
                    @svg('site.nav.about.arrow', 'w-30px h-30px f*(c-s-1)')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {
                const id = @json($id);
                document.querySelectorAll(`#${id} .swiper-slide`).forEach((el) => {
                    const bg = el.getAttribute('data-bg');
                    if (bg) el.style.background = `url('${bg}') center / cover no-repeat`;
                });

                if (window.Swiper) {
                    new window.Swiper(`#${id}`, {
                        modules: [window.SwiperPagination, window.SwiperAutoplay, window.SwiperNavigation, window.SwiperScrollbar],
                        direction: 'vertical',
                        loop: true,
                        spaceBetween: 0,
                        slidesPerView: 1,
                        slidesPerGroup: 1,
                        centeredSlides: false,
                        watchOverflow: true,
                        observer: true,
                        observeParents: true,
                        pagination: {
                            el: `#${id} .swiper-pagination`,
                            clickable: true,
                        },
                        scrollbar: {
                            el: `#${id} .swiper-scrollbar`,
                            draggable: true,
                        },
                        navigation: {
                            nextEl: `#${id} .swiper-button-next`,
                            prevEl: `#${id} .swiper-button-prev`,
                        },
                        autoplay: {
                            delay: 15000, // Tempo entre transições (em ms)
                        },
                    });
                }
            });
        </script>
    @endpush
@else
    @php($item = $banner ?? ($banners[0] ?? null))
    @if ($item)
        <div class="dp-flex v-center h-left w-100% !bc(c-border-3) b-1px-solid rounded-10px">
            <figure class="w-100% h-400px mob:h-auto m-0px p-0px overflow-hidden rounded-10px">
                <img src="{{ Vite::asset($item['img']) }}"
                    @if(!empty($item['alt']))
                    alt="{{ $item['alt'] }}"
                    @endif class="dp-block w-100% h-100% fit-cover" loading="lazy">
            </figure>
        </div>
    @endif
@endif
