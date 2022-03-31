<?php
add_action( 'admin_init', 'hide_editor' );

function hide_editor() {
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    if( !isset( $post_id ) ) return;

    $template_file = get_post_meta($post_id, '_wp_page_template', true);

    if($template_file == 'subsidiary-landing.php'){ // edit the template name
        remove_post_type_support('page', 'editor');
    }
    if($template_file == 'flexible-page-template.php'){ // edit the template name
        remove_post_type_support('page', 'editor');
    }
}