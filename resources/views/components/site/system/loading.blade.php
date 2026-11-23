@props([
	'icon' => 'site.logo.default', // SVG / Image
	'type' => 'fill', // Tipo de loading
	'debug' => false, // Modo debug - loading não some
	'exitAnimation' => 'scale-fade', // Tipos: zoom-out, fade, swipe-up, swipe-down, swipe-left, swipe-right, slide-up, slide-down, rotate-fade, scale-fade, blur-fade
])

<nav id="loading" class="bg(c-bg-1) fixed w-100% h-100% z-99 overflow-hidden">
    <!-- Background decorativo animado -->
    <div id="decorative-background" class="fixed w-100% h-100% opacity-0.5">
        <!-- Partículas flutuantes -->
        <div class="particle particle-1 absolute w-4px h-4px bg(c-t1-500) rounded-100%"></div>
        <div class="particle particle-2 absolute w-3px h-3px bg(c-t1-400) rounded-100%"></div>
        <div class="particle particle-3 absolute w-5px h-5px bg(c-t1-600) rounded-100%"></div>
        <div class="particle particle-4 absolute w-2px h-2px bg(c-t1-300) rounded-100%"></div>
        <div class="particle particle-5 absolute w-6px h-6px bg(c-t1-500) rounded-100%"></div>

        <!-- Elementos geométricos arquitetônicos -->
        {{-- <div class="geometric geo-1 absolute w-100px h-2px bg(c-t1-500) opacity-0.1"></div>
		<div class="geometric geo-2 absolute w-80px h-2px bg(c-t1-400) opacity-0.1"></div>
		<div class="geometric geo-3 absolute w-60px h-2px bg(c-t1-600) opacity-0.1"></div> --}}
    </div>

    <!-- Gradiente de fundo pulsante -->
    <div class="hidden fixed center(c) w-100% h-100% opacity-1 rounded-100%" style="background: radial-gradient(circle, var(--c-t1-500) 0%, transparent 70%); animation: breathe 3s ease-in-out infinite;"></div>

    <!-- Container principal da logo -->
    <figure id="loader" class="fixed center(c) w-150px h-150px">
        <!-- Círculo de progresso ao redor da logo -->
        <svg class="absolute center(c) w-180px h-180px" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" fill="none" stroke="var(--c-t1-500)" stroke-width="0.5" opacity="0.2" />
            <circle id="progress-circle" cx="50" cy="50" r="45" fill="none" stroke="var(--c-t1-500)" stroke-width="1" stroke-dasharray="282.74" stroke-dashoffset="282.74" opacity="0.8" />
        </svg>

        <!-- Glow effect container -->
        <div class="absolute center(c) w-150px h-150px rounded-100%" style="box-shadow: 0 0 40px rgba(var(--c-t1-500-rgb), 0.3); animation: glow-pulse 2s ease-in-out infinite;"></div>

        <!-- Logo com saturação controlada -->
        <div class="absolute center(c) w-60% h-60%">
            <div class="saturation"></div>
            @svg($icon, 'w-100% h-100%')
        </div>
    </figure>

    <!-- Contador de progresso -->
    <div class="absolute center(h) bottom(80px) dp-flex col v-center h-center gap-10px">
        <div id="progress-text" class="c(c-s-1) fnt(wgt-medium) fs-18px opacity-0.8">0%</div>
    </div>
</nav>

<script type="module">
    let loadingSaturation = undefined;
	let progressCircle = undefined;
	let progressText = undefined;
	let loadingPhrase = undefined;
	let loadingPercentual = 0;
	const circleCircumference = 282.74; // 2 * π * 45

	// Frases divertidas relacionadas aos serviços da JRJ
	const loadingPhrases = [
		"🧗‍♂️ Aquecendo as cordas de alpinismo...",			// Trabalhos em Altura e Espaço Confinado
		"🧨 Testando os extintores de incêndio...",			// Segurança contra Incêndios
		"📐 Calculando a trajetória de descida...",			// Trabalhos em Altura e Espaço Confinado
		"📚 Organizando o treinamento...",					// Treinamentos e Programas Ocupacionais
		"🦺 Conferindo a linha de vida...",					// Manutenção e Infraestrutura Industrial
		"✅ Conferindo se tudo está nos conformes...",		// Mão de Obra Especializada
		"📋 Ajustando os procedimentos de segurança...",	// Mão de Obra Especializada
		"📏 Conferindo a altura do prédio...",				// Trabalhos em Altura e Espaço Confinado
		"🔒 Verificando se o mosquetão está firme...",		// Trabalhos em Altura e Espaço Confinado
		"💬 Preparando as placas de sinalização...",		// Comunicação Visual
	];

	// Função para selecionar frase aleatória
	function getRandomPhrase() {
		return loadingPhrases[Math.floor(Math.random() * loadingPhrases.length)];
	}

	// Função para animar o carregamento com requestAnimationFrame
	function animateLoading() {
		if (loadingPercentual <= 60) {
			// Animação da saturação da logo
			loadingSaturation.style.transform = `translateY(-${loadingPercentual}%)`;
			
			// Animação do círculo de progresso
			const offset = circleCircumference - (loadingPercentual / 100) * circleCircumference;
			progressCircle.style.strokeDashoffset = offset;
			
			// Atualização do texto de progresso
			progressText.textContent = `${Math.round(loadingPercentual)}%`;
			
			loadingPercentual += 0.8; // Velocidade mais suave
			requestAnimationFrame(animateLoading);
		}
	}

	// Função para trocar frase periodicamente
	function changePhrase() {
		if (loadingPhrase) {
			loadingPhrase.style.opacity = '0.3';
			setTimeout(() => {
				loadingPhrase.textContent = getRandomPhrase();
				loadingPhrase.style.opacity = '0.6';
			}, 200);
		}
	}

	@if($type == 'fill')
		document.addEventListener('DOMContentLoaded', () => {
			loadingSaturation = document.querySelector('#loading .saturation');
			progressCircle = document.querySelector('#progress-circle');
			progressText = document.querySelector('#progress-text');
			loadingPhrase = document.querySelector('#loading-phrase');
			loadingPercentual = 0;

			let body = document.querySelector('body');
			body.style.overflow = 'hidden';

			// Define frase inicial aleatória
			loadingPhrase.textContent = getRandomPhrase();

			// Troca frase a cada 2.5 segundos
			const phraseInterval = setInterval(changePhrase, 2500);

			// Inicia a animação de carregamento
			requestAnimationFrame(animateLoading);

			// Para as trocas de frase quando a página carregar
			window.addEventListener('load', () => {
				clearInterval(phraseInterval);
			});
		});
	@endif

	window.addEventListener('load', () => {
		@if($type == 'fill')
			// Finaliza o carregamento ao alcançar 100%
			loadingSaturation.style.transform = `translateY(-100%)`;
			progressCircle.style.strokeDashoffset = 0;
			progressText.textContent = '100%';
		@endif

		@if(!$debug)
		// Timeout para esconder o loader após a página ser carregada (só se não estiver em debug)
		setTimeout(() => {
			let loading = document.querySelector('#loading');
			loading.classList.add('finished');
			loading.classList.add('exit-{{ $exitAnimation }}');

			// Define duração baseada no tipo de animação
			const animationDurations = {
				'zoom-out': 400,
				'fade': 600,
				'swipe-up': 500,
				'swipe-down': 500,
				'swipe-left': 600,
				'swipe-right': 600,
				'slide-up': 700,
				'slide-down': 700,
				'rotate-fade': 800,
				'scale-fade': 500,
				'blur-fade': 700
			};

			const duration = animationDurations['{{ $exitAnimation }}'] || 400;

			setTimeout(() => {
				loading.style.display = 'none';
				document.querySelector('body').style.overflowY = 'auto';
			}, duration);
		}, 500);
		@else
		// Modo debug: adiciona botão para fechar manualmente
		setTimeout(() => {
			const debugButton = document.createElement('div');
			debugButton.innerHTML = '❌ Debug Mode - Clique para fechar';
			debugButton.style.cssText = `
				position: fixed;
				top: 20px;
				right: 20px;
				background: var(--c-t1-500);
				color: white;
				padding: 10px 20px;
				border-radius: 25px;
				cursor: pointer;
				font-size: 14px;
				font-weight: bold;
				z-index: 100;
				animation: pulse 2s infinite;
			`;
			debugButton.onclick = () => {
				document.querySelector('#loading').style.display = 'none';
				document.querySelector('body').style.overflowY = 'auto';
			};
			document.body.appendChild(debugButton);
		}, 1000);
		@endif
	});
</script>

<style>
    /* Animações para partículas flutuantes */
    .particle {
        animation: float 8s ease-in-out infinite;
    }

    .particle-1 {
        top: 20%;
        left: 15%;
        animation-delay: 0s;
    }

    .particle-2 {
        top: 60%;
        left: 80%;
        animation-delay: 2s;
    }

    .particle-3 {
        top: 80%;
        left: 20%;
        animation-delay: 4s;
    }

    .particle-4 {
        top: 40%;
        left: 70%;
        animation-delay: 1s;
    }

    .particle-5 {
        top: 30%;
        left: 50%;
        animation-delay: 3s;
    }

    /* Animações para elementos geométricos */
    .geometric {
        animation: slide 12s linear infinite;
    }

    .geo-1 {
        top: 25%;
        left: -100px;
        animation-delay: 0s;
        transform: rotate(15deg);
    }

    .geo-2 {
        top: 65%;
        right: -80px;
        animation-delay: 4s;
        transform: rotate(-10deg);
    }

    .geo-3 {
        top: 45%;
        left: -60px;
        animation-delay: 8s;
        transform: rotate(25deg);
    }

    /* Keyframes */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) translateX(0px);
            opacity: 0.3;
        }

        33% {
            transform: translateY(-20px) translateX(10px);
            opacity: 0.6;
        }

        66% {
            transform: translateY(10px) translateX(-5px);
            opacity: 0.4;
        }
    }

    @keyframes slide {
        0% {
            transform: translateX(-100px) rotate(var(--initial-rotation));
        }

        100% {
            transform: translateX(calc(100vw + 100px)) rotate(var(--initial-rotation));
        }
    }

    @keyframes breathe {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.05;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.08;
        }
    }

    @keyframes glow-pulse {

        0%,
        100% {
            box-shadow: 0 0 40px rgba(var(--c-t1-500-rgb), 0.3);
        }

        50% {
            box-shadow: 0 0 60px rgba(var(--c-t1-500-rgb), 0.5);
        }
    }

    /* Transição suave para quando o loading termina */
    #loading.finished {
        transition: all 0.4s ease-out;
    }

    /* === EFEITOS DE SAÍDA === */
    
    /* Zoom Out (padrão) */
    #loading.finished.exit-zoom-out {
        opacity: 0;
        transform: scale(0.95);
    }

    /* Fade simples */
    #loading.finished.exit-fade {
        opacity: 0;
    }

    /* Swipe para cima */
    #loading.finished.exit-swipe-up {
        opacity: 0;
        transform: translateY(-100vh);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Swipe para baixo */
    #loading.finished.exit-swipe-down {
        opacity: 0;
        transform: translateY(100vh);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Swipe para esquerda */
    #loading.finished.exit-swipe-left {
        opacity: 0;
        transform: translateX(-100vw);
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Swipe para direita */
    #loading.finished.exit-swipe-right {
        opacity: 0;
        transform: translateX(100vw);
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Slide para cima com escala */
    #loading.finished.exit-slide-up {
        opacity: 0;
        transform: translateY(-50vh) scale(0.8);
        transition: all 0.7s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    /* Slide para baixo com escala */
    #loading.finished.exit-slide-down {
        opacity: 0;
        transform: translateY(50vh) scale(0.8);
        transition: all 0.7s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    /* Rotação com fade */
    #loading.finished.exit-rotate-fade {
        opacity: 0;
        transform: rotate(180deg) scale(0.3);
        transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Escala com fade suave */
    #loading.finished.exit-scale-fade {
        opacity: 0;
        transform: scale(1.2);
        filter: blur(2px);
        transition: all 0.5s ease-out;
    }

    /* Blur com fade */
    #loading.finished.exit-blur-fade {
        opacity: 0;
        filter: blur(10px);
        transform: scale(1.05);
        transition: all 0.7s ease-in-out;
    }

    /* Transição suave para troca de frases */
    #loading-phrase {
        transition: opacity 0.3s ease-in-out;
    }

    /* Aplicar variáveis CSS customizadas para rotação */
    .geo-1 {
        --initial-rotation: 15deg;
    }

    .geo-2 {
        --initial-rotation: -10deg;
    }

    .geo-3 {
        --initial-rotation: 25deg;
    }

    /* Animação para o botão de debug */
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }

        50% {
            transform: scale(1.05);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 0.8;
        }
    }

</style>
