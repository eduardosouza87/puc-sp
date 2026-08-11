<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package PUC_SP
 */

get_header();
?>

<!-- Página 404 -->
<section class="relative py-20 md:py-32 bg-gradient-to-b from-app-blue to-blue-800">
	<div class="container relative z-10 px-5">
		<div class="max-w-3xl mx-auto text-center">

			<!-- Número 404 -->
			<div class="mb-8">
				<h1 class="text-[150px] md:text-[200px] lg:text-[250px] font-bold text-white/10 leading-none select-none">
					404
				</h1>
			</div>

			<!-- Ícone -->
			<div class="mb-8 flex justify-center">
				<div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
					<svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
					</svg>
				</div>
			</div>

			<!-- Mensagem -->
			<h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
				Página não encontrada
			</h2>
			<p class="text-white/80 text-lg mb-8">
				Desculpe, a página que você está procurando não existe ou foi movida.
			</p>

			<!-- Botões de ação -->
			<div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
				<a href="<?php echo home_url(); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-app-blue font-semibold rounded-lg hover:bg-gray-100 transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
					</svg>
					Ir para Home
				</a>
				<a href="<?php echo home_url('/verbetes'); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-app-yellow text-app-blue font-semibold rounded-lg hover:bg-yellow-400 transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
					</svg>
					Ver Verbetes
				</a>
			</div>

		</div>
	</div>
</section>

<!-- Seção de busca e sugestões -->
<section class="py-12 md:py-16 bg-white">
	<div class="container px-5">
		<div class="max-w-3xl mx-auto">

			<!-- Busca -->
			<div class="mb-12">
				<h3 class="text-2xl font-bold text-app-blue mb-4 text-center">
					Tente fazer uma busca
				</h3>
				<div class="flex flex-col gap-1 items-center mb-6">
					<span class="block w-14 h-1 bg-app-blue"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>

				<form role="search" method="get" class="relative" action="<?php echo home_url('/'); ?>">
					<input
						type="search"
						name="s"
						placeholder="Pesquisar no site..."
						class="w-full px-6 py-4 pr-14 border-2 border-gray-300 rounded-lg focus:border-app-blue focus:outline-none text-lg"
						value="<?php echo get_search_query(); ?>" />
					<button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-app-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
						<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
						</svg>
					</button>
				</form>
			</div>

			<!-- Posts recentes -->
			<?php
			$recent_posts = wp_get_recent_posts(array(
				'numberposts' => 3,
				'post_status' => 'publish'
			));

			if (!empty($recent_posts)) :
			?>
				<div class="mb-12">
					<h3 class="text-2xl font-bold text-app-blue mb-4 text-center">
						Publicações recentes
					</h3>
					<div class="flex flex-col gap-1 items-center mb-6">
						<span class="block w-14 h-1 bg-app-blue"></span>
						<span class="block w-39 h-1 bg-app-yellow"></span>
					</div>

					<div class="grid md:grid-cols-3 gap-6">
						<?php foreach ($recent_posts as $recent) : ?>
							<article class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
								<?php if (has_post_thumbnail($recent['ID'])) : ?>
									<a href="<?php echo get_permalink($recent['ID']); ?>" class="block">
										<?php echo get_the_post_thumbnail($recent['ID'], 'medium', array('class' => 'w-full h-48 object-cover')); ?>
									</a>
								<?php endif; ?>
								<div class="p-4">
									<h4 class="font-bold text-lg mb-2">
										<a href="<?php echo get_permalink($recent['ID']); ?>" class="text-app-blue hover:text-blue-700 transition-colors">
											<?php echo esc_html($recent['post_title']); ?>
										</a>
									</h4>
									<p class="text-gray-600 text-sm">
										<?php echo get_the_date('', $recent['ID']); ?>
									</p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php
				wp_reset_query();
			endif;
			?>

			<!-- Links úteis -->
			<div class="text-center">
				<h3 class="text-2xl font-bold text-app-blue mb-4">
					Links úteis
				</h3>
				<div class="flex flex-col gap-1 items-center mb-6">
					<span class="block w-14 h-1 bg-app-blue"></span>
					<span class="block w-39 h-1 bg-app-yellow"></span>
				</div>

				<div class="flex flex-wrap justify-center gap-3">
					<a href="<?php echo home_url(); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
						Home
					</a>
					<a href="<?php echo home_url('/verbetes'); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
						Verbetes
					</a>
					<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
						Notícias
					</a>
					<?php
					$pages = get_pages(array('number' => 5, 'sort_column' => 'menu_order'));
					foreach ($pages as $page) :
					?>
						<a href="<?php echo get_permalink($page->ID); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
							<?php echo esc_html($page->post_title); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
