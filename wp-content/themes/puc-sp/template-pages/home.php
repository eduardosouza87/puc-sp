<?php

/**
 * Template Name: Home
 * Template part Pagina Home
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Theme Default
 */

get_header();

// ACF Fields
$banners = get_field('banners', 'option'); // Repeater com campos 'imagem', 'titulo', 'subtitulo', 'link'
?>

<!-- Banners com Swiper -->
<section class="hero-section py-6">
	<div class="container px-5">
		<?php if ($banners && is_array($banners)) : ?>
			<div id="hero-slider" class="swiper hero-swiper h-[375px] md:h-[540px] rounded-xl overflow-hidden">
				<div class="swiper-wrapper">
					<?php foreach ($banners as $slide) :
						$imagem = $slide['imagem'];
						$tem_link = $slide['a_imagem_tem_link'];
						$link = $slide['link_de_destino'];
						$titulo = $slide['titulo'];
						$subtitulo = $slide['subtitulo'];
					?>
						<div class="swiper-slide relative h-full">
							<?php if ($tem_link && $link) : ?>
								<a href="<?php echo esc_url($link); ?>" class="block absolute inset-0 z-10">
								<?php endif; ?>

								<!-- Imagem de fundo -->
								<div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo esc_url($imagem); ?>');">
									<div class="absolute inset-0 bg-black/50"></div>
								</div>

								<!-- Conteúdo do slide -->
								<div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 z-20">
									<div class="flex flex-col gap-y-2 lg:gap-y-4">
										<?php if ($titulo) : ?>
											<h2 class="text-2xl lg:text-4xl font-bold text-white leading-tight">
												<?php echo esc_html($titulo); ?>
											</h2>
										<?php endif; ?>

										<?php if ($subtitulo) : ?>
											<p class="text-base md:text-lg text-white font-normal max-w-xl">
												<?php echo esc_html($subtitulo); ?>
											</p>
										<?php endif; ?>
									</div>
								</div>

								<?php if ($tem_link && $link) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Arrows -->
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>

				<!-- Pagination -->
				<div class="swiper-pagination"></div>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Últimas Notícias -->
<?php
$ultimas_noticias = new WP_Query([
	'post_type'      => 'post',
	'posts_per_page' => 4,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish'
]);

if ($ultimas_noticias->have_posts()) :
?>
	<section class="py-16 md:py-24">
		<div class="container px-5">
			<div class="mb-10">
				<h2 class="text-2xl md:text-3xl font-bold text-app-blue">Notícias</h2>
				<div class="flex flex-col gap-1 mt-2">
					<span class="block w-14 h-1 bg-app-blue"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

				<?php
				$counter = 0;
				while ($ultimas_noticias->have_posts()) :
					$ultimas_noticias->the_post();
					$counter++;

					if ($counter === 1) :
						// Primeira notícia - Card destacado (coluna esquerda)
				?>
						<article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
							<?php if (has_post_thumbnail()) : ?>
								<a href="<?php the_permalink(); ?>" class="block">
									<?php the_post_thumbnail('large', array('class' => 'object-[0%_15%] w-full h-64 object-cover')); ?>
								</a>
							<?php endif; ?>

							<div class="p-6 space-y-3">
								<!-- Data e Categoria -->
								<div class="">
									<span class="text-sm text-gray-500">
										<?php echo get_the_date(); ?>
									</span>
								</div>
								<?php
								$categories = get_the_category();
								if (!empty($categories)) :
									$first_cat = $categories[0];
								?>
									<a href="<?php echo esc_url(get_category_link($first_cat->term_id)); ?>" class="inline-block px-3 py-1 text-xs font-medium bg-app-blue text-white rounded-full hover:bg-blue-700 transition-colors">
										<?php echo esc_html($first_cat->name); ?>
									</a>
								<?php endif; ?>

								<!-- Título -->
								<h3 class="text-2xl font-bold mb-3">
									<a href="<?php the_permalink(); ?>" class="text-app-blue hover:text-blue-700 transition-colors">
										<?php the_title(); ?>
									</a>
								</h3>

								<!-- Excerpt -->
								<?php if (has_excerpt() || get_the_content()) : ?>
									<div class="text-gray-600">
										<?php
										if (has_excerpt()) {
											echo get_the_excerpt();
										} else {
											echo wp_trim_words(get_the_content(), 25, '...');
										}
										?>
									</div>
								<?php endif; ?>
							</div>
						</article>

					<?php
					elseif ($counter === 2) :
						// Começar a coluna da direita com os 3 cards menores
						echo '<div class="flex flex-col gap-6">';
					endif;

					if ($counter > 1) :
						// Cards menores (coluna direita)
					?>
						<article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
							<div class="flex gap-4 p-4">
								<!-- Imagem -->
								<div class="flex-shrink-0">
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
								<div class="flex-1 min-w-0 flex flex-col gap-2">
									<!-- Data -->
									<span class="text-xs text-gray-500">
										<?php echo get_the_date(); ?>
									</span>

									<!-- Categoria -->
									<?php
									$categories = get_the_category();
									if (!empty($categories)) :
										$first_cat = $categories[0];
									?>
										<a href="<?php echo esc_url(get_category_link($first_cat->term_id)); ?>" class="inline-block px-2 py-1 text-xs font-medium bg-app-blue text-white rounded-full hover:bg-blue-700 transition-colors self-start">
											<?php echo esc_html($first_cat->name); ?>
										</a>
									<?php endif; ?>

									<!-- Título -->
									<h3 class="text-lg font-bold leading-tight">
										<a href="<?php the_permalink(); ?>" class="text-app-blue hover:text-blue-700 transition-colors">
											<?php the_title(); ?>
										</a>
									</h3>
								</div>
							</div>
						</article>
				<?php
					endif;
				endwhile;

				// Fechar a coluna da direita
				if ($counter > 1) {
					echo '</div>';
				}

				wp_reset_postdata();
				?>

			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Primeira Área Divisória -->
<?php
$primeira_area = get_field('primeira_area_divisoria');
if ($primeira_area) :
?>
	<section class="py-6">
		<div class="container px-5">
			<div class="relative h-[375px] md:h-[540px] rounded-xl overflow-hidden">
				<!-- Imagem de fundo -->
				<div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo $primeira_area['imagem']; ?>');">
					<div class="absolute inset-0 bg-black/50"></div>
				</div>

				<!-- Conteúdo -->
				<?php if (!empty($primeira_area['texto'])) : ?>
					<div class="absolute inset-0 flex items-center justify-center z-10 p-6 md:p-10">
						<div class="text-center max-w-3xl">
							<p class="text-2xl md:text-3xl lg:text-4xl text-white font-bold leading-relaxed">
								<?php echo $primeira_area['texto']; ?>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Listagem de Tomos -->
<section class="py-16 md:py-24">
	<div class="container px-5">
		<div class="mb-10">
			<h2 class="text-2xl md:text-3xl font-bold text-app-blue">Tomos</h2>
			<div class="flex flex-col gap-1 mt-2">
				<span class="block w-14 h-1 bg-app-blue"></span>
				<span class="block w-39 h-1 bg-app-yellow"></span>
			</div>
		</div>

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

<!-- Segunda Área Divisória -->
<?php
$segunda_area = get_field('segunda_area_divisoria');
if ($segunda_area) :
?>
	<section class="py-6">
		<div class="container px-5">
			<div class="relative h-[375px] md:h-[540px] rounded-xl overflow-hidden">
				<!-- Imagem de fundo -->
				<div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo $segunda_area['imagem']; ?>');">
					<div class="absolute inset-0 bg-black/50"></div>
				</div>

				<!-- Conteúdo -->
				<?php if (!empty($segunda_area['texto'])) : ?>
					<div class="absolute inset-0 flex items-center justify-center z-10 p-6 md:p-10">
						<div class="text-center max-w-3xl">
							<p class="text-xl md:text-2xl lg:text-3xl text-white font-medium leading-relaxed">
								<?php echo $segunda_area['texto']; ?>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Verbetes em Destaque -->
<?php
$verbetes_destaque = get_field('verbetes_em_destaque');
if ($verbetes_destaque && is_array($verbetes_destaque)) :
?>
	<section class="py-16 md:py-24 bg-gray-50">
		<div class="container px-5">
			<div class="mb-10">
				<h2 class="text-2xl md:text-3xl font-bold text-app-blue">Verbetes em Destaque</h2>
				<div class="flex flex-col gap-1 mt-2">
					<span class="block w-14 h-1 bg-app-blue"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
				<?php
				foreach ($verbetes_destaque as $verbete) :
					$post = $verbete;
					setup_postdata($post);
					get_template_part('components/card', 'verbete-destaque');
				endforeach;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php else :
	// Se não houver verbetes em destaque, busca 6 verbetes aleatórios
	$verbetes_aleatorios = new WP_Query([
		'post_type'      => 'verbetes',
		'posts_per_page' => 6,
		'orderby'        => 'rand',
		'post_status'    => 'publish'
	]);

	if ($verbetes_aleatorios->have_posts()) :
	?>
		<section class="py-16 md:py-24 bg-gray-50">
			<div class="container px-5">
				<div class="mb-10">
					<h2 class="text-2xl md:text-3xl font-bold text-app-blue">Verbetes em Destaque</h2>
					<div class="flex flex-col gap-1 mt-2">
						<span class="block w-14 h-1 bg-app-blue"></span>
						<span class="block w-39 h-1 bg-app-yellow"></span>
					</div>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
					<?php
					while ($verbetes_aleatorios->have_posts()) :
						$verbetes_aleatorios->the_post();
						get_template_part('components/card', 'verbete-destaque');
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
<?php
	endif;
endif; ?>

<?php get_footer();
