<?php
// Home page Slider Post Type

add_action( 'init', 'bn_slides_management' );
function bn_slides_management()
{
    $slide_args = array (
        'public'    =>true,
        'query_ver' =>'hero',
        'rewrite'   => array(
            'slug'  =>  'hero',
            'with_front'    =>   false
        ),
        'supports'  => array(
            'title',
            'editor',
            'excerpt',
            'publicize'.
            'tag',
            'thumbnail',
            'revisions'
        ),
        'labels'    =>  array(
            'name'              =>'Hero',
            'singular_name'     =>'Hero',
            'add_new'           =>'Add new hero image',
            'add_new_item'      =>'Add new hero image',
            'edit_item'         =>'Edit hero image',
            'new_item'          =>'New hero image',
            'view_item'         =>'View hero image',
            'search_items'      =>'Search hero image',
            'not_found'         =>'No hero image found',
            'not_found_in_trash'    =>'No hero image Found in Trash'
        ),
        'menu_position' =>4,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'menu_icon' => 'dashicons-id',
    );
    register_post_type('hero', $slide_args );
}