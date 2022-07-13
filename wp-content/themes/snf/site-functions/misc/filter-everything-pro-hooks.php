<?php

add_action( 'wp_enqueue_scripts', 'remove_default_stylesheet', 20 );
function remove_default_stylesheet() {
    wp_dequeue_style( 'wpc-filter-everything-custom' );
    wp_dequeue_style( 'wpc-filter-everything' );

    wp_deregister_style( 'wpc-filter-everything-custom' );
    wp_deregister_style( 'wpc-filter-everything' );
    


}
