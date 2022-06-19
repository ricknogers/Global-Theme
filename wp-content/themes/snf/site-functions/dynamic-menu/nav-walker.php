<?php
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
    register_nav_menus(
        array(
            'snf_locations_dropdown' => __( 'Locations Menu'),
            'snf_global_main_nav' => __( 'Global Navigation' ),
            'snf_markets_municipal_nav' => __( 'Municipal Menu'),
            'snf_markets_og_nav' => __( 'Oil Gas Menu'),
            'snf_markets_pc_nav' => __( 'Personal Care Menu'),
            'snf_markets_ag_nav' => __( 'Agriculture Menu'),
            'snf_markets_homecare_nav' => __( 'Home Care Menu'),
            'snf_markets_industrial_nav' => __( 'Industrial Water Treatment Menu'),
            'snf_markets_dredging_nav' => __( 'Dredging Menu'),
            'snf_markets_mining_nav' => __( 'Mining Menu'),
            'snf_markets_pulp_nav' => __( 'Pulp & Paper Menu'),
            'snf_markets_textiles_nav' => __( 'Textiles Menu'),
			'snf_markets_equipment_nav'  => __( 'Equipment & Engineering Menu'),
            'snf_markets_construction_nav'  => __( '	Construction & Civil Engineering Menu'),
            'snf_subsidiary_usa_nav'  => __( 'United States Menu'),
            'snf_subsidiary_uk_nav'  => __( 'United Kingdom Menu'),
            'snf_subsidiary_france_nav'  => __( 'France Menu'),
            'snf_subsidiary_canada_nav'  => __( 'Canada Menu'),
            'snf_subsidiary_austraila_nav'  => __( 'Austraila Menu'),
        )
    );
}


/**
 * Country Sites Navwalker  Menus
 */
function snf_subsidiary_usa_nav($depth) {
    // display the wp3 menu if available
    require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu( array(
        'theme_location'  => 'snf_subsidiary_usa_nav',
        'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
        'container'       => 'div',
        'container_class' => 'market_nav_elements  navbar-collapse',
        'container_id'    => 'country-menu',
        'menu_class'      => 'navbar-nav mr-auto',
        'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
        'walker'          => new WP_Bootstrap_Mega_Navwalker(),
    ) );

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
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
	        'theme_location' => 'snf_markets_municipal_nav',
            'container' => 'div', /* container class */
            'container_class' => 'market_nav_elements  navbar-collapse',
            'menu_class'      => 'navbar-nav mr-auto',
            'container_id' => 'municipal-market-menu',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'walker' => new WP_Bootstrap_Mega_Navwalker()
        )
    );
}
function snf_markets_og_nav($depth) {
    // display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
	        'theme_location' => 'snf_markets_og_nav', /* where in the theme it's assigned */
	        'menu_class'      => 'navbar-nav mr-auto',
            'container' => 'div', /* container class */
            'container_class' => 'market_nav_elements  navbar-collapse',
            'container_id' => 'og-market-menu',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'walker' => new WP_Bootstrap_Mega_Navwalker()
        )
    );
}
function snf_markets_pc_nav($depth) {
    // display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
	        'theme_location' => 'snf_markets_pc_nav',
	        'container_id' => 'pc-market-menu',
	        'menu_class'      => 'navbar-nav mr-auto',
	        'container' => 'div', /* container class */
	        'container_class' => 'market_nav_elements   navbar-collapse',
	        'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
	        'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'walker' => new WP_Bootstrap_Mega_Navwalker()
        )
    );
}
function snf_markets_ag_nav($depth) {
    // display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'theme_location' => 'snf_markets_ag_nav',
            'container_id' => 'ag-market-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'container' => 'div', /* container class */
            'container_class' => 'market_nav_elements  navbar-collapse',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'walker' => new WP_Bootstrap_Mega_Navwalker()
        )
    );
}
function snf_markets_homecare_nav($depth) {
    // display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'theme_location' => 'snf_markets_homecare_nav',
            'container_id' => 'hc-market-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'container' => 'div', /* container class */
            'container_class' => 'market_nav_elements  navbar-collapse',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'walker' => new WP_Bootstrap_Mega_Navwalker()
        )
    );
}
function snf_markets_industrial_nav($depth) {
    // display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
    wp_nav_menu(
        array(
            'theme_location' => 'snf_markets_industrial_nav',
            'container_id' => 'industrial-market-menu',
            'menu_class'      => 'navbar-nav mr-auto',
            'container' => 'div', /* container class */
            'container_class' => 'market_nav_elements  navbar-collapse',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'walker' => new WP_Bootstrap_Mega_Navwalker()
        )
    );
}
function snf_markets_dredging_nav($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu(
		array(
			'theme_location' => 'snf_markets_dredging_nav', /* where in the theme it's assigned */
			'container_id' => 'dredging-market-menu',
			'menu_class'      => 'navbar-nav mr-auto',
			'container' => 'div', /* container class */
			'container_class' => 'market_nav_elements  navbar-collapse',
			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
			'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
			'walker' => new WP_Bootstrap_Mega_Navwalker()
		)
	);
}
function snf_markets_mining_nav($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu(
		array(
			'theme_location' => 'snf_markets_mining_nav', /* where in the theme it's assigned */
			'container_id' => 'mining-market-menu',
			'menu_class'      => 'navbar-nav mr-auto',
			'container' => 'div', /* container class */
			'container_class' => 'market_nav_elements  navbar-collapse',
			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
			'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
			'walker' => new WP_Bootstrap_Mega_Navwalker()
		)
	);
}
function snf_markets_pulp_nav($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu(
		array(
			'theme_location' => 'snf_markets_pulp_nav', /* where in the theme it's assigned */
			'container_id' => 'pulp-market-menu',
			'menu_class'      => 'navbar-nav mr-auto',
			'container' => 'div', /* container class */
			'container_class' => 'market_nav_elements  navbar-collapse',
			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
			'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
			'walker' => new WP_Bootstrap_Mega_Navwalker()
		)
	);
}
function snf_markets_textiles_nav($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu(
		array(

			'theme_location' => 'snf_markets_textiles_nav', /* where in the theme it's assigned */
			'container_id' => 'textiles-market-menu',
			'menu_class'      => 'navbar-nav mr-auto',
			'container' => 'div', /* container class */
			'container_class' => 'market_nav_elements  navbar-collapse',
			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
			'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
			'walker' => new WP_Bootstrap_Mega_Navwalker()
		)
	);
}
function snf_markets_equipment_nav($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu(
		array(
			'theme_location' => 'snf_markets_equipment_nav', /* where in the theme it's assigned */
			'container_id' => 'equipment-market-menu',
			'menu_class'      => 'navbar-nav mr-auto',
			'container' => 'div', /* container class */
			'container_class' => 'market_nav_elements  navbar-collapse',
			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
			'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
			'walker' => new WP_Bootstrap_Mega_Navwalker()
		)
	);
}
function snf_markets_construction_nav($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu(
		array(

			'theme_location' => 'snf_markets_construction_nav', /* where in the theme it's assigned */
			'container_id' => 'construction-market-menu',
			'menu_class'      => 'navbar-nav mr-auto',
			'container' => 'div', /* container class */
			'container_class' => 'market_nav_elements  navbar-collapse',
			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
			'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
			'walker' => new WP_Bootstrap_Mega_Navwalker()
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




