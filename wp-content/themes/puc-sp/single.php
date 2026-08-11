<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package PUC_SP
 */

get_header();

while (have_posts()) :
	the_post();
?>

	<!-- Header da Página -->
	<section class="relative py-10 md:py-14 bg-app-blue">
		<div class="container relative z-10 px-5">
			<!-- Breadcrumb -->
			<nav class="mb-4">
				<ol class="flex items-center gap-2 text-white/70 text-sm">
					<li><a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a></li>
					<li><span class="mx-2">/</span></li>
					<li><a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="hover:text-white transition-colors">Notícias</a></li>
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

			<?php if ('post' === get_post_type()) : ?>
				<!-- Autor e Data -->
				<p class="text-white/80 mt-4">
					<span class="inline-flex items-center gap-2">
						Por <?php the_author_posts_link(); ?>
						<span class="mx-2">•</span>
						<?php echo get_the_date(); ?>
					</span>
				</p>

				<!-- Categorias -->
				<?php
				$categories = get_the_category();
				if (!empty($categories)) :
				?>
					<div class="text-white/80 mt-2">
						<span class="inline-flex items-center gap-2 flex-wrap">
							<?php foreach ($categories as $category) : ?>
								<a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="inline-block px-3 py-1 text-xs font-medium bg-white/20 text-white rounded-full hover:bg-app-yellow hover:text-app-blue transition-colors">
									<?php echo esc_html($category->name); ?>
								</a>
							<?php endforeach; ?>
						</span>
					</div>
				<?php endif; ?>

				<!-- Tags -->
				<?php
				$tags = get_the_tags();
				if (!empty($tags)) :
				?>
					<div class="text-white/80 mt-2">
						<span class="inline-flex items-center gap-2 flex-wrap">
							<?php foreach ($tags as $tag) : ?>
								<a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="inline-block px-3 py-1 text-xs font-medium bg-white/10 text-white rounded-full hover:bg-app-yellow hover:text-app-blue transition-colors">
									#<?php echo esc_html($tag->name); ?>
								</a>
							<?php endforeach; ?>
						</span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</section>

	<!-- Conteúdo do Post -->
	<section class="py-12 md:py-16 bg-white">
		<div class="container px-5">
			<article class="max-w-4xl mx-auto">

				<!-- Imagem Destacada -->
				<?php if (has_post_thumbnail()) : ?>
					<div class="mb-8 flex justify-center">
						<?php the_post_thumbnail('large', array('class' => 'object-[0%_15%] rounded-lg shadow-lg max-w-full w-full max-h-96 object-cover h-auto')); ?>
					</div>
				<?php endif; ?>

				<!-- Conteúdo -->
				<div class="prose prose-lg max-w-none prose-headings:text-app-blue prose-a:text-app-blue prose-a:no-underline hover:prose-a:underline">
					<?php the_content(); ?>
				</div>

				<!-- Navegação entre posts -->
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

				<!-- Comentários -->
				<?php
				if (comments_open() || get_comments_number()) :
					echo '<div class="mt-12 pt-8 border-t border-gray-200">';
					comments_template();
					echo '</div>';
				endif;
				?>

			</article>
		</div>
	</section>

<?php
endwhile; // End of the loop.

get_footer();
