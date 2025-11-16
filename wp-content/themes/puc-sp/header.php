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

<body class="font-inter">
	<?php get_template_part('components/loader'); ?>

	<header class="bg-app-black py-4 relative">
		<div class="flex items-center gap-x-4 px-5 h-full justify-between lg:justify-normal">
			<a href="<?php echo get_site_url(); ?>">
				<img class="object-contain max-w-40 mx-auto" src="<?php echo get_template_directory_uri() . '/assets/images/logo-pucsp.jpg'; ?>">
			</a>

			<div class="g-menu max-lg:bg-app-black w-full	max-lg:bottom-0 max-lg:left-0 max-lg:top-0 max-lg:min-h-screen max-lg:opacity-0 max-lg:invisible max-lg:z-50 max-lg:fixed max-lg:transition-all max-lg:duration-500 max-lg:pt-24 max-lg:pb-16 max-lg:translate-x-full max-lg:pr-10">
				<div class="lg:flex lg:items-center lg:justify-between lg:w-full max-lg:space-y-4">
					<?php
					wp_nav_menu(
						array(
							'menu'	 					=> 'menu-principal',
							'menu_id'					=> 'menu',
							'menu_class'			=> 'nav-main flex flex-col max-lg:gap-y-4 lg:flex-row lg:items-center lg:gap-x-1 max-lg:text-right',
							'container'				=> 'nav',
							'container_id'		=> 'navbar-dropdown',
							'container_class' => 'nav header__menu js-menu w-full lg:w-auto',
							'walker' 					=> new Menu_Default(),
						)
					);
					?>
				</div>
			</div>

			<button class="menu__burger space-y-2 block lg:hidden z-9999" type="button" aria-label="Menu" aria-controls="navigation">
				<span class="block w-8 h-0.5 bg-slate-50"></span>
				<span class="block w-8 h-0.5 bg-slate-50"></span>
				<span class="block w-8 h-0.5 bg-slate-50"></span>
			</button>

		</div>
	</header>

	<main class="">