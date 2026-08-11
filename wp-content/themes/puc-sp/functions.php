<?php

/**
 * PUC SP functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package PUC_SP
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function puc_sp_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on PUC SP, use a find and replace
		* to change 'puc-sp' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('puc-sp', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'puc-sp'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'puc_sp_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'puc_sp_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function puc_sp_content_width()
{
	$GLOBALS['content_width'] = apply_filters('puc_sp_content_width', 640);
}
add_action('after_setup_theme', 'puc_sp_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function puc_sp_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'puc-sp'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'puc-sp'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'puc_sp_widgets_init');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Removing some trashs
 */
add_action('after_setup_theme', function () {

	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_shortlink_wp_head');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
	add_filter('the_generator', '__return_false');
	add_filter('show_admin_bar', '__return_false');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');

	// remove SVG and global styles
	remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');

	// remove wp_footer actions which add's global inline styles
	remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

	// remove render_block filters which adding unnecessary stuff
	remove_filter('render_block', 'wp_render_duotone_support');
	remove_filter('render_block', 'wp_restore_group_inner_container');
	remove_filter('render_block', 'wp_render_layout_support_flag');
});

/**
 * Remove some widgets from dashboard
 * Quick Press
 * Recent Drafts
 * Primary
 * Secondary
 * Incoming Links
 * Recent Comments
 * Welcome Panel
 */
function remove_dashboard_widgets()
{
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
	remove_meta_box('dashboard_primary', 'dashboard', 'side');
	remove_meta_box('dashboard_secondary', 'dashboard', 'side');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

// Remove Menus on WP Admin
function remove_menus_on_wp_admin()
{
	remove_menu_page('edit-comments.php'); // Remove Comments
}
add_action('admin_menu', 'remove_menus_on_wp_admin');

// Desativar os tamanhos de imagem padrão do WordPress
function disable_image_sizes($sizes)
{
	unset($sizes['1536x1536']);
	unset($sizes['2048x2048']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_image_sizes');

function custom_login_logo()
{
	$upload_dir = wp_upload_dir();
	$logo_url = $upload_dir['baseurl'] . '/2025/12/logo-pucsp-enciclopedia-juridica.png';

	echo '
    <style type="text/css">
        #login h1 a {
            background-image: url("' . esc_url($logo_url) . '");
            background-size: contain;
            width: 100%;
            height: 120px;
        }
    </style>
    ';
}
add_action('login_head', 'custom_login_logo');

function enqueue_custom_scripts()
{
	wp_enqueue_script(
		'app-js',
		get_template_directory_uri() . '/assets/js/app.js?v=' . time(),
		array(),
		null,
		true
	);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

/**
 * Modifica o slug da taxonomy "autores" para usar /autores/ ao invés de /autor/
 */
function puc_sp_change_autores_rewrite($args, $taxonomy)
{
	if ($taxonomy === 'autores') {
		$args['rewrite'] = [
			'slug'         => 'autores',
			'with_front'   => false,
			'hierarchical' => false,
		];
	}
	return $args;
}
add_filter('register_taxonomy_args', 'puc_sp_change_autores_rewrite', 10, 2);

/**
 * Adiciona estilos customizados para a paginação
 */
function puc_sp_custom_pagination_styles()
{
?>
	<style>
		/* Estilos da paginação - paginate_links */
		.pagination {
			display: flex;
			justify-content: center;
			align-items: center;
			gap: 0.5rem;
			list-style: none;
			padding: 0;
			margin: 0;
		}

		.pagination .page-numbers li {
			display: inline-block;
		}

		.pagination .page-numbers li a,
		.pagination .page-numbers li span {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 2.5rem;
			height: 2.5rem;
			padding: 0.5rem 0.75rem;
			font-weight: 500;
			font-size: 0.875rem;
			color: #1e3a8a;
			background-color: #fff;
			border: 1px solid #e5e7eb;
			border-radius: 0.5rem;
			text-decoration: none;
			transition: all 0.2s;
		}

		.pagination .page-numbers li a:hover {
			background-color: #1e3a8a;
			color: #fff;
			border-color: #1e3a8a;
		}

		.pagination .page-numbers li span.current {
			background-color: #1e3a8a;
			color: #fff;
			border-color: #1e3a8a;
			font-weight: 600;
		}

		.pagination .page-numbers li span.dots {
			border: none;
			background: transparent;
			color: #9ca3af;
			pointer-events: none;
		}

		/* Estilos da paginação - the_posts_pagination */
		.navigation.pagination {
			margin-top: 3rem;
		}

		.navigation.pagination .nav-links {
			display: flex;
			justify-content: center;
			align-items: center;
			gap: 0.5rem;
			flex-wrap: wrap;
		}

		.navigation.pagination .nav-links a,
		.navigation.pagination .nav-links .current,
		.navigation.pagination .nav-links .dots {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 2.5rem;
			height: 2.5rem;
			padding: 0.5rem 0.75rem;
			font-weight: 500;
			font-size: 0.875rem;
			color: #1e3a8a;
			background-color: #fff;
			border: 1px solid #e5e7eb;
			border-radius: 0.5rem;
			text-decoration: none;
			transition: all 0.2s;
		}

		.navigation.pagination .nav-links a:hover {
			background-color: #1e3a8a;
			color: #fff;
			border-color: #1e3a8a;
		}

		.navigation.pagination .nav-links .current {
			background-color: #1e3a8a;
			color: #fff;
			border-color: #1e3a8a;
			font-weight: 600;
		}

		.navigation.pagination .nav-links .dots {
			border: none;
			background: transparent;
			color: #9ca3af;
			pointer-events: none;
		}

		.navigation.pagination .nav-links .prev,
		.navigation.pagination .nav-links .next {
			font-weight: 600;
		}

		/* Responsive */
		@media (max-width: 640px) {

			.pagination .page-numbers li a,
			.pagination .page-numbers li span,
			.navigation.pagination .nav-links a,
			.navigation.pagination .nav-links .current,
			.navigation.pagination .nav-links .dots {
				min-width: 2rem;
				height: 2rem;
				padding: 0.375rem 0.5rem;
				font-size: 0.75rem;
			}
		}
	</style>
<?php
}
add_action('wp_head', 'puc_sp_custom_pagination_styles');

/**
 * Adiciona estilos customizados para o WPForms e scroll suave
 */
function puc_sp_custom_wpforms_styles()
{
?>
	<style>
		/* Scroll suave */
		html {
			scroll-behavior: smooth;
		}

		/* Estilos do WPForms - baseado nos inputs de busca do site */
		.wpforms-container-custom .wpforms-form .wpforms-field-label {
			font-weight: 600;
			color: #1e3a8a;
			margin-bottom: 0.5rem;
			font-size: 0.875rem;
		}

		.wpforms-container-custom .wpforms-form input[type="text"],
		.wpforms-container-custom .wpforms-form input[type="email"],
		.wpforms-container-custom .wpforms-form input[type="tel"],
		.wpforms-container-custom .wpforms-form input[type="url"],
		.wpforms-container-custom .wpforms-form input[type="number"],
		.wpforms-container-custom .wpforms-form textarea,
		.wpforms-container-custom .wpforms-form select {
			width: 100%;
			padding: 0.75rem 1rem;
			border: 1px solid #d1d5db;
			border-radius: 0.5rem;
			font-size: 0.875rem;
			transition: all 0.2s;
			background-color: #fff;
		}

		.wpforms-container-custom .wpforms-form input[type="text"]:focus,
		.wpforms-container-custom .wpforms-form input[type="email"]:focus,
		.wpforms-container-custom .wpforms-form input[type="tel"]:focus,
		.wpforms-container-custom .wpforms-form input[type="url"]:focus,
		.wpforms-container-custom .wpforms-form input[type="number"]:focus,
		.wpforms-container-custom .wpforms-form textarea:focus,
		.wpforms-container-custom .wpforms-form select:focus {
			outline: none;
			border-color: #1e3a8a;
			ring: 2px;
			ring-color: #1e3a8a;
			box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1);
		}

		.wpforms-container-custom .wpforms-form textarea {
			min-height: 120px;
			resize: vertical;
		}

		.wpforms-container-custom .wpforms-form .wpforms-field {
			margin-bottom: 1.5rem;
		}

		.wpforms-container-custom .wpforms-form button[type="submit"],
		.wpforms-container-custom .wpforms-form .wpforms-submit {
			background-color: #1e3a8a;
			color: #fff;
			font-weight: 600;
			padding: 0.75rem 2rem;
			border: none;
			border-radius: 0.5rem;
			cursor: pointer;
			transition: all 0.2s;
			font-size: 0.875rem;
		}

		.wpforms-container-custom .wpforms-form button[type="submit"]:hover,
		.wpforms-container-custom .wpforms-form .wpforms-submit:hover {
			background-color: #1e40af;
			transform: translateY(-1px);
			box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
		}

		.wpforms-container-custom .wpforms-form .wpforms-required-label {
			color: #ef4444;
		}

		.wpforms-container-custom .wpforms-form .wpforms-error {
			color: #ef4444;
			font-size: 0.75rem;
			margin-top: 0.25rem;
		}

		.wpforms-container-custom .wpforms-form .wpforms-field.wpforms-has-error input,
		.wpforms-container-custom .wpforms-form .wpforms-field.wpforms-has-error textarea,
		.wpforms-container-custom .wpforms-form .wpforms-field.wpforms-has-error select {
			border-color: #ef4444;
		}

		/* Remove estilos padrão do WPForms que podem conflitar */
		.wpforms-container-custom .wpforms-form .wpforms-field-container {
			max-width: 100%;
		}

		.wpforms-container-custom .wpforms-confirmation-container-full {
			background-color: #dcfce7;
			border: 1px solid #86efac;
			color: #166534;
			padding: 1rem;
			border-radius: 0.5rem;
			margin-bottom: 1rem;
		}

		.wp-block-image .alignleft,
		.wp-block-image .alignright,
		.wp-block-image .aligncenter {
			float: none !important;
			margin: 0 !important;
		}

		.wp-block-image {
			margin: 0 !important;
		}
	</style>

	<script>
		// Scroll suave para âncoras (fallback para navegadores antigos)
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('a[href^="#"]').forEach(anchor => {
				anchor.addEventListener('click', function(e) {
					const href = this.getAttribute('href');
					if (href === '#' || href === '') return;

					const target = document.querySelector(href);
					if (target) {
						e.preventDefault();
						target.scrollIntoView({
							behavior: 'smooth',
							block: 'start'
						});

						// Atualiza URL sem scroll
						if (history.pushState) {
							history.pushState(null, null, href);
						}
					}
				});
			});
		});
	</script>
<?php
}
add_action('wp_head', 'puc_sp_custom_wpforms_styles');
