<?php

/**
 * Card Tomo Component
 * Exibe um card individual para o CPT Tomo
 *
 * @package PUC_SP
 */

// ACF Fields
$descricao = get_field('descricao');
$versao_ativa = puc_sp_get_tomo_versao_ativa(get_the_ID());
$codigo = $versao_ativa['codigo'];

?>

<article class="group relative bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100 corner-top-left">
	<!-- Conteúdo -->
	<div class="pb-4 px-4 pt-8">
		<?php if ($codigo) : ?>
			<span class="inline-block text-xs font-semibold text-app-blue bg-app-blue/10 px-2 py-1 rounded mb-3">
				<?php echo esc_html($codigo); ?>
			</span>
		<?php endif; ?>

		<h3 class="text-lg uppercase font-bold text-app-blue mb-2 line-clamp-2 hover:text-blue-800 transition-colors">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>

		<?php if ($descricao) : ?>
			<p class="text-gray-600 text-sm line-clamp-3 mb-4">
				<?php echo esc_html(wp_trim_words($descricao, 20)); ?>
			</p>
		<?php endif; ?>
	</div>
</article>