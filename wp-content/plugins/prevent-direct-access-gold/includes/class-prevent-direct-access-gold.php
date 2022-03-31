<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda_gold
 * @since      1.0.0
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
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
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class Prevent_Direct_Access_Gold {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Prevent_Direct_Access_Gold_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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
		if ( defined( 'PDA_GOLD_V3_VERSION' ) ) {
			$this->version = PDA_GOLD_V3_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'prevent-direct-access-gold-v3';

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
	 * - Prevent_Direct_Access_Gold_Loader. Orchestrates the hooks of the plugin.
	 * - Prevent_Direct_Access_Gold_i18n. Defines internationalization functionality.
	 * - Prevent_Direct_Access_Gold_Admin. Defines all hooks for the admin area.
	 * - Prevent_Direct_Access_Gold_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-i18n.php';

		/**
		 * Ymese plugin's sdk
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'yme-wp-plugins-sdk/require.php';

		/**
		 * Package background processing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		/**
		 * The class move files on background processing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/async-task/class-pda-move-files-after-deactivate.php';

		/**
		 * The class move files on background processing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/async-task/class-pda-move-files-after-activate.php';

		/**
		 * The class activate all sites on background processing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/async-task/class-pda-activate-all-sites.php';

		/**
		 * Plugin update checker
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'yme-plugin-update-checker/plugin-update-checker.php';

		/**
		 * The class responsible for defining constants
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-constants.php';

		/**
		 * The interface for log handler.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/interfaces/interface-prevent-direct-access-log-handler.php';

		/**
		 * The abstract class for log level.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/abstract-prevent-direct-access-log-handler.php';

		/**
		 * The abstract class for log handler.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/abstract-prevent-direct-access-log-handler.php';

		/**
		 * The file log handler class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/log-handlers/class-prevent-direct-access-log-handler-file.php';

		/**
		 * Interface PDA Logger
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/interfaces/interface-prevent-direct-access-logger.php';

		/**
		 * Abstract PDA Logger
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/abstracts/abstract-prevent-direct-access-log-levels.php';

		/**
		 * The PDA Logger class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-logger.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/public/class-prevent-direct-access-gold-hooks.php';
		/**
		 * The class responsible for useful services
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-services.php';

		/**
		 * The class responsible for useful functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-function.php';

		/**
		 * The class responsible for rewrite rules checking
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-rewrite-rules-checker.php';

		/**
		 * Video Utils
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-videos-util.php';

		/**
		 * The class responsible for file handling
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-file-handler.php';

		/**
		 * The helper class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-helpers.php';

		/**
		 * The class responsible for htaccess functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-htaccess.php';

		/**
		 * The class responsible for db functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-db.php';

		/**
		 * The class responsible for repository
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-repository.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-status.php';

		/**
		 * The class responsible for public private link services
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/public/class-prevent-direct-access-gold-private-link-service.php';

		/**
		 * The class responsible for public original link services
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/public/class-prevent-direct-access-gold-original-link-service.php';

		/**
		 * The class responsible for API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-api.php';

		/**
		 * The class responsible for Update Service
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-update-services.php';

		/**
		 * The class responsible for plugin changes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-plugin-changes.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-prevent-direct-access-gold-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-prevent-direct-access-gold-public.php';

		/**
		 * The class responsible for rendering views
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-helper-tab.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/public/class-prevent-direct-access-gold-hooks.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-constants.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-logger.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-setting-widgets.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-affiliate.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cronjob-handlers/class-prevent-direct-access-cronjob-handler.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/private/class-prevent-direct-access-gold-private-hooks.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-add-media.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/class-prevent-direct-access-gold-validators.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/class-prevent-direct-access-gold-response-types.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/integrations/class-prevent-direct-access-wpml.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/integrations/class-prevent-direct-access-polylang.php';

		/**
		 * The class responsible for public shortcode services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-prevent-direct-access-gold-shortcode-service.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/modules/Files/Crypto.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/modules/Files/Loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/modules/Files/Service.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/modules/Files/Util.php';

		// We need plugin.php!
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->loader = new Prevent_Direct_Access_Gold_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Prevent_Direct_Access_Gold_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Prevent_Direct_Access_Gold_i18n();

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

		$plugin_admin = new Prevent_Direct_Access_Gold_Admin( $this->get_plugin_name(), $this->get_version() );
		register_shutdown_function( array( $plugin_admin, 'log_errors' ) );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'pda_register_log_handlers', $plugin_admin, 'pda_register_default_log_handler' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'pda_db_handle', 10 );
		$this->loader->add_action( 'wp_ajax_pda_gold_update_general_settings', $plugin_admin, 'pda_gold_update_general_settings' );
		$this->loader->add_action( 'wp_ajax_pda_gold_recheck_license', $plugin_admin, 'pda_gold_recheck_license' );
		$this->loader->add_action( 'wp_ajax_pda_gold_migrate_data', $plugin_admin, 'pda_gold_migrate_data' );
		$this->loader->add_action( 'wp_ajax_pda_gold_check_htaccess', $plugin_admin, 'pda_gold_check_htaccess' );
		$this->loader->add_action( 'wp_ajax_pda_gold_enable_raw_url', $plugin_admin, 'pda_gold_enable_raw_url' );
		$this->loader->add_action( 'wp_ajax_pda_gold_activate_all_sites', $plugin_admin, 'pda_gold_activate_all_sites' );
		$this->loader->add_action( 'wp_ajax_pda_gold_activated_statistics', $plugin_admin, 'pda_gold_activated_statistics' );
		$this->loader->add_filter( 'plugin_action_links_' . PDA_PLUGIN_BASE_NAME, $plugin_admin, 'handle_plugin_links', 30 );
		$this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'handle_plugin_updates', 10, 2 );

		$have_licensed = get_option( PDA_v3_Constants::LICENSE_OPTIONS );
		if ( $have_licensed ) {
			$this->loader->add_filter( 'wp_generate_attachment_metadata', $plugin_admin, 'pda_custom_upload_filter', 999, 2 );

			$this->loader->add_action( 'rest_api_init', $plugin_admin, 'pda_rest_api_init_cb', 10, 2 );
			add_filter( 'mod_rewrite_rules', 'Prevent_Direct_Access_Gold_Htaccess::pda_handle_htaccess_rewrite_rules', 9999, 2 );

			if ( Pda_Gold_Functions::is_fully_activated() ) {
				$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'update_default_settings' );
				$this->loader->add_filter( 'manage_upload_columns', $plugin_admin, 'pda_add_upload_columns', 10, 2 );
				$this->loader->add_action( 'manage_media_custom_column', $plugin_admin, 'pda_media_custom_column', 10, 2 );
				$this->loader->add_filter( 'bulk_actions-upload', $plugin_admin, 'pda_custom_bulk_actions', 10, 2 );
				$this->loader->add_filter( 'handle_bulk_actions-upload', $plugin_admin, 'pda_bulk_action_handler', 10, 3 );
				$this->loader->add_filter( 'wp_prepare_attachment_for_js', $plugin_admin, 'decorate_media_grid_view', 10, 3 );
				$this->loader->add_filter( 'admin_init', $plugin_admin, 'handle_plugin_changes', 10, 3 );
				$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'plugins_loaded', 10 );
			}

			$this->loader->add_action( 'wp_ajax_pda_update_file_access_permission', $plugin_admin, 'pda_update_file_access_permission' );

			$this->loader->add_action( 'wp_ajax_update_ip_block', $plugin_admin, 'so_wp_ajax_update_ip_block' );
			$this->loader->add_action( 'wp_ajax_pda_gold_subscribe', $plugin_admin, 'pda_ajax_pda_gold_subscribe' );
			$this->loader->add_action( 'wp_head', $plugin_admin, 'add_no_index_meta' );
			$this->loader->add_filter( 'robots_txt', $plugin_admin, 'pda_v3_custom_robots_txt', 10, 2 );
			$this->loader->add_action( 'add_meta_boxes_attachment', $plugin_admin, 'add_custom_setting_metabox' );
			//User delete site, drop table
			$this->loader->add_action( 'delete_blog', $plugin_admin, 'delete_table_site_website', 10, 2 );

			//Filter file protected or un-protected
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'restrict_manage_protected_media', 10, 1 );
			$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'modify_protected_media', 10, 1 );

			$this->loader->add_filter( 'attachment_fields_to_edit', $plugin_admin, 'add_checkbox_protect_file', 10, 2 );
			$this->loader->add_filter( 'attachment_fields_to_save', $plugin_admin, 'save_file_file_attachment_edit', null, 2 );

			$this->loader->add_filter( 'wp_get_attachment_url', $plugin_admin, 'change_file_url_in_media', 99, 2 );
			$this->loader->add_filter( 'wp_get_attachment_metadata', $plugin_admin, 'handle_attachment_metadata', 99, 2 );

			$this->loader->add_filter( 'admin_body_class', $plugin_admin, 'add_body_classes_for_quick_tour' );

			$this->loader->add_action( 'post-upload-ui', $plugin_admin, 'add_checkbox_auto_protect_file' );
			$this->loader->add_action( 'admin_footer-media-new.php', $plugin_admin, 'add_js_for_auto_protect_file' );
			$this->loader->add_filter( 'media_row_actions', $plugin_admin, 'add_button_handle_protect_or_unprotect', 10, 3 );
			$this->loader->add_filter( 'qtranslate_language_detect_redirect', $plugin_admin, 'jetpack_no_qtranslate_rest_url_redirect', 10, 3 );


			// Remove WordPress Version Number.
			$this->loader->add_filter( 'the_generator', $plugin_admin, 'luke_remove_version' );

			$this->loader->add_action( 'admin_init', $plugin_admin, 'pda_handle_update_services', 10 );
			// Add css and js for popup add media.
			$this->loader->add_action( 'wp_enqueue_media', $plugin_admin, 'pda_add_script_for_check_box_auto_protect_file' );
		} else {
			// These hooks run only when the license hasn't activated yet.
			$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'register_plugins_links', 10, 2 );
		}


		$this->loader->add_action( 'admin_menu', $plugin_admin, 'Prevent_Direct_Access_Gold_create_plugin_menu' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'Prevent_Direct_Access_Gold_admin_notices' );
		$this->loader->add_action( 'wp_ajax_Prevent_Direct_Access_Gold_Check_Licensed', $plugin_admin, 'Prevent_Direct_Access_Gold_Check_Licensed' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'save_product_id_to_option' );
		$this->loader->add_action( 'ninja_forms_after_submission', $plugin_admin, 'handle_ninja_forms_after_submission' );
		$this->loader->add_action( 'pda_cleanup_logs', $plugin_admin, 'pda_cleanup_logs' );
		$this->loader->add_filter( 'the_content', $plugin_admin, 'replace_protected_file', 99999 );
		$this->loader->add_filter( PDA_v3_Constants::PDA_THE_CONTENT_HOOK, $plugin_admin, 'pda_handle_link_of_the_content', PDA_v3_Constants::SEARCH_AND_REPLACE_LEVEL['PDA_GOLD'], 2 );
		$this->loader->add_filter( PDA_v3_Constants::PDA_BEFORE_HANDLE_SR_HOOK, $plugin_admin, 'pda_before_handle_the_content', 10, 2 );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'pda_custom_intervals' );
		$this->loader->add_action( PDA_v3_Constants::PDA_LS_CRON_JOB_NAME, $plugin_admin, 'pda_ls_cron_exec' );
		$this->loader->add_action( PDA_v3_Constants::PDA_DELETE_EXPIRED_PRIVATE_LINK_CRON_JOB_NAME, $plugin_admin, 'pda_delete_expired_private_links_cron_exec' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'load_notice_dismiss_script');
		if ( is_multisite() ) {
			// TODO: wpmu_new_blog is deprecated since version 5.1.0. Need to replace by wp_insert_site instead.
			$this->loader->add_filter( 'wpmu_new_blog', $plugin_admin, 'pda_auto_activate_new_site', 10, 4 );
		}

		$this->loader->add_filter( 'before_render_pda_column', $plugin_admin, 'before_render_pda_column', 10, 2 );
		$this->loader->add_filter( 'pda_custom_handle_protected_file', $plugin_admin, 'before_handle_protected_file', 1, 2 );


		$this->loader->add_action( 'admin_init', $plugin_admin, 'pda_handle_upgrade_extension' );

		/**
		 * Handle with S&R case:
		 *      Image src: 'https://localhost/wordpress/wp-json/uploads/2020/04/test.jpg
		 *      File is protected
		 *      Image metadata's filename is: _pda/2020/04/test.jpg
		 * Expect:
		 *      test.jpg exists in Image src (https://localhost/wordpress/wp-json/uploads/2020/04/test.jpg) so need to get basename of file.
		 */
		$this->loader->add_filter( 'wp_calculate_image_srcset_meta', $plugin_admin, 'pda_handle_calculate_image_srcset_meta', 5, 4 );
		$this->loader->add_filter( 'wp_calculate_image_srcset', $plugin_admin, 'pda_handle_calculate_image_srcset', 5, 5 );


		$this->loader->add_filter( 'puc_request_info_options-prevent-direct-access-gold', $plugin_admin, 'puc_add_token_to_headers', 10, 1 );

		$update_msg_hook = 'in_plugin_update_message-' . basename( PDA_V3_BASE_DIR ) . '/' . PDA_BASE_NAME;
		$this->loader->add_action( $update_msg_hook, $plugin_admin, 'plugin_update_msg_cb', 10, 2 );

		$this->loader->add_filter( 'puc_request_info_result-prevent-direct-access-gold', $plugin_admin, 'puc_handle_result', 10, 1 );

		$loader = new \PDAGOLD\modules\Files\Loader();
		$loader->register();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Prevent_Direct_Access_Gold_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		if ( get_option( PDA_v3_Constants::LICENSE_OPTIONS ) ) {
			$this->loader->add_action( 'init', $plugin_public, 'pda_s3_handle_protected_file' );
			$this->loader->add_action( 'init', $plugin_public, 'pda_s3_handle_download_log_file' );
		}
		$this->loader->add_shortcode( 'pda_private_link', $plugin_public, 'generate_pda_private_link_shortcode' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
		$this->create_files();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Prevent_Direct_Access_Gold_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	private function create_files() {
		// Bypass if filesystem is read-only and/or non-standard upload system is used.
		if ( apply_filters( 'pda_install_skip_create_files', false ) ) {
			return;
		}

		$files = array(
			array(
				'base'    => PDA_LOG_DIR,
				'file'    => '.htaccess',
				'content' => 'deny from all',
			),
			array(
				'base'    => PDA_LOG_DIR,
				'file'    => 'index.html',
				'content' => '',
			),
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
				if ( $file_handle ) {
					fwrite( $file_handle, $file['content'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite
					fclose( $file_handle ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
				}
			}
		}
	}
}
