<?php

/**
 * Template Name: Tomos
 * Template para a página de listagem de Tomos
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
		<?php if (get_the_content()) : ?>
			<p class="text-white/80 text-left mt-4 max-w-2xl">
				<?php echo wp_trim_words(get_the_content(), 30); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<!-- Listagem de Tomos -->
<section class="py-16 md:py-24 bg-white">
	<div class="container px-5">
		<?php
		$tomos = new WP_Query([
			'post_type'      => 'tomos',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish'
		]);

		if ($tomos->have_posts()) :
		?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
				<?php
				while ($tomos->have_posts()) :
					$tomos->the_post();
					get_template_part('components/card', 'tomo');
				endwhile;
				?>
			</div>
		<?php
			wp_reset_postdata();
		else :
		?>
			<div class="text-center py-12">
				<p class="text-gray-500 text-lg">Nenhum tomo encontrado.</p>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
