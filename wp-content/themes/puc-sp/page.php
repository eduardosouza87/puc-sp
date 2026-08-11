<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package PUC_SP
 */

get_header();
?>

<!-- Header da Página -->
<section class="relative py-10 md:py-14 bg-app-blue">
	<div class="container relative z-10 px-5">
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
	</div>
</section>

<!-- Conteúdo da Página -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">
		<?php while (have_posts()) : the_post(); ?>
			<article class="prose prose-lg max-w-none prose-headings:text-app-blue prose-a:text-app-blue prose-a:no-underline hover:prose-a:underline">
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>

<?php
get_footer();
