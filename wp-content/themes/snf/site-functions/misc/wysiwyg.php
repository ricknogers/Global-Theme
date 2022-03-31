<?php


/**
 * ACF Content WYSIWYG / Excerpt for Archive and Taxonomy Template
 */
// Custom Excerpt function for Advanced Custom Fields
function custom_field_excerpt() {
    global $post;
    $text = get_field('content'); //Replace 'your_field_name'
    $subfield = get_sub_field('content');
    $wysiwyg = get_field('wysiwyg');
    if ( '' != $text ) {
        $text = strip_shortcodes( $text );
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        $excerpt_length = 45; // 20 words
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
    }
    if ( '' != $wysiwyg ) {
        $wysiwyg = strip_shortcodes( $text );
        $wysiwyg = apply_filters('the_content', $text);
        $wysiwyg = str_replace(']]>', ']]>', $text);
        $excerpt_length = 45; // 20 words
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $wysiwyg = wp_trim_words( $wysiwyg, $excerpt_length, $excerpt_more );
    }

    return apply_filters('the_excerpt', $text,$wysiwyg, $subfield);
}
// add something like this to functions.php
function snf_custom_excerpt($text) {
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]>', $text);
    $excerpt_length = apply_filters('excerpt_length', 45);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    return wp_trim_words( $text, $excerpt_length, $excerpt_more );
}



// =========================================================================
// ADD CLASS TO EXCERPT
// =========================================================================
//add_filter( "the_excerpt", "add_class_to_excerpt" );
//
//function add_class_to_excerpt( $excerpt ) {
//    if(is_front_page() && 'global-communication' == get_post_type() ){
//        return str_replace('<p', '<p class="lead news__txt"', $excerpt);
//    }else{
//        return str_replace('<p', '<p class="lead"', $excerpt);
//    }
//
//
//}
