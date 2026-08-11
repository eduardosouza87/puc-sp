<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package PUC_SP
 */

get_header();
?>

<!-- Header da Página de Blog -->
<section class="relative py-10 md:py-14 bg-app-blue">
	<div class="container relative z-10 px-5">
		<!-- Breadcrumb -->
		<nav class="mb-4">
			<ol class="flex items-center gap-2 text-white/70 text-sm">
				<li><a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a></li>
				<li><span class="mx-2">/</span></li>
				<li class="text-white">Notícias</li>
			</ol>
		</nav>

		<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white text-left">
			<?php
			if (is_home() && !is_front_page()) {
				single_post_title();
			} else {
				echo 'Notícias';
			}
			?>
		</h1>
		<!-- Barras decorativas -->
		<div class="flex flex-col gap-1 mt-2">
			<span class="block w-14 h-1 bg-white"></span>
			<span class="block w-39 h-1 bg-app-yellow"></span>
		</div>

		<?php if (is_home() && !is_front_page() && get_the_archive_description()) : ?>
			<div class="text-white/80 mt-4 max-w-3xl">
				<?php echo get_the_archive_description(); ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Lista de Posts -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">

		<?php if (have_posts()) : ?>

			<div class="max-w-4xl space-y-6">

				<?php while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('flex gap-4 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow'); ?>>

						<!-- Imagem -->
						<div class="shrink-0">
							<a href="<?php the_permalink(); ?>" class="block">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail(array(120, 120), array('class' => 'w-[120px] h-[120px] object-cover rounded')); ?>
								<?php else : ?>
									<div class="w-[120px] h-[120px] bg-gray-200 rounded flex items-center justify-center">
										<svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
										</svg>
									</div>
								<?php endif; ?>
							</a>
						</div>

						<!-- Conteúdo -->
						<div class="flex-1 min-w-0">

							<!-- Data -->
							<div class="text-xs text-gray-500 mb-1">
								<?php echo get_the_date(); ?>
							</div>

							<!-- Título -->
							<h2 class="text-xl font-bold mb-2">
								<a href="<?php the_permalink(); ?>" class="text-app-blue hover:text-blue-700 transition-colors">
									<?php the_title(); ?>
								</a>
							</h2>

							<!-- Excerpt -->
							<?php if (has_excerpt() || get_the_content()) : ?>
								<div class="text-gray-600 text-sm line-clamp-2">
									<?php
									if (has_excerpt()) {
										echo get_the_excerpt();
									} else {
										echo wp_trim_words(get_the_content(), 20, '...');
									}
									?>
								</div>
							<?php endif; ?>

							<!-- Categorias e Tags -->
							<div class="mt-2 flex gap-2 flex-wrap">
								<?php
								$categories = get_the_category();
								if (!empty($categories)) :
									foreach ($categories as $category) : ?>
										<a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="inline-block px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">
											<?php echo esc_html($category->name); ?>
										</a>
									<?php endforeach;
								endif;

								$tags = get_the_tags();
								if (!empty($tags)) :
									foreach ($tags as $tag) : ?>
										<a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="inline-block px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">
											#<?php echo esc_html($tag->name); ?>
										</a>
								<?php endforeach;
								endif;
								?>
							</div>

						</div>

					</article>

				<?php endwhile; ?>

			</div>

			<!-- Paginação -->
			<div class="mt-12 max-w-4xl mx-auto">
				<?php
				the_posts_pagination(array(
					'mid_size' => 2,
					'prev_text' => __('← Anterior', 'puc-sp'),
					'next_text' => __('Próxima →', 'puc-sp'),
					'class' => 'flex justify-center gap-2',
				));
				?>
			</div>

		<?php else : ?>

			<div class="max-w-4xl mx-auto text-center py-12">
				<svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
				</svg>
				<h2 class="text-2xl font-bold text-gray-700 mb-2">Nenhuma publicação encontrada</h2>
				<p class="text-gray-600">Ainda não há conteúdo publicado.</p>
			</div>

		<?php endif; ?>

	</div>
</section>

<?php
get_footer();
