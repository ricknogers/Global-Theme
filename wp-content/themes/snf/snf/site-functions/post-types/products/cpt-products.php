<?php

/**
 * Registers the `products` post type.
 */
function products_init() {
    register_post_type(
        'products',
        [
            'labels'                => [
                'name'                  => __( 'Products', 'snf' ),
                'singular_name'         => __( 'Products', 'snf' ),
                'all_items'             => __( 'All Products', 'snf' ),
                'archives'              => __( 'Products Archives', 'snf' ),
                'attributes'            => __( 'Products Attributes', 'snf' ),
                'insert_into_item'      => __( 'Insert into products', 'snf' ),
                'uploaded_to_this_item' => __( 'Uploaded to this products', 'snf' ),
                'featured_image'        => _x( 'Featured Image', 'products', 'snf' ),
                'set_featured_image'    => _x( 'Set featured image', 'products', 'snf' ),
                'remove_featured_image' => _x( 'Remove featured image', 'products', 'snf' ),
                'use_featured_image'    => _x( 'Use as featured image', 'products', 'snf' ),
                'filter_items_list'     => __( 'Filter products list', 'snf' ),
                'items_list_navigation' => __( 'Products list navigation', 'snf' ),
                'items_list'            => __( 'Products list', 'snf' ),
                'new_item'              => __( 'New Products', 'snf' ),
                'add_new'               => __( 'Add New', 'snf' ),
                'add_new_item'          => __( 'Add New Products', 'snf' ),
                'edit_item'             => __( 'Edit Products', 'snf' ),
                'view_item'             => __( 'View Products', 'snf' ),
                'view_items'            => __( 'View Products', 'snf' ),
                'search_items'          => __( 'Search products', 'snf' ),
                'not_found'             => __( 'No products found', 'snf' ),
                'not_found_in_trash'    => __( 'No products found in trash', 'snf' ),
                'parent_item_colon'     => __( 'Parent Products:', 'snf' ),
                'menu_name'             => __( 'Products', 'snf' ),
            ],
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => false,
            'supports'  => array(
                'title',
                'editor',
                'excerpt',
                'publicize'.
                'tag',
                'thumbnail'
            ),
            'has_archive'           => false,
            'rewrite'               => true,
            'query_var'             => true,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-cart',
            'show_in_rest'          => true,
            'rest_base'             => 'snf-products',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ]
    );

}

add_action( 'init', 'products_init' );

/**
 * Sets the post updated messages for the `products` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `products` post type.
 */
function products_updated_messages( $messages ) {
    global $post;

    $permalink = get_permalink( $post );

    $messages['products'] = [
        0  => '', // Unused. Messages start at index 1.
        /* translators: %s: post permalink */
        1  => sprintf( __( 'Products updated. <a target="_blank" href="%s">View products</a>', 'snf' ), esc_url( $permalink ) ),
        2  => __( 'Custom field updated.', 'snf' ),
        3  => __( 'Custom field deleted.', 'snf' ),
        4  => __( 'Products updated.', 'snf' ),
        /* translators: %s: date and time of the revision */
        5  => isset( $_GET['revision'] ) ? sprintf( __( 'Products restored to revision from %s', 'snf' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        /* translators: %s: post permalink */
        6  => sprintf( __( 'Products published. <a href="%s">View products</a>', 'snf' ), esc_url( $permalink ) ),
        7  => __( 'Products saved.', 'snf' ),
        /* translators: %s: post permalink */
        8  => sprintf( __( 'Products submitted. <a target="_blank" href="%s">Preview products</a>', 'snf' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
        9  => sprintf( __( 'Products scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview products</a>', 'snf' ), date_i18n( __( 'M j, Y @ G:i', 'snf' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
        /* translators: %s: post permalink */
        10 => sprintf( __( 'Products draft updated. <a target="_blank" href="%s">Preview products</a>', 'snf' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
    ];

    return $messages;
}

add_filter( 'post_updated_messages', 'products_updated_messages' );

/**
 * Sets the bulk post updated messages for the `products` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `products` post type.
 */
function products_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
    global $post;

    $bulk_messages['products'] = [
        /* translators: %s: Number of products. */
        'updated'   => _n( '%s products updated.', '%s products updated.', $bulk_counts['updated'], 'snf' ),
        'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 products not updated, somebody is editing it.', 'snf' ) :
            /* translators: %s: Number of products. */
            _n( '%s products not updated, somebody is editing it.', '%s products not updated, somebody is editing them.', $bulk_counts['locked'], 'snf' ),
        /* translators: %s: Number of products. */
        'deleted'   => _n( '%s products permanently deleted.', '%s products permanently deleted.', $bulk_counts['deleted'], 'snf' ),
        /* translators: %s: Number of products. */
        'trashed'   => _n( '%s products moved to the Trash.', '%s products moved to the Trash.', $bulk_counts['trashed'], 'snf' ),
        /* translators: %s: Number of products. */
        'untrashed' => _n( '%s products restored from the Trash.', '%s products restored from the Trash.', $bulk_counts['untrashed'], 'snf' ),
    ];

    return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'products_bulk_updated_messages', 10, 2 );