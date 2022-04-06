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