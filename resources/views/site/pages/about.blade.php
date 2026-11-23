@php
    $banners = [
        [
            'img'   => 'resources/images/site/banner/1.jpg',
            'title' => 'Confiabilidade',
            'desc' => 'É um dos atributos indispensáveis à consultoria na área de estratégia, em razão de passarmos a ter conhecimento aprofundado do seu negócio, das suas dores e das ações futuras.',
        ],
        [
            'img' => 'resources/images/site/banner/2.jpg',
            'title' => 'Experiência',
            'desc' => 'Um dos pré-requisitos da consultoria de estratégia é ter experiência. Temos um portfólio que poucas empresas no mercado possuem, devido à nossa experiência nacional.',
        ],
        [
            'img' => 'resources/images/site/banner/3.jpg',
            'title' => 'Implantação',
            'desc' => 'Não nos limitamos a entregar apenas relatórios: participamos do gerenciamento, do monitoramento e da implantação do Planejamento Estratégico. A falta desse acompanhamento leva os planejamentos a não terem a eficácia esperada.',
        ],
        [
            'img' => 'resources/images/site/banner/4.jpg',
            'title' => 'Ética',
            'desc' => 'Nós trabalhamos com total ética, seguindo as normas da profissão e as regras internas da onde atuamos, tratando todos com total respeito e responsabilidade.',
        ],
        [
            'img' => 'resources/images/site/banner/5.jpg',
            'title' => 'Compromisso',
            'desc' => 'Não deixamos nossos clientes esperando! Todo tempo é essencial para realização das atividades, todavia, nosso time está preparado para atender todas as demandas necessárias dos clientes, 24 horas por dia, sempre com total excelência e eficácia.',
        ],
    ];
@endphp

<x-site.app>
    <div class="dp-flex col v-top h-center">
        <x-site.window.header pageTitle="Sobre Nós" pageSubtitle='Segurança e Inovação<br class="desktop" /> em Cada Projeto' thumbnail="resources/images/site/header/about.jpg"/>

        <section id="about-us" class="relative bg(c-bg-2) dp-flex col v-center h-left w-100% p-80px mob:p-20px overflow-x-hidden">

            <div class="dp-flex col wrap v-center h-left gap-40px mob:gap-20px w-100%">

                <div class="dp-flex col v-center h-left w-100%">
                    <p class="c(c-s-4) fnt(wgt-regular) fs-18px mob:fs-16px lh-1.3em t-left">
                        A JRJ Assessoria é referência em <em>segurança do trabalho</em>, <em>manutenção industrial</em> e <em>alpinismo profissional</em>. Atuamos em projetos que unem tecnologia, experiência e responsabilidade para oferecer soluções completas em ambientes de risco. Nosso compromisso é garantir qualidade, pontualidade e segurança, sempre com uma equipe altamente qualificada e preparada para qualquer desafio.
                    </p>
                </div>

                <div class="dp-flex row mob:col v-center h-left gap-40px mob:gap-20px w-100%">
                    <div class="relative w-50% mob:w-100% dp-flex col gap-20px mob:gap-30px sm:gap-10px">
                        <!-- Card 1: Nossa Missão -->
                        <div class="relative dp-flex row sm:col bg(c-s-1) !bc(c-border-2) b-1px-solid rounded-10px v-center sm:v-top h-left sm:h-center py-15px sm:pb-10px pr-10px pl-140px mob:pl-120px sm:pl-10px sm:pt-60px sm:mt-50px w-100% h-105px-min mob:h-80px-min sm:h-120px-min">
                            <div class="service-icon absolute left(10px) center(v) sm:left(auto) sm:top(0px) sm:translate(-50%,50%) bg(c-s-1) !bc(c-t1-600) c(c-t1-600) b-3px-solid rounded-100px dp-flex row v-center h-center w-120px h-120px mob:w-100px mob:h-100px p-10px">
                                @svg('site.nav.about.check', 'w-72px h-72px mob:w-60px mob:h-60px')
                            </div>
                            <div class="dp-flex col gap-10px sm:t-center">
                                <span class="c(c-t1-600) fnt(wgt-bold) fs-20px mob:fs-18px">Nossa Missão:</span>
                                <span class="c(c-s-3) fnt(wgt-regular) fs-16px mob:fs-14px lh-1.3em">Inovar em segurança e saúde ocupacional, oferecendo excelência em serviços e treinamentos especializados.</span>
                            </div>
                        </div>

                        <!-- Card 2: Nosso Propósito -->
                        <div class="relative dp-flex row sm:col bg(c-s-1) !bc(c-border-2) b-1px-solid rounded-10px v-center sm:v-top h-left sm:h-center py-15px sm:pb-10px pr-10px pl-140px mob:pl-120px sm:pl-10px sm:pt-60px sm:mt-50px w-100% h-105px-min mob:h-80px-min sm:h-120px-min">
                            <div class="service-icon absolute left(10px) center(v) sm:left(auto) sm:top(0px) sm:translate(-50%,50%) bg(c-s-1) !bc(c-t1-600) c(c-t1-600) b-3px-solid rounded-100px dp-flex row v-center h-center w-120px h-120px mob:w-100px mob:h-100px p-10px">
                                @svg('site.nav.about.horse', 'w-72px h-72px mob:w-60px mob:h-60px')
                            </div>
                            <div class="dp-flex col gap-10px sm:t-center">
                                <span class="c(c-t1-600) fnt(wgt-bold) fs-20px mob:fs-18px">Nosso Propósito:</span>
                                <span class="c(c-s-3) fnt(wgt-regular) fs-16px mob:fs-14px lh-1.3em">Ser referência em engenharia e soluções ocupacionais, elevando padrões de qualidade para clientes e colaboradores.</span>
                            </div>
                        </div>

                        <!-- Card 3: Nossos Valores -->
                        <div class="relative dp-flex row sm:col bg(c-s-1) !bc(c-border-2) b-1px-solid rounded-10px v-center sm:v-top h-left sm:h-center py-15px sm:pb-10px pr-10px pl-140px mob:pl-120px sm:pl-10px sm:pt-60px sm:mt-50px w-100% h-105px-min mob:h-80px-min sm:h-120px-min">
                            <div class="service-icon absolute left(10px) center(v) sm:left(auto) sm:top(0px) sm:translate(-50%,50%) bg(c-s-1) !bc(c-t1-600) c(c-t1-600) b-3px-solid rounded-100px dp-flex row v-center h-center w-120px h-120px mob:w-100px mob:h-100px p-10px">
                                @svg('site.nav.about.diamond', 'w-72px h-72px mob:w-60px mob:h-60px')
                            </div>
                            <div class="dp-flex col gap-10px sm:t-center">
                                <span class="c(c-t1-600) fnt(wgt-bold) fs-20px mob:fs-18px">Nossos Valores:</span>
                                <span class="c(c-s-3) fnt(wgt-regular) fs-16px mob:fs-14px lh-1.3em">Ética, pontualidade, aprendizado contínuo e confiança em cada entrega.</span>
                            </div>
                        </div>

                    </div>

                    <div class="relative dp-flex col v-center h-left w-50% mob:w-100% gap-30px mob:gap-15px">

                        <h1 class="c(c-t1-600) fnt(wgt-medium) fs-32px mob:fs-29px">O que fazemos?</h1>

                        <p class="c(c-s-4) fnt(wgt-regular) fs-18px mob:fs-16px lh-1.3em">
                            Na JRJ Assessoria, acreditamos que a excelência começa pelas pessoas. Nossa equipe é formada por profissionais experientes, qualificados e em constante atualização, prontos para atuar com segurança, eficiência e responsabilidade em cada projeto.
                        </p>

                        <p class="c(c-s-4) fnt(wgt-regular) fs-18px mob:fs-16px lh-1.3em">
                            Nosso método de trabalho vai além da entrega de soluções técnicas: buscamos compreender a <b>realidade de cada cliente</b>, <b>suas necessidades</b> e <b>desafios</b>, para oferecer respostas <i>práticas</i> e <i>eficazes</i>. Atuamos de forma presencial e ativa na implantação, acompanhando cada etapa para garantir que os resultados sejam duradouros e atendam às expectativas.
                        </p>

                    </div>
                </div>

                <hr class="w-55% g(c-border-3) opacity-0.6" />

                <div class="dp-flex col v-center h-left gap-30px mob:gap-15px w-100%">
                    <h1 class="c(c-t1-600) fnt(wgt-semibold) fs-32px mob:fs-29px">Os Cinco Pilares</h1>
                    <p class="c(c-s-4) fnt(wgt-regular) fs-18px mob:fs-16px lh-1.3em">
                        Baseamos nossas ações em cinco pilares: <b>confiabilidade</b>, <b>experiência</b>, <b>implantação</b>, <b>ética</b> e <b>compromisso</b>:
                    </p>
                </div>

                <div class="w-100%">
                    <x-site.content.banner :banners="$banners" id="about-banner" type="carousel" />
                </div>

                <div class="dp-flex col v-center h-left w-100%">
                    <p class="c(c-s-4) fnt(wgt-regular) fs-18px mob:fs-16px lh-1.3em">
                        É assim que a <em>JRJ Assessoria</em> se posiciona como uma parceira de confiança: unindo <b>profissionalismo</b>, <b>inovação</b> e <b>dedicação</b> contínua para transformar desafios em resultados concretos.
                    </p>
                </div>

            </div>

        </section>

        <x-site.content.cta title="Parceria que <b>transforma</b> desafios.<br>Expertise que <b>entrega</b> resultados." message="Conheça a empresa que une <b>segurança, inovação e confiabilidade</b><br>em cada projeto. Vamos construir algo <b>extraordinário</b> juntos." buttonText="Fale conosco" buttonLink="{{ $globalConfig->whatsapp_link }}" />

        <x-site.content.footer />
    </div>

</x-site.app>
