<?php

add_action( 'pre_get_posts', 'snf_custom_wp_query' );
function snf_custom_wp_query( $wp_query ){
    if( $wp_query->is_main_query() && $wp_query->is_page('news') ){
        $wp_query->set('posts_per_page', -1);
        $wp_query->set('orderby', 'date');
        $wp_query->set('order', 'DESC');
    }
}