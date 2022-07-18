<?php
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
	register_nav_menus(
		array(
			'snf_global_main_nav' => __( 'Global Navigation' ),
			'snf_markets_ag_nav' => __( 'Agriculture Menu'),
			'snf_markets_construction_nav'  => __( 'Construction & Civil Engineering Menu'),
			'snf_markets_dredging_nav' => __( 'Dredging Menu'),
			'snf_markets_equipment_nav'  => __( 'Equipment & Engineering Menu'),
			'snf_markets_homecare_nav' => __( 'Home Care Menu'),
			'snf_markets_industrial_nav' => __( 'Industrial Water Treatment Menu'),
			'snf_locations_dropdown' => __( 'Locations Mobile Menu'),
			'snf_markets_mining_nav' => __( 'Mining Menu'),
			'snf_markets_municipal_nav' => __( 'Municipal Menu'),
			'snf_markets_og_nav' => __( 'Oil Gas Menu'),
			'snf_markets_pc_nav' => __( 'Personal Care Menu'),
			'snf_markets_pulp_nav' => __( 'Pulp & Paper Menu'),
			'snf_markets_textiles_nav' => __( 'Textiles Menu'),
			// Country
			'snf_country_nav_us' => __( 'USA Country Menu '),
			'snf_country_nav_fr' => __( 'France Country Menu '),
			'snf_country_nav_uk' => __( 'United Kingdom Country Menu '),
			'snf_country_nav_ca' => __( 'Canada Country Menu '),
			'snf_country_nav_chad' => __( 'Chad Country Menu '),
			'snf_country_nav_egypt' => __( 'Egypt Country Menu '),
			'snf_country_nav_israel' => __( 'Israel Country Menu '),
			'snf_country_nav_oman' => __( 'Oman Country Menu '),
			'snf_country_nav_saudi_arabia' => __( 'Saudi Arabia Country Menu '),
			'snf_country_nav_south_africa' => __( 'South Africa Country Menu '),
			'snf_country_nav_uae' => __( 'United Arab Emirates Country Menu '),
			'snf_country_nav_australia' => __( 'Australia Country Menu '),
			'snf_country_nav_argentina' => __( 'Argentina Country Menu '),
			'snf_country_nav_brazil' => __( 'Brazil Country Menu '),
			'snf_country_nav_chile' => __( 'Chile Country Menu '),
			'snf_country_nav_colombia' => __( 'Columbia Country Menu '),
			'snf_country_nav_mexico' => __( 'Mexico Country Menu '),
			'snf_country_nav_china' => __( 'China Country Menu '),
			'snf_country_nav_in' => __( 'India Country Menu '),
			'snf_country_nav_indonesia' => __( 'Indonesia Country Menu '),
			'snf_country_nav_japan' => __( 'Japan Country Menu '),
			'snf_country_nav_philippines' => __( 'Philippine Country Menu '),
			'snf_country_nav_kr' => __( 'South Korea Country Menu '),
			'snf_country_nav_singapore' => __( 'Singapore Country Menu '),
			'snf_country_nav_taiwan' => __( 'Taiwan Country Menu '),
			'snf_country_nav_thailand' => __( 'Thailand Country Menu '),
			'snf_country_nav_austria' => __( 'Austria Country Menu '),
			'snf_country_nav_belgium' => __( 'Belgium Country Menu '),
			'snf_country_nav_croatia' => __( 'Croatia Country Menu '),
			'snf_country_nav_czech_republic' => __( 'Czech Republic Country Menu '),
			'snf_country_nav_finland' => __( 'Finland Country Menu '),
			'snf_country_nav_germany' => _('Germany Country Menu'),
			'snf_country_nav_greece' => _('Greece Country Menu'),
			'snf_country_nav_italy' => _('Italy Country Menu'),
			'snf_country_nav_kazakhstan' => _('Kazakhstan Country Menu'),
			'snf_country_nav_netherlands' => _('Netherlands Country Menu'),
			'snf_country_nav_poland' => _('Poland Country Menu'),
			'snf_country_nav_portugal' => _('Portugal Country Menu'),
			'snf_country_nav_russia' => _('Russia Country Menu'),
			'snf_country_nav_slovakia' => _('Slovakia Country Menu'),
			'snf_country_nav_spain' => _('Spain Country Menu'),
			'snf_country_nav_sweden' => _('Sweden Country Menu'),
			'snf_country_nav_switzerland' => _('Switzerland Country Menu'),
			'snf_country_nav_turkey' => _('Turkey Country Menu'),
		)
	);
}

/**
 * Country Secondary Navbar
 */


function snf_country_nav_us($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_us',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-us-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_fr($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_fr',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-us-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_uk($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_uk',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-us-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_ca($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_ca',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-us-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_australia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_australia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-us-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_chad($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_chad',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-chad-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_egypt($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_egypt',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-egypt-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_oman($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_oman',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-oman-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}

function snf_country_nav_israel($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_israel',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-israel-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_saudi_arabia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_saudi_arabia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-saudi_arabia-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_south_africa($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_south_africa',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-south_africa-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_uae($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_uae',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-uae-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_argentina($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_argentina',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-argentina-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_brazil($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_brazil',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-brazil-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_chile($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_chile',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-chile-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_colombia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_colombia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-colombia-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_mexico($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_mexico',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-mexico-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_china($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_china',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-china-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_in($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_in',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-in-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_indonesia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_indonesia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-indonesia-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_japan($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_japan',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-japan-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_philippines($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_philippines',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-philippines-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_kr($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_kr',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-kr-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_singapore($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_singapore',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-singapore-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_taiwan($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_taiwan',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-taiwan-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_thailand($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_thailand',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-thailand-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_austria($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_austria',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-austria-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_belgium($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_belgium',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-belgium-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_croatia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_croatia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-croatia-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_czech_republic($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_czech_republic',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-czech_republic-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_finland($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_finland',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-finland-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_germany($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_germany',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-germany-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_greece($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_greece',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-greece-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_italy($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_italy',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-italy-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_kazakhstan($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_kazakhstan',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-kazakhstan-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_netherlands($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_netherlands',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-netherlands-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_poland($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_poland',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-poland-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_portugal($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_portugal',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-portugal-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_russia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_russia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-russia-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_slovakia($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_slovakia',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-slovakia-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_spain($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_spain',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-spain-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_sweden($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_sweden',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-sweden-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_switzerland($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_switzerland',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-switzerland-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
	) );

}
function snf_country_nav_turkey($depth) {
	// display the wp3 menu if available
	require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
	wp_nav_menu( array(
		'theme_location'  => 'snf_country_nav_turkey',
		'container' => 'div', /* container class */
		'container_class' => 'market_nav_elements  navbar-collapse',
		'menu_class'      => 'navbar-nav mr-auto',
		'container_id' => 'country-turkey-menu',
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
		'walker' => new WP_Bootstrap_Mega_Navwalker()
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




