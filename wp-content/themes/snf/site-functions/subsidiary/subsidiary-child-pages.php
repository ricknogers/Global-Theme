<?php
function is_tree($pid){
    global $post;

    $ancestors = get_post_ancestors($post->$pid);
    $root = count($ancestors) - 1;
    $parent = $ancestors[$root];

    if(is_page() && (is_page($pid) || $post->post_parent == $pid || in_array($pid, $ancestors))) {
        return true;
    }
    else {
        return false;
    }
};

function get_the_top_ancestor_id() {
    global $post;
    if ( $post->post_parent ) {
        $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
        return $ancestors[0];
    } else {
        return $post->ID;
    }
}

/**
 * Subsididary Child Pages takes wp_list_pages replaces classes of Bootstrap
 */

    function remove_page_class($wp_list_pages) {
        $pattern = '/\<li class="page_item[^>]*">/';
        $replace_with = '<li class="list-group-item">';
        return preg_replace($pattern, $replace_with, $wp_list_pages);
    }
    add_filter('wp_list_pages', 'remove_page_class');


