<?php
/**
 * SNF Group  functions and definitions
 *
 * @package SNF Group
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
    $content_width = 1140; /* pixels */
}
if ( ! function_exists( 'snf_group_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function snf_group_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on SNF , use a find and replace
         * to change 'snf_subsidiary' to the name of your theme in all the template files
         */
        load_theme_textdomain( 'snf_group', get_template_directory() . '/languages' );
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support( 'post-thumbnails' );
        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            )
        );
        // Setup the WordPress core custom background feature.
        // Enable support for HTML5 markup.
        add_theme_support( 'html5', array(
            'comment-list',
            'search-form',
            'comment-form',
            'gallery',
            'caption',
        ) );
        add_theme_support( 'post-thumbnails' );
        /**
         * Register Custom Navigation Walker
         */
        function register_navwalker(){
            require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
            require_once get_template_directory() . '/resources/inc/wp_bootstrap_navwalker.php';
        }
        add_action( 'after_setup_theme', 'register_navwalker' );

    }
endif; // snf_subsidiary_setup
add_action( 'after_setup_theme', 'snf_group_setup' );
/**
 * Enqueue styles.
 */
function snf_group_add_styles()
{
    wp_enqueue_style( 'snf-group-style', get_stylesheet_uri() );
//    wp_enqueue_style( 'snf-global-general-style',  get_template_directory_uri() . '/styles/css/global.scss');
    wp_enqueue_style('boot-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css');
    wp_enqueue_style('Flag Icons', 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css');
    wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', 'screen');
    wp_enqueue_style('snf-adobe-garamond-pro', 'https://use.typekit.net/fws0qwx.css');
    wp_enqueue_style('snf-source-sans-pro', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700&display=swap');
    wp_enqueue_style('snf-esg-lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css');


    /**
     * Conditional Market Style Sheet
     */
    $marketConditional = (is_page_template(array(

        'market-sites/market-home.php',
        'market-sites/market-inner-template.php',
        'market-sites/market-news-template.php',
        'market-sites/market-products-template.php'
    )));
    $args = array(
        'hide_empty' => 0
    );
    $terms = get_terms('markets', $args);
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $custom_field = get_field('market_ready', $term);
            if ($custom_field == 'true') {
                if (has_term('oil-gas', 'markets') && $marketConditional) {
                  wp_enqueue_style('snf-oil-gas-css', get_template_directory_uri() . "/styles/css/markets/oil-gas.css");
                }
                if (has_term('personal-care', 'markets') && $marketConditional || (is_page_template('markets/markets-search.php'))) {
                    wp_enqueue_style('snf-pc-css', get_template_directory_uri() . "/styles/css/markets/personal-care.css");
                }
                if (has_term('home-care-ii', 'markets') && $marketConditional) {
                    wp_enqueue_style('snf-hc-ii-css', get_template_directory_uri() . "/styles/css/markets/home-care.css");
                }
                if (has_term('agriculture', 'markest') && $marketConditional) {
                    wp_enqueue_style('snf-agriculture-css', get_template_directory_uri() . "/styles/css/markets/agriculture.css");
                }
            }
        }
    }
    if ($marketConditional) {
        wp_enqueue_style('markets-standard', get_template_directory_uri() . "/styles/css/markets/markets-default.css");
    }
}
add_action( 'wp_enqueue_scripts', 'snf_group_add_styles' );


/**
 * Conditional Archive Style Sheet
 */
if(!function_exists('archives_page_styles')) :
    function archives_page_styles() {
        if(is_page_template('archive.php')) {
            wp_enqueue_style('archives-page-style', get_template_directory_uri() . '/styles/css/archives-page-style.css'); // standard way of adding style sheets in WP.
        }

    }
endif;
add_action('wp_enqueue_scripts', 'archives_page_styles');



add_filter('style_loader_tag', 'my_style_loader_tag_filter', 10, 2);
function my_style_loader_tag_filter($html, $handle) {
    if ($handle === array('snf-source-sans-pro','font-awesome')) {
        return str_replace("rel='stylesheet'",
            "rel='preload' as='font' type='font/woff2' crossorigin='anonymous'", $html);
    }
    return $html;
}
/**
 * Enqueue scripts
 */
function snf_group_add_scripts() {
    global $wp_scripts;
    wp_register_script( 'html5_shiv', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js', '', '', false );
    wp_register_script( 'respond_js', 'https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js', '', '', false );
    $wp_scripts->add_data( 'html5_shiv', 'conditional', 'lt IE 9' );
    $wp_scripts->add_data( 'respond_js', 'conditional', 'lt IE 9' );
    wp_enqueue_script('snf-contact-form-slide-out-js', get_template_directory_uri() . '/scripts/js/contact-form-slide-out.js', array('jquery'), 'custom', true);
    wp_enqueue_script( 'snf-app-js-defer', get_template_directory_uri() . '/scripts/js/app.js', array('jquery'), 'custom', true );
    wp_enqueue_script('snf-front-page-js',get_template_directory_uri() . '/scripts/js/front-page.js', array('jquery'), 'custom', true);

    wp_enqueue_script('snf-flexible-scripts-js',get_template_directory_uri() . '/scripts/js/flexible.js', array(), false, true );



    if(is_post_type_archive('timeline')){
        wp_enqueue_style('timeline-page-style', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/css/swiper.min.css'); // standard way of adding style sheets in WP.

    }

    // Bootstrap JS CDN
    wp_enqueue_script( 'slim-jquery','https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js', true);
    wp_enqueue_script( 'boot-pooper-js','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', true);
    wp_enqueue_script( 'boot-js','https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js', array('jquery'), true);
    wp_enqueue_script('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), true);
    wp_enqueue_script('snf-lightbox-scripts-js','https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js', array(), '', true  );

}
add_action( 'wp_enqueue_scripts', 'snf_group_add_scripts' );

/**
 * Only Enqueue Specific Scripts for these conditions
 */
function specific_scripts() {
    if( is_404() ){
        wp_enqueue_script('snf-oops-js', get_template_directory_uri() . '/scripts/js/oops.js', array('jquery'), 'custom', true);
    }
}
add_action('wp_enqueue_scripts', 'specific_scripts');

/**
 * Only Enqueue Specific Styles for these conditions
 */
if(!function_exists('archives_page_styles')) :
    function archives_page_styles() {
        if(is_page_template('tmpl_archives.php')) {
            wp_enqueue_style('archives-page-style', get_template_directory_uri() . '/styles/css/archives-page-style.css'); // standard way of adding style sheets in WP.
        }
    }
endif;
add_action('wp_enqueue_scripts', 'archives_page_styles');

/**
 * Custom Login Screen
 */
require_once('site-functions/login/custom-login-hook.php');

/**
 * Function for retrieving the Categories for a post/page
 */
function snf_subsidiary_get_cats(){
    $cats_list = get_the_category_list( ', ' );
    if ( $cats_list){
        echo "<span class='tags-links'>Posted in {$cats_list}</span>";
    }
}

/**
 * Function for retrieving the tags for a post/page
 */
function snf_subsidiary_get_tags(){
    $tags_list = get_the_tag_list( ' ', ',' );
    if ( $tags_list ) {
        echo "<span class='tags-links'>Tagged with {$tags_list}</span>";
    }
}

/**
 *  DIY Popular Posts for News and Single Posts
 */
require_once('site-functions/sidebar/popular-posts.php');

/**
 *  Get Related Posts
 */
require_once('site-functions/sidebar/get-related-cpt-post.php');

/**
 *  Adding Excerpt support to pages for search result usage
 */
require_once('site-functions/misc/excerpt-support-for-pages.php');

/**
 *  Display News CPT on the same category page as your default
 */
require_once('site-functions/post-types/news/news-cpt-display-on-category-page.php');


/**
 *  Setting Default Page Template if the user is not an administrator
 */
require_once('site-functions/misc/setting-default-page-template.php');

/**
 *  Removes Comments From Dashboard
 */
require_once('site-functions/dashboard/remove-comments-from-dashboard.php');

/**
 *  Adds Bootstrap Class Img Fluid to all Imgs added to Post Pages
 */
require_once('site-functions/misc/adding-img-fluid-to-img-class.php');

/**
 *  Strips <br /> from WYSIWYG
 */
require_once('site-functions/misc/strip-break-tag-from-wysiwyg.php');

/**
 *  RankMath Breadcrumbs Hook
 */
require_once('site-functions/breadcrumbs/breadcrumbs-function.php');

/**
 *  Dynamic Menu : Nav-Walker
 */
require_once('site-functions/dynamic-menu/nav-walker.php');

/**
 *  Header Shrink Function
 */
require_once('site-functions/dynamic-menu/snf-navigation-shrink.php');

/**
 * General Settings Add-On Fields
 */
require_once('site-functions/dashboard/general-settings-meta-fields.php');

/**
 *  Custom Post Types Register
 */
require_once('site-functions/post-types/news/cpt-news.php');
require_once('site-functions/post-types/products/cpt-products.php');
require_once('site-functions/banner-image/home-slider.php');

/**
 *  Custom Taxonomy Register
 */
require_once('site-functions/taxonomies/register-countries.php');
require_once('site-functions/taxonomies/register-markets.php');

/**
 *  Widget : Dynamic Sidebar
 */
require_once('site-functions/widgets/dynamic-sidebar-register.php');

/**
 * Taxonomy Term Filter
 */
require_once('site-functions/taxonomies/taxonomy-filter.php');

/**
 * Bootstrap Pagination
 */
require_once('site-functions/pagination/bootstrap-pagination.php');

/**
 * Archive Sidebar get by Year
 */
require_once('site-functions/archives/archives-sidebar.php');


/**
 * Archive Sidebar get by Year for Taxonomy Terms
 */
require_once('site-functions/archives/taxonomy-archives-month-year-list.php');



/**
 * Footer Widget Function
 */
require_once('site-functions/footer/footer-widget.php');

/**
 * Subsidiary & Locations Child Pages
 */
require_once('site-functions/subsidiary/subsidiary-child-pages.php');

/**
 *  Subsidiary & Locations Remove WYSIWYG
 *  Force to use ACF Fields
 *  Eliminates content Mess
 */
require_once('site-functions/subsidiary/remove-wysiwyg.php');

/**
 *  When enquing scritps and style function creates
 *  Refrence that will defer to footer
 */
require_once('site-functions/misc/scripts-defer.php');

/**
 *  Archive Query to display Global Communication
 *  posts that have the respective country term and news term
 */
// require_once('site-functions/cpt-query/communication-country-query.php');

/**
 *  WP Menu Hook Add On Functions
 *
 */
require_once('site-functions/dynamic-menu/wp-menu-add-on-hooks.php');

//
//
// add_filter( 'body_class', 'market_body_class' );
// // add classes to body based on custom taxonomy ('sections')
// // examples: section-about-us, section-start, section-nyc
// function market_body_class( $classes ) {
//    global $post;
//     $section_terms = get_the_terms( $post->ID, 'country' );
//    $market_terms = get_the_terms( $post->ID, 'markets' );
//    if ( $market_terms && ! is_wp_error( $market_terms ) ) {
//        foreach ($market_terms as $term) {
//            $classes[] = 'taxonomy';
//            $classes[] = $term->slug;
//
//
//        }
//    }
//     if ( $section_terms && ! is_wp_error( $section_terms ) ) {
//           foreach ($section_terms as $term) {
//               $classes[] = 'taxonomy';
//               $classes[] = $term->slug;
//
//
//           }
//       }
//    return $classes;
// }
/**
 * Customizer additions.
 */
require_once( 'resources/inc/nav-menu-dropdown.php' );
/**
 *  Country Locations Modal Input Text Filter Function
 *  Populate Post / Pages
 */
require_once( 'site-functions/post-types/news/country-input-search-filter.php' );

/**
 *  Get Custom Taxonomy Dropdown Function
 *
 */
require_once( 'site-functions/misc/get-custom-taxonomy-dropdown.php' );

/**
 *  Filter Term Clauses for Global Communication Feed
 *
 */
require_once( 'site-functions/cpt-query/filter-query-pre-get-post.php' );


/**
 *  WYSIWYG ACF Generate Excerpt
 *
 */
require_once( 'site-functions/misc/wysiwyg.php' );


/**
 *  Filter Everything
 *
 */
require_once( 'site-functions/misc/filter-everything-pro-hooks.php' );

function theme_slug_filter_wp_title( $title ) {
    if ( is_404() ) {
        $title = 'Whoops Something is not right';
    }
    // You can do other filtering here, or
    // just return $title
    return $title;
}
// Hook into wp_title filter hook
add_filter( 'wp_title', 'theme_slug_filter_wp_title' );

/**
 *  Gravity Forms Bootstrap Styling Overwrite
 *
 */

add_filter( 'gform_submit_button', 'fe_gravity_forms_btn_classes', 10, 2 );

/**
 * Replace Gravity Forms button classes with Bootstrap button classes.
 */
// function fe_gravity_forms_btn_classes( $button, $form ) {
//     return str_replace( 'gform_button button', 'btn btn-primary', $button );
// }

add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );
function form_submit_button( $button, $form ) {
    return "<div class='snf-link-wrapper gform_button ' id='gform_submit_button_{$form['id']}'><div class='snf-link'><span class='product-list-link'>Submit</span></div></div>";
}

/**
 *  Test *
 */
 
function snf_check_page_market_tax(){
	global $post;
	$term_array = array();
	$term_list = wp_get_post_terms($post->ID, 'markets', array(
	        "fields" => "all",
	        'orderby' => 'parent',
	        'order' => 'ASC'
	    )
	);

		
		foreach ( $term_list as $term_single ) {
			$term_array[] = $term_single->name; //do something here
		}
		return $term_array;


	                                                 
}

/**
 * Check Market Pages and Retrives the market selected on the Backend of WP
 */
function wpdocs_custom_taxonomies_terms_links() {
    // Get post by post ID.
    if ( ! $post = get_post() ) {
        return '';
    }
    // Get post type by post.
    $post_type = $post->post_type;
    // Get post type taxonomies.
    $taxonomies = get_object_taxonomies( $post_type, 'markets' );
    $out = array();
    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
        // Get the terms related to post.
        $terms = get_the_terms( $post->ID, $taxonomy_slug );
        if ( ! empty( $terms ) ) {?>
    <?php  $out[] = "<div class='card-header heading'>"; ?>
            <?php foreach ( $terms as $term ):?>
              <?php  $out[] = sprintf( '<a href="%1$s"><h4 class="display-5">'.  $term->name .'</h4></a>',
                    esc_url( get_term_link( $term->slug, $taxonomy_slug ) ),
                    esc_html( $term->name )
                );?>
            <?php endforeach;?>
             <?php  $out[] = "</div>";
        }
    }
    return implode( '', $out );
}

/**
 * On WP Dashboard this Removes the Welcome Quick Edit Modal
 */
remove_action('welcome_panel', 'wp_welcome_panel');

/**
 * Adds Categories on Pages for ACF Selection Sake
 */
function myplugin_settings() {

    // Add category metabox to page
    register_taxonomy_for_object_type('category', 'page');
}
/**
 * Add to the admin_init hook of your theme functions.php file
 */

add_action( 'init', 'myplugin_settings' );





/**
 * Customizer additions.
 */
require get_template_directory() . '/resources/inc/customizer.php';