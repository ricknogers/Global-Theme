<?php

/**
 * Defer Scripts
 */


/**
 * Remove Emoji Scripts Styles
 */


remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

remove_action ('wp_head', 'rsd_link');


/**
 *  Trying to Preload Font Family from CDN
 */
add_filter('style_loader_tag', 'my_style_loader_tag_filter', 10, 2);
function my_style_loader_tag_filter($html, $handle) {
	if ($handle === array('snf-source-sans-pro','font-awesome')) {
		return str_replace("rel='stylesheet'",
			"rel='preload' as='font' type='font/woff2' crossorigin='anonymous'", $html);
	}
	return $html;
}