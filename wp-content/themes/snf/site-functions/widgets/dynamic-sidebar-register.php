<?php
function snf_widgets_init() {
    /*Sidebar (one widget area)*/
    register_sidebar( array(
        'name'            => __( 'Sidebar Contact Form', 'snf' ),
        'id'              => 'sidebar-contact-form',
        'description'     => __( 'The sidebar widget area', 'bst' ),
        'before_widget'   => '<section class="%1$s %2$s">',
        'after_widget'    => '</section>',
        'before_title'    => '<h4>',
        'after_title'     => '</h4>',
    ) );

}
add_action( 'widgets_init', 'snf_widgets_init' );

function register_search_widget() {
	register_sidebar(
		array(
			'before_title'  => '<h4 class="search-bar-results">',
			'after_title'   => '</h4>',
			'before_widget' => '<div class="search-widget">',
			'after_widget'  => '</div>',
			'name'        => __( 'Search Widget', 'snf_group' ),
			'id'          => 'search-widget',
			'description' => __( 'This is for search results.', 'snf_group' ),
		)
	);
}
add_action( 'widgets_init', 'register_search_widget' );
