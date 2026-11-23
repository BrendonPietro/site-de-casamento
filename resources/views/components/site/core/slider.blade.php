@props([
    // Swiper doc: https://swiperjs.com/swiper-api

    'id' => 'slider', // Id do componente
    'arrows' => true, // Ativa as arrows de navegação
    'dots' => true, // Ativa os dots de paginação
    'dotsClickable' => true, // Indica se os dots são clicáveis
    'autoplay' => true, // Indica se o slider deve rodar automaticamente
    'delay' => 3000, // Indica o delay do autoplay em milissegundos
    'loop' => true, // Indica se ao chegar no último slide, o slider volta para o primeiro
    'slides' => 1, // Indica a quantidade de slides visíveis
    'group' => 1, // Indica a quantidade de slides a serem agrupados
    'spacing' => 0, // Indica o espaçamento entre os slides,
])

@php
    $plugins = [];
    if ($arrows) {
        $plugins[] = 'SwiperNavigation';
    }
    if ($dots) {
        $plugins[] = 'SwiperPagination';
    }
    if ($autoplay) {
        $plugins[] = 'SwiperAutoplay';
    }
@endphp

<!-- Slider main container -->
<div class="relative z-2 w-100%">

    <div id="{{ $id }}" class="swiper !static {{ $attributes['class'] ?? '' }}" {{ $attributes }}>
        <div class="swiper-wrapper">
            <!-- Slides -->
            {{ $slot }}
        </div>

        @if ($dots)
            <div class="swiper-pagination dp-flex row v-center h-center gap-20px"></div>
        @endif

        @if ($arrows)
            <!-- If we need navigation buttons -->
            <div class="absolute center(v) left(20px) swiper-button-prev"></div>
            <div class="absolute center(v) right(20px) swiper-button-next"></div>
        @endif

        @push('scripts')
            <script type="module">
                document.addEventListener("DOMContentLoaded", function() {
                    new Swiper('#{{ $id }}', {
                        loop: {{ $loop ? 'true' : 'false' }},
                        modules: [{{ implode(', ', $plugins) }}],
                        slidesPerView: 3,
                        slidesPerGroup: 3,

                        spaceBetween: {{ $spacing }},
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: {{ $dotsClickable ? 'true' : 'false' }},
                        },
                        autoplay: {
                            delay: {{ $delay }},
                        },

                        // Responsive breakpoints
                        breakpoints: {
                            1024: {
                                slidesPerView: {{ $slides }},
                                slidesPerGroup: {{ $group }},
                            },
                        },
                    });
                });
            </script>
        @endpush

    </div>

</div>
