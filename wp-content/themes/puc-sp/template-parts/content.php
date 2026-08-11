<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package PUC_SP
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('max-w-4xl mx-auto'); ?>>

	<?php if (is_singular() && has_post_thumbnail()) : ?>
		<div class="entry-featured-image mb-8">
			<?php the_post_thumbnail('full', array('class' => 'w-full h-auto rounded-lg shadow-lg object-[0%_15%]')); ?>
		</div>
	<?php endif; ?>

	<header class="entry-header mb-8">
		<?php
		if (is_singular()) :
			the_title('<h1 class="entry-title text-4xl font-bold mb-4">', '</h1>');
		else :
			the_title('<h2 class="entry-title text-3xl font-bold mb-4"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-blue-600 transition-colors">', '</a></h2>');
		endif;

		if ('post' === get_post_type()) :
		?>
			<div class="entry-meta flex flex-wrap gap-4 items-center text-sm text-gray-600 mb-4">
				<?php
				// Data de publicação
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
				if (get_the_time('U') !== get_the_modified_time('U')) {
					$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
				}
				$time_string = sprintf(
					$time_string,
					esc_attr(get_the_date(DATE_W3C)),
					esc_html(get_the_date())
				);
				echo '<span class="posted-on flex items-center gap-1">';
				echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
				echo $time_string;
				echo '</span>';

				// Autor
				$byline = sprintf(
					esc_html_x('por %s', 'post author', 'puc-sp'),
					'<span class="author vcard"><a class="url fn n hover:text-blue-600 transition-colors" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
				);
				echo '<span class="byline flex items-center gap-1">';
				echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
				echo $byline;
				echo '</span>';
				?>
			</div><!-- .entry-meta -->

			<?php
			// Categorias
			$categories_list = get_the_category_list(', ');
			if ($categories_list) :
			?>
				<div class="entry-categories mb-4">
					<span class="inline-flex items-center gap-2 flex-wrap">
						<svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
						</svg>
						<?php
						$categories = get_the_category();
						foreach ($categories as $category) {
							echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-block px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">' . esc_html($category->name) . '</a>';
						}
						?>
					</span>
				</div>
			<?php endif; ?>

			<?php
			// Tags
			$tags_list = get_the_tag_list();
			if ($tags_list) :
			?>
				<div class="entry-tags">
					<span class="inline-flex items-center gap-2 flex-wrap">
						<svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
						</svg>
						<?php
						$tags = get_the_tags();
						if ($tags) {
							foreach ($tags as $tag) {
								echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full hover:bg-gray-200 transition-colors">#' . esc_html($tag->name) . '</a>';
							}
						}
						?>
					</span>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if (! is_singular() && has_post_thumbnail()) : ?>
		<div class="entry-thumbnail mb-6">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('large', array('class' => 'w-full h-auto rounded-lg shadow-md hover:shadow-lg transition-shadow')); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content prose prose-lg max-w-none">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Continue lendo<span class="screen-reader-text"> "%s"</span>', 'puc-sp'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links mt-8 flex gap-2 items-center"><span class="font-semibold">' . esc_html__('Páginas:', 'puc-sp') . '</span>',
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
		<?php puc_sp_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->