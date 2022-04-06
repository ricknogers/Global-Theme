<?php
function ns_register_footer_widget() {
    register_sidebar(
        array(
            'before_title'  => '<h4 class="bottom-footer-widget-title">',
            'after_title'   => '</h4>',
            'before_widget' => '<div class="bottom-footer-widget">',
            'after_widget'  => '</div>',
            'name'        => __( 'Footer-Widget', 'snf_group' ),
            'id'          => 'bottom-footer-widget',
            'description' => __( 'This is the widget area in the bottom footer.', 'snf_group' ),
        )
    );
}
add_action( 'widgets_init', 'ns_register_footer_widget' );

function register_social_footer_widget() {
    register_sidebar(
        array(
            'before_title'  => '<h4 class="social-footer-widget-title">',
            'after_title'   => '</h4>',
            'before_widget' => '<div class="footer-social-widget">',
            'after_widget'  => '</div>',
            'name'        => __( 'Social-Footer-Widget', 'snf_group' ),
            'id'          => 'social-widget',
            'description' => __( 'This is the widget area in the bottom footer.', 'snf_group' ),
        )
    );
}
add_action( 'widgets_init', 'register_social_footer_widget' );