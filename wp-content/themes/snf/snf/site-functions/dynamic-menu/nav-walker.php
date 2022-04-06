<?php
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
    register_nav_menus(
        array(
            'snf_locations_dropdown' => __( 'Locations Menu'),
            'snf_global_main_nav' => __( 'Global Navigation' ),
            'snf_group_footer_menu_items_social' => __( 'Footer Menu Social Items'),
            'snf_group_footer_menu_column_one' => __( 'Footer Menu Column One'),
            'snf_group_footer_menu_column_two' => __( 'Footer Menu Column Two'),
            'snf_group_footer_menu_column_three' => __( 'Footer Menu Column Three'),
            'snf_group_footer_menu_column_four' => __( 'Footer Menu Column Four'),
            'snf_markets_municipal_nav' => __( 'Municipal Menu'),
            'snf_markets_og_nav' => __( 'Oil Gas Menu'),
            'snf_markets_pc_nav' => __( 'Personal Care Menu'),
            'snf_markets_ag_nav' => __( 'Agriculture Menu'),
            'snf_markets_homecare_nav' => __( 'Home Care Menu'),
            'snf_markets_industrial_nav' => __( 'Industrial Water Treatment Menu'),
            'snf_markets_dredging_nav' => __( 'Dredging Menu'),

        )
    );
}
/**
 * Market Sites Navwalker  Menus
 */
function snf_global_main_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu( array(
        'theme_location'  => 'snf_global_main_nav',
        'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
        'container'       => 'div',
        'container_class' => 'collapse navbar-collapse',
        'container_id'    => 'main-menu',
        'menu_class'      => 'navbar-nav mr-auto',
        'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
        'walker'          => new WP_Bootstrap_Mega_Navwalker(),
    ) );

}
function snf_markets_municipal_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_markets_municipal_nav',  /* menu name */
            'container' => 'nav', /* container class */
            'container_class' => 'list-group-items',
            'container_id' => 'municipal-market-menu',
            'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
            'depth' => "{$depth}",  //suppress lower levels for now
            'walker' => new wp_bootstrap_navwalker()
        )
    );
}
function snf_markets_og_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_markets_og_nav',  /* menu name */
            'menu_class' => 'nav navbar-nav',
            'theme_location' => 'snf_markets_og_nav', /* where in the theme it's assigned */
            'container' => 'nav', /* container class */
            'container_class' => 'list-group-items',
            'container_id' => 'og-market-menu',
            'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
            'depth' => "{$depth}",  //suppress lower levels for now
            'walker' => new wp_bootstrap_navwalker()
        )
    );
}
function snf_markets_pc_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_markets_pc_nav',  /* menu name */
            'theme_location' => 'snf_markets_pc_nav',
            'container' => 'nav', /* container class */
            'container_class' => 'list-group-items',
            'container_id' => 'pc-market-menu',
            'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
            'depth' => "{$depth}",  //suppress lower levels for now
            'walker' => new wp_bootstrap_navwalker()
        )
    );
}
function snf_markets_ag_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_markets_ag_nav',  /* menu name */
            'theme_location' => 'snf_markets_ag_nav',
            'container' => 'nav', /* container class */
            'container_class' => 'list-group-items',
            'container_id' => 'ag-market-menu',
            'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
            'depth' => "{$depth}",  //suppress lower levels for now
            'walker' => new wp_bootstrap_navwalker()
        )
    );
}
function snf_markets_homecare_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_markets_homecare_nav',  /* menu name */
            'theme_location' => 'snf_markets_homecare_nav',
            'container' => 'nav', /* container class */
            'container_class' => 'list-group-items',
            'container_id' => 'hc-market-menu',
            'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
            'depth' => "{$depth}",  //suppress lower levels for now
            'walker' => new wp_bootstrap_navwalker()
        )
    );
}
function snf_markets_industrial_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_markets_industrial_nav',  /* menu name */
            'theme_location' => 'snf_markets_industrial_nav',
            'container' => 'nav', /* container class */
            'container_class' => 'list-group-items',
            'container_id' => 'industrial-market-menu',
            'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
            'depth' => "{$depth}",  //suppress lower levels for now
            'walker' => new wp_bootstrap_navwalker()
        )
    );
}


/**
 * Locations Menu Modal Breakdown
 */
function snf_locations_dropdown($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
    wp_nav_menu( array(
        'theme_location' => 'snf_locations_dropdown',
        'menu_class' => 'form-control',
        'container' => 'form', /* container class */
        'container_class' => 'list-group-items',
        'container_id' => 'snf_locations_dropdown',
        'fallback_cb' => 'wp_bootstrap_main_nav_fallback', /* menu fallback */
        'depth' => "{$depth}",  //suppress lower levels for now
        'walker' => new wp_bootstrap_navwalker()
    ) );


}

/**
 * Footer Navwalker Register Menus
 */

function snf_group_footer_menu_items_social($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_group_footer_menu_items_social',  /* menu name */
            'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'div',
            'container_class' => 'social-footer-widget',
            'container_id'    => 'footer-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Mega_Navwalker(),
        )
    );
}
function snf_group_footer_menu_column_one($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_group_footer_menu_column_one',  /* menu name */
            'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => 'footer-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Mega_Navwalker(),
        )
    );
}
function snf_group_footer_menu_column_two($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_group_footer_menu_column_two',  /* menu name */
            'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => 'footer-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Mega_Navwalker(),
        )
    );
}
function snf_group_footer_menu_column_three($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_group_footer_menu_column_three',  /* menu name */
            'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => 'footer-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Mega_Navwalker(),
        )
    );
}
function snf_group_footer_menu_column_four($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'menu' => 'snf_group_footer_menu_column_four',  /* menu name */
            'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => 'footer-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new WP_Bootstrap_Mega_Navwalker(),
        )
    );
}



