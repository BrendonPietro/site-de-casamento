<x-site.app :current_page="'convite'">
	<!-- Hero Section -->
	<section class="relative w-full overflow-hidden bg-gradient-to-br from-pink-900 via-pink-800 to-red-900"
			 style="height: 50vh; min-height: 400px;">
		
		<!-- Gradient Overlay -->
		<div class="absolute inset-0 bg-black bg-opacity-20"></div>

		<!-- Content -->
		<div class="relative h-full flex flex-col items-center justify-center px-4 text-center">
			<h1 class="text-5xl md:text-6xl font-bold text-white mb-4 drop-shadow-lg">
				Convite Virtual
			</h1>
			<p class="text-lg md:text-xl text-pink-100 drop-shadow-lg">
				Você está convidado para nosso grande dia
			</p>
		</div>
	</section>

	<!-- Content Section -->
	<section class="py-16 md:py-24 px-4 max-w-4xl mx-auto">
		<div class="bg-white rounded-lg shadow-xl p-8 md:p-12">
			<div class="text-center mb-12">
				<h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
					Você está convidado!
				</h2>
				<p class="text-lg text-gray-600">
					Estamos muito felizes em convidá-lo para celebrar esse momento especial de nossas vidas.
				</p>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
				<!-- Data/Local -->
				<div class="border-l-4 border-pink-600 pl-6">
					<h3 class="text-xl font-bold text-gray-800 mb-3">Data e Local</h3>
					<p class="text-gray-600 mb-2">
						<strong>Data:</strong> [Data do Casamento]
					</p>
					<p class="text-gray-600">
						<strong>Local:</strong> [Local da Cerimônia]
					</p>
				</div>

				<!-- Hora -->
				<div class="border-l-4 border-pink-600 pl-6">
					<h3 class="text-xl font-bold text-gray-800 mb-3">Cerimônia</h3>
					<p class="text-gray-600 mb-2">
						<strong>Horário:</strong> [Horário]
					</p>
					<p class="text-gray-600">
						<strong>Endereço:</strong> [Endereço Completo]
					</p>
				</div>
			</div>

			<!-- Confirmation Button -->
			<div class="text-center mt-12">
				<a href="#" class="px-8 py-4 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors duration-200">
					Confirmar Presença
				</a>
				<p class="text-gray-500 text-sm mt-4">
					Você precisa estar logado para confirmar sua presença
				</p>
			</div>

			<!-- Additional Info -->
			<div class="mt-12 pt-8 border-t">
				<h3 class="text-xl font-bold text-gray-800 mb-4">Informações Importantes</h3>
				<ul class="space-y-3 text-gray-600">
					<li class="flex items-start">
						<span class="text-pink-600 font-bold mr-3">•</span>
						<span>Favor, confirme sua presença até a data limite.</span>
					</li>
					<li class="flex items-start">
						<span class="text-pink-600 font-bold mr-3">•</span>
						<span>Haverá recepção após a cerimônia.</span>
					</li>
					<li class="flex items-start">
						<span class="text-pink-600 font-bold mr-3">•</span>
						<span>Veja nossa lista de presentes para nos ajudar.</span>
					</li>
				</ul>
			</div>
		</div>
	</section>
</x-site.app>
