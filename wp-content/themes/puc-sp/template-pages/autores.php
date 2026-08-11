<?php

/**
 * Template Name: Autores
 * Template para a página de listagem de Autores
 * com busca e filtro por letra.
 *
 * @package PUC_SP
 */

get_header();

// Filtros
$search_query = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$letter_filter = isset($_GET['letter']) ? strtoupper(substr(sanitize_text_field(wp_unslash($_GET['letter'])), 0, 1)) : '';

// Buscar todos os termos da taxonomy autores
$autores_args = [
	'taxonomy'   => 'autor',
	'hide_empty' => false,
	'orderby'    => 'name',
	'order'      => 'ASC',
];

if ($search_query) {
	$autores_args['search'] = $search_query;
}

$autores_terms = get_terms($autores_args);
$autores_data = [];

if (!is_wp_error($autores_terms) && !empty($autores_terms)) {
	foreach ($autores_terms as $autor) {
		// Contar verbetes do autor
		$verbetes_count = puc_sp_count_autor_verbetes($autor->term_id);

		$autores_data[] = [
			'id'          => $autor->term_id,
			'name'        => $autor->name,
			'slug'        => $autor->slug,
			'description' => $autor->description,
			'link'        => get_term_link($autor),
			'count'       => $verbetes_count,
			'letter'      => puc_sp_normalize_first_letter($autor->name),
		];
	}
}

// Aplica filtro de letra se houver
if ($letter_filter && $autores_data) {
	$autores_data = array_filter($autores_data, function ($item) use ($letter_filter) {
		return $item['letter'] === $letter_filter;
	});
}

// Agrupa por letra
$grouped = puc_sp_group_by_letter($autores_data);
$has_filters = !empty($search_query) || !empty($letter_filter);

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
						<li class="text-white"><?php the_title(); ?></li>
					</ol>
				</nav>

				<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white text-left">
					<?php the_title(); ?>
				</h1>
				<div class="flex flex-col gap-1 mt-2">
					<span class="block w-14 h-1 bg-white"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>

				<?php if (has_excerpt()) : ?>
					<p class="text-white/80 mt-4 max-w-2xl"><?php echo get_the_excerpt(); ?></p>
				<?php endif; ?>
			</div>

			<!-- Busca -->
			<div class="">
				<form action="<?php echo get_permalink(); ?>" method="get" class="flex items-center gap-3 mt-6">
					<div class="relative w-full max-w-80">
						<input
							type="text"
							name="q"
							value="<?php echo esc_attr($search_query); ?>"
							placeholder="Buscar autores..."
							class="w-full pl-4 pr-10 py-3 text-sm border-0 rounded-lg focus:ring-2 focus:ring-app-yellow transition-all">
						<button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-app-blue transition-colors">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
							</svg>
						</button>
					</div>

					<?php if ($has_filters) : ?>
						<a href="<?php echo get_permalink(); ?>" class="whitespace-nowrap items-center text-sm bg-app-yellow px-2 py-3 rounded-lg transition-colors">Limpar filtros</a>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
</section>

<!-- Filtros de letras -->
<section class="bg-white pb-4 pt-6">
	<div class="container px-5 flex flex-col gap-4">
		<div class="flex flex-wrap gap-2">
			<?php foreach (range('A', 'Z') as $letter) : ?>
				<?php $is_active = ($letter_filter === $letter); ?>
				<a href="<?php echo esc_url(add_query_arg(['letter' => $letter], get_permalink())); ?>" class="w-10 h-10 flex items-center justify-center rounded border text-sm font-semibold transition-colors <?php echo $is_active ? 'bg-app-blue text-white border-app-blue' : 'bg-white text-app-blue border-gray-300 hover:border-app-blue'; ?>">
					<?php echo $letter; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Listagem de autores -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">
		<?php if ($has_filters) : ?>
			<div class="mb-6 text-gray-600">
				<?php
				$total_results = array_sum(array_map('count', $grouped));
				$filter_text = [];
				if ($search_query) {
					$filter_text[] = '"' . esc_html($search_query) . '"';
				}
				if ($letter_filter) {
					$filter_text[] = 'letra ' . esc_html($letter_filter);
				}
				?>
				<strong><?php echo $total_results; ?></strong> resultado<?php echo $total_results !== 1 ? 's' : ''; ?> encontrado<?php echo $total_results !== 1 ? 's' : ''; ?> para <strong class="text-app-blue"><?php echo implode(' e ', $filter_text); ?></strong>.
			</div>
		<?php endif; ?>

		<?php if (!empty($grouped)) : ?>
			<?php foreach ($grouped as $letra => $items) : ?>
				<div id="indice-<?php echo esc_attr($letra); ?>" class="mb-10">
					<h2 class="text-5xl font-bold text-app-yellow mb-4 pb-2 border-b-2 border-app-yellow"><?php echo esc_html($letra); ?></h2>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
						<?php foreach ($items as $autor) : ?>
							<a href="<?php echo esc_url($autor['link']); ?>" class="group p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
								<div class="flex flex-col gap-y-1">
									<h3 class="text-lg font-semibold text-app-blue group-hover:text-app-black transition-colors">
										<?php echo esc_html($autor['name']); ?>
									</h3>
									<p class="text-sm text-gray-500">
										<?php echo $autor['count']; ?> verbete<?php echo $autor['count'] !== 1 ? 's' : ''; ?>
									</p>
									<?php if (!empty($autor['description'])) : ?>
										<p class="text-sm text-gray-600 mt-1 line-clamp-2">
											<?php echo esc_html($autor['description']); ?>
										</p>
									<?php endif; ?>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="text-center py-12">
				<p class="text-gray-500 text-lg">Nenhum autor encontrado.</p>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
