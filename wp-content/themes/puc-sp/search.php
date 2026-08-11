<?php

/**
 * The template for displaying search results pages
 * Busca em posts, tomos e verbetes
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package PUC_SP
 */

get_header();

$search_query = get_search_query();
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Query customizada para buscar em posts, tomos e verbetes
$search_args = [
	'post_type'      => ['post', 'tomos', 'verbetes'],
	's'              => $search_query,
	'posts_per_page' => 20,
	'paged'          => $paged,
	'orderby'        => 'relevance',
	'order'          => 'DESC',
];

$search_results = new WP_Query($search_args);

?>

<!-- Header -->
<section class="relative py-10 md:py-14 bg-app-blue">
	<div class="container relative z-10 px-5">
		<div class="flex flex-col lg:flex-row gap-4 lg:justify-between lg:items-center">
			<div class="">
				<nav class="mb-4">
					<ol class="flex items-center gap-2 text-white/70 text-sm">
						<li><a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a></li>
						<li><span class="mx-2">/</span></li>
						<li class="text-white">Busca</li>
					</ol>
				</nav>

				<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white text-left">
					Resultados da busca
				</h1>
				<div class="flex flex-col gap-1 mt-2">
					<span class="block w-14 h-1 bg-white"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>

				<?php if ($search_query) : ?>
					<p class="text-white/80 mt-4">
						Exibindo resultados para: <strong class="text-white">"<?php echo esc_html($search_query); ?>"</strong>
					</p>
				<?php endif; ?>
			</div>

			<!-- Busca -->
			<div class="">
				<form action="<?php echo home_url('/'); ?>" method="get" class="flex items-center gap-3 mt-6">
					<div class="relative w-full max-w-80">
						<input
							type="text"
							name="s"
							value="<?php echo esc_attr($search_query); ?>"
							placeholder="Buscar..."
							class="w-64 pl-4 text-white pr-10 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-app-blue focus:border-app-blue transition-all">
						<button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-app-blue transition-colors">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
							</svg>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<!-- Resultados -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">
		<?php if ($search_results->have_posts()) : ?>
			<div class="mb-6 text-gray-600">
				<strong><?php echo $search_results->found_posts; ?></strong> resultado<?php echo $search_results->found_posts !== 1 ? 's' : ''; ?> encontrado<?php echo $search_results->found_posts !== 1 ? 's' : ''; ?>.
			</div>

			<div class="space-y-4">
				<?php while ($search_results->have_posts()) : $search_results->the_post(); ?>
					<?php
					$post_type = get_post_type();
					$post_type_label = '';
					$post_type_color = '';

					switch ($post_type) {
						case 'verbetes':
							$post_type_label = 'Verbete';
							$post_type_color = 'bg-app-blue';
							$autores = puc_sp_get_verbete_autores(get_the_ID());
							break;
						case 'tomos':
							$post_type_label = 'Tomo';
							$post_type_color = 'bg-app-yellow text-app-black';
							break;
						case 'post':
							$post_type_label = 'Notícias';
							$post_type_color = 'bg-gray-600';
							break;
						default:
							$post_type_label = 'Página';
							$post_type_color = 'bg-gray-400';
					}
					?>
					<article class="group p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
						<div class="flex flex-col gap-y-2">
							<div class="flex items-center gap-3">
								<span class="text-xs font-semibold px-2 py-1 rounded <?php echo $post_type_color; ?> text-white">
									<?php echo $post_type_label; ?>
								</span>
								<h3 class="text-lg font-semibold text-app-blue transition-colors">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
							</div>

							<?php if ($post_type === 'verbetes' && !empty($autores)) : ?>
								<p class="text-sm font-bold text-gray-600">
									<?php echo puc_sp_render_autores_links($autores); ?>
								</p>
							<?php endif; ?>

							<?php if (has_excerpt() || get_the_content()) : ?>
								<p class="text-sm text-gray-600 line-clamp-2">
									<?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 30); ?>
								</p>
							<?php endif; ?>

							<?php if ($post_type === 'post') : ?>
								<p class="text-xs text-gray-400">
									<?php echo get_the_date(); ?>
								</p>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<!-- Paginação -->
			<?php if ($search_results->max_num_pages > 1) : ?>
				<div class="mt-12">
					<div class="pagination">
						<?php
						echo paginate_links([
							'total'     => $search_results->max_num_pages,
							'current'   => $paged,
							'prev_text' => '← Anterior',
							'next_text' => 'Próxima →',
							'type'      => 'list',
							'mid_size'  => 2,
						]);
						?>
					</div>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<div class="text-center py-12">
				<div class="mb-4">
					<svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</div>
				<p class="text-gray-500 text-lg mb-4">Nenhum resultado encontrado para "<strong><?php echo esc_html($search_query); ?></strong>".</p>
				<p class="text-gray-400">Tente buscar com outros termos ou navegue pelas páginas:</p>
				<div class="flex flex-wrap justify-center gap-3 mt-6">
					<a href="<?php echo home_url('/verbetes'); ?>" class="px-4 py-2 bg-app-blue text-white rounded-lg hover:bg-blue-700 transition-colors">Verbetes</a>
					<a href="<?php echo home_url('/tomos'); ?>" class="px-4 py-2 bg-app-yellow text-app-black rounded-lg hover:bg-yellow-400 transition-colors">Tomos</a>
					<a href="<?php echo home_url('/noticias'); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">Notícias</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
