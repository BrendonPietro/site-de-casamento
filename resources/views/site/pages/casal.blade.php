<x-site.app :current_page="'casal'">
	<!-- Hero Section -->
	<section class="relative w-full overflow-hidden bg-gradient-to-br from-purple-900 via-purple-800 to-blue-900"
			 style="height: 50vh; min-height: 400px;">
		
		<!-- Gradient Overlay -->
		<div class="absolute inset-0 bg-black bg-opacity-20"></div>

		<!-- Content -->
		<div class="relative h-full flex flex-col items-center justify-center px-4 text-center">
			<h1 class="text-5xl md:text-6xl font-bold text-white mb-4 drop-shadow-lg">
				Letícia & Brendon
			</h1>
			<p class="text-lg md:text-xl text-purple-100 drop-shadow-lg">
				Conheça nossa história
			</p>
		</div>
	</section>

	<!-- Content Section -->
	<section class="py-16 md:py-24 px-4 max-w-5xl mx-auto">
		<!-- Letícia -->
		<div class="mb-16">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
				<!-- Image Placeholder -->
				<div class="bg-gray-300 rounded-lg overflow-hidden h-80">
					<img src="https://via.placeholder.com/400x500?text=Letícia" 
					     alt="Letícia" 
					     class="w-full h-full object-cover">
				</div>

				<!-- Text -->
				<div>
					<h2 class="text-4xl font-bold text-gray-800 mb-4">Letícia</h2>
					<p class="text-gray-600 text-lg leading-relaxed mb-4">
						Letícia é [descrição sobre Letícia].
					</p>
					<p class="text-gray-600 text-lg leading-relaxed">
						Ela ama [hobbies/interesses] e sonha em [sonhos/planos].
					</p>
				</div>
			</div>
		</div>

		<!-- Brendon -->
		<div class="mb-16">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
				<!-- Text -->
				<div class="order-2 md:order-1">
					<h2 class="text-4xl font-bold text-gray-800 mb-4">Brendon</h2>
					<p class="text-gray-600 text-lg leading-relaxed mb-4">
						Brendon é [descrição sobre Brendon].
					</p>
					<p class="text-gray-600 text-lg leading-relaxed">
						Ele é apaixonado por [hobbies/interesses] e tem o sonho de [sonhos/planos].
					</p>
				</div>

				<!-- Image Placeholder -->
				<div class="order-1 md:order-2 bg-gray-300 rounded-lg overflow-hidden h-80">
					<img src="https://via.placeholder.com/400x500?text=Brendon" 
					     alt="Brendon" 
					     class="w-full h-full object-cover">
				</div>
			</div>
		</div>

		<!-- Our Story -->
		<div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-8 md:p-12">
			<h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
				Nossa História
			</h2>
			<div class="max-w-3xl mx-auto space-y-4 text-gray-600 text-lg leading-relaxed">
				<p>
					Letícia e Brendon se conheceram em [lugar/contexto] e desde o primeiro momento soube que seria especial.
				</p>
				<p>
					[Continuar a história do casal...]
				</p>
				<p>
					Depois de [tempo], Brendon pediu Letícia em casamento em [local/contexto especial], 
					e agora estamos aqui, preparados para esta jornada juntos.
				</p>
			</div>
		</div>
	</section>
</x-site.app>
