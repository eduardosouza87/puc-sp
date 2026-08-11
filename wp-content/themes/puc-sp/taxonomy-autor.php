<?php

/**
 * Template para exibir a página de um Autor (taxonomy)
 * Lista todos os verbetes onde este autor participa
 *
 * @package PUC_SP
 */

get_header();

$term = get_queried_object();
$term_id = $term->term_id;
$term_name = $term->name;
$term_description = $term->description;

// Buscar todos os verbetes que têm este autor no campo ACF
$verbetes_query = new WP_Query([
	'post_type'      => 'verbetes',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
	'meta_query'     => [
		[
			'key'     => 'autores',
			'value'   => '"' . $term_id . '"',
			'compare' => 'LIKE'
		]
	]
]);

// Preparar dados dos verbetes
$verbetes_data = [];

if ($verbetes_query->have_posts()) {
	while ($verbetes_query->have_posts()) {
		$verbetes_query->the_post();
		$title = get_the_title();
		$link  = get_the_permalink();

		// Normaliza a primeira letra
		$title_clean = preg_replace('/^\x{FEFF}/u', '', $title);
		$title_clean = preg_replace('/^[\s\x00-\x1F\x7F]+/u', '', $title_clean);
		$first_letter_raw = mb_substr($title_clean, 0, 1, 'UTF-8');
		$first_letter_upper = mb_strtoupper($first_letter_raw, 'UTF-8');

		// Mapa de acentos para letras base
		$accents_map = [
			'Á' => 'A',
			'À' => 'A',
			'Ã' => 'A',
			'Â' => 'A',
			'Ä' => 'A',
			'Å' => 'A',
			'É' => 'E',
			'È' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'Í' => 'I',
			'Ì' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'Ó' => 'O',
			'Ò' => 'O',
			'Õ' => 'O',
			'Ô' => 'O',
			'Ö' => 'O',
			'Ú' => 'U',
			'Ù' => 'U',
			'Û' => 'U',
			'Ü' => 'U',
			'Ç' => 'C',
			'Ñ' => 'N',
			'Ý' => 'Y',
		];

		if (isset($accents_map[$first_letter_upper])) {
			$normalized = $accents_map[$first_letter_upper];
		} elseif (preg_match('/^[A-Z]$/u', $first_letter_upper)) {
			$normalized = $first_letter_upper;
		} else {
			$normalized = '#';
		}

		// Buscar edições/tomos relacionados
		$edicoes = get_field('edicoes', get_the_ID());

		$verbetes_data[] = [
			'id'       => get_the_ID(),
			'title'    => $title,
			'link'     => $link,
			'letter'   => $normalized,
			'edicoes'  => $edicoes,
		];
	}
	wp_reset_postdata();
}

// Agrupa por letra
$grouped = [];
foreach ($verbetes_data as $item) {
	$letter = $item['letter'];
	if (!isset($grouped[$letter])) {
		$grouped[$letter] = [];
	}
	$grouped[$letter][] = $item;
}
ksort($grouped);

$total_verbetes = count($verbetes_data);

?>

<!-- Header estilo verbetes -->
<section class="relative py-10 md:py-14 bg-app-blue">
	<div class="container relative z-10 px-5">
		<nav class="mb-4">
			<ol class="flex items-center gap-2 text-white/70 text-sm">
				<li><a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a></li>
				<li><span class="mx-2">/</span></li>
				<li><a href="<?php echo home_url('/verbetes'); ?>" class="hover:text-white transition-colors">Verbetes</a></li>
				<li><span class="mx-2">/</span></li>
				<li class="text-white">Autor</li>
			</ol>
		</nav>

		<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white text-left">
			<?php echo esc_html($term_name); ?>
		</h1>
		<div class="flex flex-col gap-1 mt-2">
			<span class="block w-14 h-1 bg-white"></span>
			<span class="block w-39 h-1 bg-app-yellow"></span>
		</div>

		<?php if ($term_description) : ?>
			<p class="text-white/80 mt-4 max-w-2xl"><?php echo esc_html($term_description); ?></p>
		<?php endif; ?>

		<p class="text-white/80 mt-4">
			<strong><?php echo $total_verbetes; ?></strong> verbete<?php echo $total_verbetes !== 1 ? 's' : ''; ?> encontrado<?php echo $total_verbetes !== 1 ? 's' : ''; ?>
		</p>
	</div>
</section>

<!-- Filtros de letras -->
<?php if (!empty($grouped)) : ?>
	<section class="bg-white pb-4 pt-6">
		<div class="container px-5 flex flex-col gap-4">
			<div class="flex flex-wrap gap-2">
				<?php foreach (array_keys($grouped) as $letter) : ?>
					<a href="#indice-<?php echo esc_attr($letter); ?>" class="w-10 h-10 flex items-center justify-center rounded border text-sm font-semibold transition-colors bg-white text-app-blue border-gray-300 hover:border-app-blue hover:bg-app-blue hover:text-white">
						<?php echo esc_html($letter); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Listagem de verbetes do autor -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">
		<?php if (!empty($grouped)) : ?>
			<?php foreach ($grouped as $letra => $items) : ?>
				<div id="indice-<?php echo esc_attr($letra); ?>" class="mb-10">
					<h2 class="text-5xl font-bold text-app-yellow mb-4 pb-2 border-b-2 border-app-yellow"><?php echo esc_html($letra); ?></h2>
					<div class="space-y-3">
						<?php foreach ($items as $verbete) : ?>
							<article class="group p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
								<div class="flex flex-col gap-y-2">
									<h3 class="text-lg font-semibold text-app-blue transition-colors">
										<a href="<?php echo esc_url($verbete['link']); ?>"><?php echo esc_html($verbete['title']); ?></a>
									</h3>
									<?php if (!empty($verbete['edicoes']) && is_array($verbete['edicoes'])) : ?>
										<p class="text-sm text-gray-600">
											<?php
											$tomos_names = array_map(function ($tomo) {
												return is_object($tomo) ? $tomo->post_title : get_the_title($tomo);
											}, $verbete['edicoes']);
											echo 'Tomo: ' . esc_html(implode(', ', $tomos_names));
											?>
										</p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="text-center py-12">
				<p class="text-gray-500 text-lg">Nenhum verbete encontrado para este autor.</p>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
