<?php

function get_related_posts( $taxonomy = '', $args = array() ){
    /*
     * Before we do anything and waste unnecessary time and resources, first check if we are on a single post page
     * If not, bail early and return false
     */
    if ( !is_single() )
        return false;
    /*
     * Check if we have a valid taxonomy and also if the taxonomy exists to avoid bugs further down.
     * Return false if taxonomy is invalid or does not exist
     */
    if ( !$taxonomy )
        return false;
    $taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
    if ( !taxonomy_exists( $taxonomy ) )
        return false;
    /*
     * We have made it to here, so we should start getting our stuff togther.
     * Get the current post object to start of
     */
    $current_post = get_queried_object();
    /*
     * Get the post terms, just the ids
     */
    $terms = wp_get_post_terms( $current_post->ID, $taxonomy, array( 'fields' => 'ids') );
    /*
     * Lets only continue if we actually have post terms and if we don't have an WP_Error object. If not, return false
     */
    if ( !$terms || is_wp_error( $terms ) )
        return false;
    /*
     * Set the default query arguments
     */
    $defaults = array(
        'post_type' => $current_post->post_type,
        'post__not_in' => array( $current_post->ID),
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'terms' => $terms,
                'include_children' => false
            ),
        ),
    );
    /*
     * Validate and merge the defaults with the user passed arguments
     */
    if ( is_array( $args ) ) {
        $args = wp_parse_args( $args, $defaults );
    } else {
        $args = $defaults;
    }
    /*
     * Now we can query our related posts and return them
     */
    $q = get_posts( $args );
    return $q;
}