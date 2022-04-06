<?php
//
///**
// * Registers the `communication` post type.
// */
//function communication_init() {
//    register_post_type('global-communication', array(
//        'label' => 'Global Communication',
//        'description' => 'Communication in the forms of Communication Articles, Press Releases, and Trade Shows',
//        'hierarchical' => true,
//        'supports' => array(
//            0 => 'title',
//            1 => 'editor',
//            2 => 'author',
//            3 => 'thumbnail',
//            4 => 'trackbacks',
//            5 => 'custom-fields',
//            6 => 'revisions',
//            7 => 'page-attributes',
//            8 => 'post-formats',
//        ),
//        'taxonomies' => array(
//            0 => 'category',
//            1 => 'post_format',
//            2 => 'markets',
//            3 => 'country',
//        ),
//        'public' => true,
//        'exclude_from_search' => false,
//        'publicly_queryable' => true,
//        'can_export' => true,
//        'delete_with_user' => 'null',
//        'labels' => array(
//            'singular_name' => 'Communication',
//            'add_new' => 'Add New Communication Piece',
//            'menu_name' => 'Communication',
//        ),
//        'menu_icon' => 'dashicons-admin-post',
//        'show_ui' => true,
//        'show_in_menu' => true,
//        'show_in_nav_menus' => true,
//        'show_in_admin_bar' => true,
//        'rewrite' => true,
//        'has_archive' => true,
//        'show_in_rest' => false,
//        'rest_base' => '',
//        'rest_controller_class' => 'WP_REST_Posts_Controller',
//        'acfe_archive_template' => '/archives/archive-communication.php',
//        'acfe_archive_ppp' => 10,
//        'acfe_archive_orderby' => 'date',
//        'acfe_archive_order' => 'DESC',
//        'acfe_single_template' => 'single-templates/single-communicaiton.php',
//        'acfe_admin_archive' => false,
//        'acfe_admin_ppp' => 10,
//        'acfe_admin_orderby' => 'date',
//        'acfe_admin_order' => 'DESC',
//        'capability_type' => 'post',
//        'capabilities' => array(
//        ),
//        'map_meta_cap' => NULL,
//    ));
//
//
//
//}
//
//add_action( 'init', 'communication_init' );
//
///**
// * Sets the post updated messages for the `communication` post type.
// *
// * @param  array $messages Post updated messages.
// * @return array Messages for the `communication` post type.
// */
//function communication_updated_messages( $messages ) {
//    global $post;
//
//    $permalink = get_permalink( $post );
//
//    $messages['global-communication'] = [
//        0  => '', // Unused. Messages start at index 1.
//        /* translators: %s: post permalink */
//        1  => sprintf( __( 'Communication updated. <a target="_blank" href="%s">View Communnicaiton</a>', 'snf' ), esc_url( $permalink ) ),
//        2  => __( 'Custom field updated.', 'snf' ),
//        3  => __( 'Custom field deleted.', 'snf' ),
//        4  => __( 'Communication updated.', 'snf' ),
//        /* translators: %s: date and time of the revision */
//        5  => isset( $_GET['revision'] ) ? sprintf( __( 'Communication restored to revision from %s', 'snf' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
//        /* translators: %s: post permalink */
//        6  => sprintf( __( 'Communication published. <a href="%s">View communication</a>', 'snf' ), esc_url( $permalink ) ),
//        7  => __( 'Communication saved.', 'snf' ),
//        /* translators: %s: post permalink */
//        8  => sprintf( __( 'Communication submitted. <a target="_blank" href="%s">Preview communication</a>', 'snf' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
//        /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
//        9  => sprintf( __( 'Communication scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview communication</a>', 'snf' ), date_i18n( __( 'M j, Y @ G:i', 'snf' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
//        /* translators: %s: post permalink */
//        10 => sprintf( __( 'Communication draft updated. <a target="_blank" href="%s">Preview communication</a>', 'snf' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
//    ];
//
//    return $messages;
//}
//
//add_filter( 'post_updated_messages', 'communication_updated_messages' );
//
///**
// * Sets the bulk post updated messages for the `communication` post type.
// *
// * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
// *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
// * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
// * @return array Bulk messages for the `communication` post type.
// */
//function communication_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
//    global $post;
//
//    $bulk_messages['global-communication'] = [
//        /* translators: %s: Number of communication. */
//        'updated'   => _n( '%s communication updated.', '%s communication updated.', $bulk_counts['updated'], 'snf' ),
//        'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 communication not updated, somebody is editing it.', 'snf' ) :
//            /* translators: %s: Number of communication. */
//            _n( '%s communication not updated, somebody is editing it.', '%s communication not updated, somebody is editing them.', $bulk_counts['locked'], 'snf' ),
//        /* translators: %s: Number of communication. */
//        'deleted'   => _n( '%s communication permanently deleted.', '%s communication permanently deleted.', $bulk_counts['deleted'], 'snf' ),
//        /* translators: %s: Number of communication. */
//        'trashed'   => _n( '%s communication moved to the Trash.', '%s communication moved to the Trash.', $bulk_counts['trashed'], 'snf' ),
//        /* translators: %s: Number of communication. */
//        'untrashed' => _n( '%s communication restored from the Trash.', '%s communication restored from the Trash.', $bulk_counts['untrashed'], 'snf' ),
//    ];
//
//    return $bulk_messages;
//}
//
//add_filter( 'bulk_post_updated_messages', 'communication_bulk_updated_messages', 10, 2 );