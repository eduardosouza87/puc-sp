<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package PUC_SP
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function puc_sp_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'puc_sp_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function puc_sp_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'puc_sp_pingback_header');

/**
 * Menu_Default
 */
class Menu_Default extends Walker_Nav_Menu
{
	// Displays start of an element. E.g '<li> Item Name'
	// @see Walker::start_el()
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$object 			= $item->object;
		$type 				= $item->type;
		$title 				= $item->title;
		$description 	= $item->description;
		$permalink 		= $item->url;
		$output .= "<li class='" .  implode(" ", $item->classes) . "'>";

		$classesCSS = "text-white hover:text-app-green uppercase lg:px-4 lg:py-6 border-b-2 lg:border-b-4 border-b-transparent text-xs tracking-login transition-colors";

		if ($permalink && $permalink != '#') {
			$output .= '<a class="' . $classesCSS . '" href="' . $permalink . '">';
		} else {
			$output .= '<span class="' . $classesCSS . '">';
		}

		$output .= $title;

		if ($description != '' && $depth == 0) {
			$output .= '<small class="">' . $description . '</small>';
		}
		if ($permalink && $permalink != '#') {
			$output .= '</a>';
		} else {
			$output .= '</span>';
		}
	}
}

/**
 * Auto Copyright
 */
function auto_copyright($year = 'auto')
{
	if ($year == 'auto') {
		$year = date('Y');
	}

	if (intval($year) == date('Y')) {
		echo intval($year);
	}

	if (intval($year) < date('Y')) {
		echo intval($year) . ' - ' . date('Y');
	}

	if (intval($year) > date('Y')) {
		echo date('Y');
	}
}
