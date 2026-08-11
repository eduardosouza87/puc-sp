<?php

/**
 * Template Name: Sobre
 * Template para a página Sobre
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package PUC_SP
 */

get_header();

// Pega a imagem destacada da página para usar como background do header
$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
$default_bg = get_template_directory_uri() . '/assets/images/header-bg-default.jpg';
$background_image = $featured_image ? $featured_image : $default_bg;

?>

<div class="page-sobre">
	<!-- Header da Página -->
	<section class="relative py-10 md:py-14 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url($background_image); ?>');">
		<!-- Overlay -->
		<div class="absolute inset-0 bg-app-black/70"></div>

		<div class="container relative z-10 px-5">
			<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white text-left">
				<?php the_title(); ?>
			</h1>
			<!-- Barras decorativas -->
			<div class="flex flex-col gap-1 mt-2">
				<span class="block w-14 h-1 bg-app-blue"></span>
				<span class="block w-39 h-1 bg-app-yellow"></span>
			</div>
		</div>
	</section>

	<!-- Conteúdo -->
	<section class="py-14">
		<div class="container p-4 lg:py-8 lg:px-6 bg-white rounded-lg">
			<div class="prose max-w-none">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
