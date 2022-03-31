<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.buildwps.com/
 * @since      1.0.0
 *
 * @package    Wp_Pda_Stats
 * @subpackage Wp_Pda_Stats/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Pda_Stats
 * @subpackage Wp_Pda_Stats/includes
 * @author     BWPS <hello@ymese.com>
 */
class Wp_Pda_Stats {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Pda_Stats_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_PDA_STATS_VERSION' ) ) {
			$this->version = WP_PDA_STATS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'PDA Download Link Statistics';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Pda_Stats_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Pda_Stats_i18n. Defines internationalization functionality.
	 * - Wp_Pda_Stats_Admin. Defines all hooks for the admin area.
	 * - Wp_Pda_Stats_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-pda-stats-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-pda-stats-public.php';

		/**
		  * Required
		**/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'yme-wp-plugins-sdk/require.php';

		/*
		 * Database
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-db.php';
		/**
		 * repository
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-repository.php';

        /**
		 * PPW repository
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-ppw-repository.php';

		/**
		 * constants
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-constants.php';

        /**
		 * services
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-service.php';

        /**
		 * helpers
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-helpers.php';

        /**
		 * API
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-api.php';

        /**
		 * PDA Services
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-pda-stats-services-for-pda.php';

        $this->loader = new Wp_Pda_Stats_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Pda_Stats_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Pda_Stats_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Pda_Stats_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'check_plugin_pda_activate' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_submenu_pda', 20 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'upgrade_plugin' );

		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'setup_yme_plugin_rest_api_stats' );
		$this->loader->add_action( 'pda_post_id', $plugin_admin, 'get_post_id' );

		$this->loader->add_action( 'insert_country', $plugin_admin, 'insert_country_name' );

		$this->loader->add_action( 'delete_blog', $plugin_admin, 'delete_table_site_website', 10, 2 );

		$this->loader->add_action( 'pda_before_return_link', $plugin_admin, 'insert_data_for_pda_stats', 10, 4 );

		$this->loader->add_action( 'ppwp_after_check_valid_password', $plugin_admin, 'ppwp_after_check_valid_password', 10, 1 );

		$this->loader->add_action( 'ppwp_entire_site_after_check_valid_password', $plugin_admin, 'stats_for_entire_password', 10, 1 );

		$this->loader->add_action( 'ppwp_pcp_after_check_valid_password', $plugin_admin, 'stats_for_pcp', 10, 1 );

		$this->loader->add_action( 'ppwp_al_after_check_valid_password', $plugin_admin, 'stats_for_al', 10, 1 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Pda_Stats_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Pda_Stats_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
