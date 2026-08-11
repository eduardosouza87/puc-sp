<?php

/**
 * Template para exibir um verbete individual
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package PUC_SP
 */

get_header();

// ACF Fields
$notas = get_field('notas');
$referencias = get_field('referencias');
$citacao = get_field('citacao');
$pdf_old = get_field('pdf_old'); // URL do PDF antigo

// Busca autores e edições com fallback para IDs legados
$autores = puc_sp_get_verbete_autores_with_fallback(get_the_ID());
$edicoes = puc_sp_get_verbete_edicoes_with_fallback(get_the_ID());

// PDF e edição correspondentes à versão ativa do tomo relacionado
$pdf_ativo = puc_sp_get_verbete_pdf_ativo(get_the_ID());
$edicao_name = $pdf_ativo['edicao'] ? $pdf_ativo['edicao']->name : '';
?>

<!-- Header da Página -->
<section class="relative py-10 md:py-14 bg-app-blue">
	<div class="container relative z-10 px-5">
		<!-- Breadcrumb -->
		<nav class="mb-4">
			<ol class="flex items-center gap-2 text-white/70 text-sm">
				<li><a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a></li>
				<li><span class="mx-2">/</span></li>
				<li><a href="<?php echo home_url('/verbetes'); ?>" class="hover:text-white transition-colors">Verbetes</a></li>
				<li><span class="mx-2">/</span></li>
				<li class="text-white"><?php the_title(); ?></li>
			</ol>
		</nav>

		<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white text-left">
			<?php the_title(); ?>
		</h1>
		<!-- Barras decorativas -->
		<div class="flex flex-col gap-1 mt-2">
			<span class="block w-14 h-1 bg-white"></span>
			<span class="block w-39 h-1 bg-app-yellow"></span>
		</div>

		<?php if (!empty($autores)) : ?>
			<p class="text-white/80 mt-4">
				<?php echo puc_sp_render_autores_links($autores, 'hover:text-app-yellow transition-colors'); ?>
			</p>
		<?php endif; ?>

		<?php if ($edicoes) : ?>
			<div class="text-white/80 mt-2">
				<?php
				$edicoes_info = [];
				foreach ($edicoes as $tomo) :
					$tomo_data = get_the_date('Y', $tomo);
					$edicoes_info[] = sprintf(
						'<a href="%s" class="hover:text-app-yellow transition-colors">Tomo %s</a>, edição %s, %s',
						get_the_permalink($tomo),
						esc_html(get_the_title($tomo)),
						esc_html($edicao_name ?: '-'),
						esc_html($tomo_data)
					);
				endforeach;
				echo implode(' | ', $edicoes_info);
				?>
			</div>
		<?php endif; ?>

		<!-- Botão PDF no final -->
		<?php
		// Priorizar pdf_old (URL legada) sobre o PDF da edição ativa do tomo
		$pdf_url = !empty($pdf_old) ? $pdf_old : $pdf_ativo['pdf_url'];
		if ($pdf_url) :
		?>
			<a href="<?php echo esc_url($pdf_url); ?>" target="_blank" class="mt-4 inline-flex items-center gap-2 px-6 py-4 bg-app-yellow text-app-black font-semibold rounded-lg hover:bg-app-yellow/95 transition-colors">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
				</svg>
				Baixar versão em PDF
			</a>
		<?php endif; ?>
	</div>
</section>

<!-- Conteúdo do Verbete -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">
		<article class="prose prose-lg max-w-6xl prose-headings:text-app-blue prose-a:text-app-blue prose-a:no-underline hover:prose-a:underline">

			<!-- Conteúdo principal -->
			<?php the_content(); ?>

			<!-- Referências -->
			<?php if ($referencias) : ?>
				<div class="mt-12 pt-8 border-t border-gray-200">
					<h2 class="text-2xl font-bold text-app-blue mb-4">Referências</h2>
					<div class="flex flex-col gap-1 mt-2">
						<span class="block w-14 h-1 bg-app-blue"></span>
						<span class="block w-39 h-1 bg-app-yellow"></span>
					</div>
					<div>
						<?php echo wp_kses_post($referencias); ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- Citação -->
			<?php if ($citacao) : ?>
				<div class="mt-12 pt-8 border-t border-gray-200">
					<h2 class="text-2xl font-bold text-app-blue mb-4">Como citar este verbete</h2>
					<div class="bg-gray-50 p-6 rounded-lg border-l-4 border-app-yellow not-prose">
						<div class="text-gray-700">
							<?php echo wp_kses_post($citacao); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</article>

		<!-- Navegação entre verbetes -->
		<div class="mt-12 pt-8 border-t border-gray-200">
			<div class="flex flex-col justify-between gap-4">
				<?php
				$prev_post = get_previous_post();
				$next_post = get_next_post();
				?>

				<?php if ($prev_post) : ?>
					<a href="<?php echo get_the_permalink($prev_post); ?>" class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex-1">
						<svg class="w-5 h-5 text-app-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
						</svg>
						<div class="min-w-0">
							<span class="text-xs text-gray-500 block">Anterior</span>
							<span class="text-sm font-medium text-gray-700 truncate block"><?php echo esc_html($prev_post->post_title); ?></span>
						</div>
					</a>
				<?php else : ?>
					<div class="flex-1"></div>
				<?php endif; ?>

				<?php if ($next_post) : ?>
					<a href="<?php echo get_the_permalink($next_post); ?>" class="flex items-center justify-end gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex-1 text-right">
						<div class="min-w-0">
							<span class="text-xs text-gray-500 block">Próximo</span>
							<span class="text-sm font-medium text-gray-700 truncate block"><?php echo esc_html($next_post->post_title); ?></span>
						</div>
						<svg class="w-5 h-5 text-app-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
						</svg>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
