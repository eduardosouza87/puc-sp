<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PUC_SP
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="index,follow">
	<meta name="googlebot" content="index,follow">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() . '/assets/css/main.css?v=' . rand(1, 100000); ?>">
</head>

<body class="font-inter bg-neutral-50">
	<?php get_template_part('components/loader'); ?>

	<!-- Barras decorativas do header -->
	<div class="w-full h-1 bg-app-blue"></div>
	<div class="w-full h-1 bg-app-yellow"></div>

	<header class="py-4 relative">
		<div class="container flex items-center gap-x-4 px-4 h-full justify-between">
			<!-- Logo -->
			<a href="<?php echo get_site_url(); ?>" class="shrink-0">
				<img class="object-contain max-w-64" src="<?php echo get_template_directory_uri() . '/assets/images/logo-pucsp-enciclopedia-juridica.png'; ?>" alt="Logo PUC-SP - Enciclopédia Jurídica">
			</a>

			<!-- Menu Desktop -->
			<div class="g-menu max-lg:bg-white w-full max-lg:bottom-0 max-lg:left-0 max-lg:top-0 max-lg:min-h-screen max-lg:opacity-0 max-lg:invisible max-lg:z-50 max-lg:fixed max-lg:transition-all max-lg:duration-500 max-lg:pt-24 max-lg:pb-16 max-lg:translate-x-full max-lg:pr-10">
				<div class="lg:flex lg:items-center lg:justify-center lg:w-full max-lg:space-y-4">
					<?php
					wp_nav_menu(
						array(
							'menu'	 					=> 'menu-principal',
							'menu_id'					=> 'menu',
							'menu_class'			=> 'nav-main flex flex-col max-lg:gap-y-4 lg:flex-row lg:items-center lg:gap-x-2 max-lg:text-right',
							'container'				=> 'nav',
							'container_id'		=> 'navbar-dropdown',
							'container_class' => 'nav header__menu js-menu w-full lg:w-auto',
							'walker' 					=> new Menu_Default(),
						)
					);
					?>
				</div>
			</div>

			<!-- Busca Desktop -->
			<form action="<?php echo home_url('/'); ?>" method="get" class="hidden lg:flex items-center shrink-0">
				<div class="relative">
					<input
						type="text"
						name="s"
						placeholder="Pesquisar..."
						class="w-64 pl-4 pr-10 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-app-blue focus:border-app-blue transition-all">
					<button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-app-blue transition-colors">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
						</svg>
					</button>
				</div>
			</form>

			<!-- Menu Burger Mobile -->
			<button class="menu__burger space-y-2 block lg:hidden z-9999" type="button" aria-label="Menu" aria-controls="navigation">
				<span class="block w-8 h-0.5 bg-app-black"></span>
				<span class="block w-8 h-0.5 bg-app-black"></span>
				<span class="block w-8 h-0.5 bg-app-black"></span>
			</button>
		</div>
	</header>

	<!-- Busca Mobile (sempre visível abaixo do header) -->
	<div class="lg:hidden px-4 pb-4">
		<form action="<?php echo home_url('/'); ?>" method="get" class="flex items-center">
			<div class="relative w-full">
				<input
					type="text"
					name="s"
					placeholder="Buscar verbetes..."
					class="w-full pl-4 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-app-blue focus:border-app-blue transition-all">
				<button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-app-blue transition-colors">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</button>
			</div>
		</form>
	</div>

	<main class="">