<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PUC_SP
 */

?>


</main>

<!-- Seção de Contato -->
<section id="contato" class="py-16 md:py-24 bg-white scroll-mt-20">
	<div class="container mx-auto max-w-6xl px-5">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

			<!-- Coluna Esquerda - Informações -->
			<div>
				<h2 class="text-2xl md:text-3xl font-bold text-app-blue">Contato</h2>
				<div class="flex flex-col gap-1 mt-2 mb-6">
					<span class="block w-14 h-1 bg-app-blue"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>

				<p class="text-gray-700 text-base leading-relaxed">
					Tem alguma dúvida, sugestão ou precisa de mais informações? Preencha o formulário abaixo e entraremos em contato o mais rápido possível.
				</p>
			</div>

			<!-- Coluna Direita - Formulário -->
			<div class="wpforms-container-custom">
				<?php echo do_shortcode('[wpforms id="54"]'); ?>
			</div>

		</div>
	</div>
</section>

<footer id="footer" class="relative">
	<!-- Barras decorativas -->
	<div class="w-full h-[3px] bg-app-blue"></div>
	<div class="w-full h-[3px] bg-app-yellow"></div>

	<!-- Conteúdo -->
	<div class="container px-4 py-10">
		<div class="flex flex-col lg:flex-row items-center lg:justify-center gap-4">
			<img
				src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-pucsp-.png"
				alt="Logo PUC-SP"
				class="h-16 object-contain">
			<p class="text-sm text-center">
				Enciclopédia Jurídica da PUCSP - PUC - Pontifícia Universidade Católica
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

</body>

</html>