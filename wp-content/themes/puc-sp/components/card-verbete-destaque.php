<?php

/**
 * Card Verbete Destaque Component
 * Exibe um card individual para verbetes em destaque (sem triângulo)
 *
 * @package PUC_SP
 */

// Pega os autores do verbete
$autores = puc_sp_get_verbete_autores(get_the_ID());

// Pega o tomo relacionado
$edicoes = get_field('edicoes');
$tomo = null;
if ($edicoes && is_array($edicoes) && !empty($edicoes)) {
	$tomo = $edicoes[0]; // Pega o primeiro tomo
}

?>

<article class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100">
	<div class="p-5">
		<!-- Título -->
		<h3 class="text-lg font-bold text-app-blue mb-3 line-clamp-2 hover:text-blue-800 transition-colors">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>

		<!-- Autores -->
		<?php if (!empty($autores)) : ?>
			<div class="flex items-start gap-2 mb-3">
				<svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
				</svg>
				<span class="text-sm text-gray-600">
					<?php echo puc_sp_render_autores_links($autores, 'text-gray-700 hover:text-app-blue'); ?>
				</span>
			</div>
		<?php endif; ?>

		<!-- Tomo -->
		<?php if ($tomo) : ?>
			<div class="flex items-start gap-2">
				<svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
				</svg>
				<a href="<?php echo get_the_permalink($tomo->ID); ?>" class="text-sm text-app-blue hover:underline">
					<?php echo esc_html($tomo->post_title); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</article>