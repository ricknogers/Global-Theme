<?php

/**
 * Filter to remove sitemap credit.
 *
 * @param boolean Defaults to false.
 */
add_filter( 'rank_math/sitemap/remove_credit', '__return_true');


/**
 * Filter to change breadcrumb args.
 *
 * @param array $args Breadcrumb args.
 * @return array $args.
 */
add_filter('rank_math/frontend/breadcrumb/args', function ($args) {
    $args = array(
        'delimiter' => '&nbsp;/&nbsp;',
        'wrap_before' => '<nav aria-label="breadcrumb-locations"><ol class="breadcrumb  ">',
        'wrap_after' => '</ol></nav>',
        'before' => '<li class="breadcrumb-item">',
        'after' => '</li>',
    );
    return $args;
});

/**
 * Filter to change breadcrumb html.
 *
 * @param html $html Breadcrumb html.
 * @param array $crumbs Breadcrumb items
 * @param class $class Breadcrumb class
 * @return html  $html.
 */
add_filter('rank_math/frontend/breadcrumb/html', function ($html, $crumbs, $class) {
    // theme_breadcrumb_function();
    return $html;
}, 10, 3);

/**
 * Allow changing or removing the Breadcrumb items
 *
 * @param array $crumbs The crumbs array.
 * @param Breadcrumbs $this Current breadcrumb object.
 */
add_filter('rank_math/frontend/breadcrumb/items', function ($crumbs, $class) {
	if(is_page('sustainability')){
		$crumbs[1];
	}
    return $crumbs;
}, 10, 2);

/**
 * Filter to change the primary term output of the breadcrumbs class.
 *
 * @param WP_Term $term  Primary term.
 * @param array   $terms Terms attached to the current post.
 */
add_filter( 'rank_math/frontend/breadcrumb/main_term', function( $current_term, $terms ) {
    return $current_term;
}, 10, 2 );

/**
 * Filter to change breadcrumb settings.
 *
 * @param  array $settings Breadcrumb Settings.
 * @return array $setting.
 */
add_filter( 'rank_math/frontend/breadcrumb/settings', function( $settings ) {
    $settings = array(
        'home'           => true,
        'separator'      => '',
        'remove_title'   => '',
        'hide_tax_name'  => '',
        'show_ancestors' => 'True',
    );
    return $settings;
});