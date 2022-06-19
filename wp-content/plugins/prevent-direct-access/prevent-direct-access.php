<?php
/*
Plugin Name: Prevent Direct Access
Plugin URI: https://preventdirectaccess.com
Description: Prevent Direct Access provides a simple solution to prevent Google indexing as well as the public from accessing your files without permission. This plugin is required for our Gold version to work properly.
Version: 2.8.1
Author: BWPS
Author URI: https://preventdirectaccess.com
Tags: files, management
License: GPL
Text Domain: prevent-direct-access
Domain Path: /languages

*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include 'includes/repository.php';
include 'includes/js-loader.php';
include 'includes/helper.php';
include 'includes/setting.php';
include 'includes/settings_page.php';
include 'includes/handle.php';
include 'includes/pda_lite_api.php';
include 'includes/constants.php';
include 'includes/pda_lite_affiliate.php';

require_once dirname( __FILE__ ) . '/includes/function.php';
include dirname( __FILE__ ) . '/includes/db-init.php';
define( 'PDA', __FILE__ );
define( 'PDA_HOME_PAGE', 'https://preventdirectaccess.com/?utm_source=user-website&utm_medium=%s&utm_campaign=%s' );
define( 'PDA_DOWNLOAD_PAGE', 'https://preventdirectaccess.com/pricing/?utm_source=user-website&amp;utm_medium=settings&amp;utm_campaign=sidebar-cta' );
define( 'PDA_TEXTDOMAIN', 'prevent-direct-access' );
define( 'PDAF_VERSION', '2.8.1' );
define( 'PDA_LITE_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'PDA_LITE_BASE_DIR', plugin_dir_path( __FILE__ ) );

require_once PDA_LITE_BASE_DIR . '/includes/modules/Grid_View/loader.php';
require_once PDA_LITE_BASE_DIR . '/includes/modules/Grid_View/service.php';

class Pda_Admin {

	private $pda_function;
	private $db;

	function __construct() {

		add_action( 'wp_footer', array( $this , 'prevent_right_click') );
		/**
		 * Do not support multisite mode when PDA Gold has never entered the valid license.
		 */
		if ( ! get_option( 'pda_is_licensed' ) && is_multisite() ) {
			add_action( 'admin_notices', array( $this, 'multisite_admin_notices' ) );
			add_action( 'network_admin_notices', array( $this, 'multisite_admin_notices' ) );
			// return;
		}

		$this->pda_function = new Pda_Function();
		$this->db           = new Pda_Database();
		$Pda_JS_Loader      = new Pda_JS_Loader( PDA_TEXTDOMAIN, PDAF_VERSION );

		if ( get_option( 'pda_is_licensed' ) ) {
			register_uninstall_hook( __FILE__, array( 'Pda_Admin', 'remove_options_when_uninstalling_plugin' ) );
			add_action( 'admin_init', array( $this, 'handle_flush_rewrite_rules' ) );

			return;
		}

		add_filter( 'mod_rewrite_rules', array( $this, 'htaccess_contents' ) );
		// TODO: Hide protected file later.
		add_filter('pre_get_posts', array($this, 'hide_posts_media_by_other'));

		$this->identifyFeatures();
		add_action( 'admin_enqueue_scripts', array( $Pda_JS_Loader, 'admin_load_js' ) );
		add_action( 'wp_ajax_myaction', array( $this, 'so_wp_ajax_function' ) );
		add_action( 'wp_ajax_regenerate-url', array( $this, 'so_wp_ajax_regenerate_url' ) );
		add_action( 'wp_ajax_pda_lite_update_general_settings', array( $this, 'pda_lite_update_general_settings' ) );
		add_action( 'wp_ajax_pda_lite_update_ip_restriction_settings', array( $this, 'pda_lite_update_ip_restriction_settings' ) );
		add_action( 'wp_ajax_pda_free_subscribe', array( $this, 'pda_ajax_pda_free_subscribe' ) );

		add_action( 'delete_post', array( $this, 'delete_prevent_direct_access' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'init', array( $this, 'my_endpoint' ) );
		add_action( 'admin_init', array( $this, 'check_htaccess_updated' ) );
		add_action( 'parse_query', array( $this, 'parse_query' ) );
		add_action( 'wp_ajax_pda_subscribe', array( $this, 'pda_ajax_subscribe' ) );
		add_action( 'admin_menu', array( $this, 'pda_add_settings_page' ) );

		register_activation_hook( __FILE__, array( $this, 'plugin_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( 'Pda_Admin', 'plugin_uninstall' ) );
		add_filter( 'plugin_row_meta', array( $this, 'register_plugins_links' ), 10, 2 );
		add_filter( 'robots_txt', array( $this, 'pda_custom_robots_txt' ), 10, 2 );

		add_action( 'the_posts', array( $this, 'modify_protected_media' ), 10 );
		add_action( 'plugins_loaded', array( $this, 'pda_load_text_domain' ) );

		add_action( 'upgrader_process_complete', array( $this, 'create_new_table_and_migrate_data' ), 10, 2 );

		add_action( 'rest_api_init', array( $this, 'pda_rest_api_init_cb' ), 10, 2 );

		pda_add_defaults_fn();

		$grid_view_module = new PDAFree\modules\Grid_View\Loader( $this );
		$grid_view_module->register();
	}

	public function hide_posts_media_by_other($query) {
		global $pagenow;
		$pda_option = get_option( 'FREE_PDA_SETTINGS' );
		if ( is_array( $pda_option ) && array_key_exists( 'hide_protected_files_in_media', $pda_option ) && $pda_option['hide_protected_files_in_media'] === "on" ) {
			if( !Pda_Helper::is_admin_user_role() ) {
				global $user_ID;
				$query->set('author', $user_ID );
			}
		}
		return $query;
	}

	/**
	 * Remove options when uninstall plugin if PDA Gold is entered license.
	 */
	public static function remove_options_when_uninstalling_plugin() {
		delete_option( 'pda_free_is_rewrite_rules' );
	}

	/**
	 * Flush rewrite rules if PDA Free update to new version.
	 */
	public function handle_flush_rewrite_rules() {
		if ( defined( 'PDA_GOLD_V3_VERSION' ) && ! get_option( 'pda_free_is_rewrite_rules' ) ) {
			flush_rewrite_rules();
			update_option( 'pda_free_is_rewrite_rules', '1' );
		}
	}

	public function check_protected_file( $id ) {
		$protected      = new Repository;
		$protected_file = $protected->get_status_advance_file_by_post_id( $id, true );
		if ( isset( $protected_file ) && $protected_file->is_prevented === "1" ) {
			$result = '<i class="dashicons dashicons-yes protected_yes" style="color: green"></i>';
		} else {
			$result = '<i class="dashicons dashicons-no protected_no" style="color: red"></i>';
		}

		return $result . '<style>
							.media-types.media-types-required-info {
								display: none;
							}
						</style>';
	}

	function add_filed_attachment( $form_fields, $post ) {
		$yes_or_no     = $this->check_protected_file( $post->ID );
		$form_fields[] = array(
			'input' => 'html',
			'label' => 'Protected',
			'html'  => $yes_or_no,
		);

		return $form_fields;
	}

	public function register_plugins_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			$links[] = '<a style="color: #cc0000 ; font-weight: bold;" href=' . sprintf( constant( 'PDA_HOME_PAGE' ), 'pluginpage', 'plugin-upgrade-link' ) . '>' . __( 'Upgrade to Gold Version', 'prevent-direct-access' ) . '</a>';
//			TODO: Comment notice in description
//			array_push( $links, $this->add_support_form());
		}

		return $links;
	}

	public function add_support_form() {
//		TODO: Comment notice in description
//		include_once ('includes/partials/subscribe.php');
	}

	public function identifyFeatures() {
		add_action( 'manage_media_custom_column', array( $this, 'media_custom_columns' ), 0, 2 );
		add_filter( 'manage_upload_columns', array( $this, 'free_add_upload_columns' ) );
	}

	public function my_endpoint() {
		$configs  = Pda_Helper::get_plugin_configs();
		$endpoint = $configs['endpoint'];
		add_rewrite_endpoint( $endpoint, EP_ROOT );
	}

	public function parse_query( $query ) {
		$configs  = Pda_Helper::get_plugin_configs();
		$endpoint = $configs['endpoint'];
		if ( isset( $query->query_vars[ $endpoint ] ) ) {
			include( plugin_dir_path( __FILE__ ) . '/download.php' );
			exit;
		}
	}

	public function check_htaccess_updated() {
		$htaccess_writable = $this->pda_function->htaccess_writable();

		$plugin           = plugin_basename( __FILE__ );
		$is_plugin_active = is_plugin_active( $plugin );
		if ( $htaccess_writable !== true && $is_plugin_active ) {
			delete_option( 'updated_htaccess_success' );
		}

		$updated_htaccess_success = get_option( 'updated_htaccess_success', false );
		if ( $updated_htaccess_success === true ) {
			return;
		}

		if ( $htaccess_writable === true && $is_plugin_active ) {
			flush_rewrite_rules(); // re-trigger mod_rewrite_rules
			add_option( 'updated_htaccess_success', true );
		}
	}

	public function admin_notices() {
		global $pagenow, $is_apache;

		if ( $pagenow == 'plugins.php' || $pagenow == 'upload.php' ) {
			$is_htaccess_writable = $this->pda_function->htaccess_writable();

			$plugin = plugin_basename( __FILE__ );
			if ( $is_apache && $is_htaccess_writable !== true && is_plugin_active( $plugin ) ) {
				?>
				<div class="error is-dismissible notice">
					<p><b><?php echo __( 'Prevent Direct Access: ', 'prevent-direct-access' ); ?></b> If your <b>.htaccess</b>
						file were writable, we could do this automatically, but it isn’t. So you must either make it
						writable or manually update your .htaccess with the mod_rewrite rules found under <b>Settings >>
							Permalinks</b>. Until then, the plugin can't work yet. </p>
				</div>
				<?php
			}

			if ( is_plugin_active( 'json-rest-api/plugin.php' ) ) {
				// plugin is activated.
				?>
				<div class="error is-dismissible notice">
					<p><b><?php echo _e( "Prevent Direct Access: ", 'prevent-direct-access' ); ?></b> You are using WP
						REST API. Please update to WordPress REST API (Version 2)
						(https://wordpress.org/plugins/rest-api/) </p>
				</div>
				<?php
			}
		}
	}

	/**
	 * Get IP Block rewrite rules.
	 *
	 * @return string
	 */
	public function get_ip_lock() {
		$pda_settings_ip = get_option( 'FREE_PDA_SETTINGS_IP', false );
		$new_rule        = '';

		if ( false === $pda_settings_ip || ! is_array( $pda_settings_ip ) ) {
			return $new_rule;
		}
		$str_ip_lock = $pda_settings_ip['ip_lock'];
		$arr_ip_lock = explode( ";", $str_ip_lock );
		if ( $arr_ip_lock[0] != null ) {
			// $newRule = "ORDER ALLOW,DENY". PHP_EOL;
			$ip = array( '*.*.*.*', '*.*.*', '*.*', '*' );
			for ( $i = 0; $i < count( $arr_ip_lock ); $i ++ ) {
				// $newRule .= "DENY FROM ".str_replace($ip, '', $arr_ip_lock[$i]). PHP_EOL;
				$new_rule .= "RewriteCond %{REMOTE_ADDR} !^" . str_replace( $ip, '', $arr_ip_lock[ $i ] ) . PHP_EOL;
			}

			// $newRule .= "ALLOW FROM ALL". PHP_EOL;
			return $new_rule;
		}

		return $new_rule;
	}

	public function htaccess_contents( $rules ) {
		// If we don't check condition, when Gold enters license and flush the rules. It will run this function and add the Free rules.
		if ( get_option( 'pda_is_licensed' ) ) {
			return $rules;
		}

		// eg. index.php?pre_dir_acc_61co625547=$1 [R=301,L]
		$configs              = Pda_Helper::get_plugin_configs();
		$endpoint             = $configs['endpoint'];
		$downloadFileRedirect = str_replace( trailingslashit( site_url() ), '', 'index.php' ) . "?{$endpoint}=$1 [L]" . PHP_EOL;

		// $newRule .= "RewriteCond %{REMOTE_HOST} !^192.168.1.2$" . PHP_EOL;
		$newRule = $this->get_ip_lock();
		$newRule .= "# Prevent Direct Access Rewrite Rules" . PHP_EOL;
		$newRule .= "RewriteRule private/([a-zA-Z0-9]+)$ " . $downloadFileRedirect;
		$newRule .= "RewriteCond %{REQUEST_FILENAME} -s" . PHP_EOL;
		$newRule .= "RewriteCond %{HTTP_USER_AGENT} !facebookexternalhit/[0-9]" . PHP_EOL;
		$newRule .= "RewriteCond %{HTTP_USER_AGENT} !Twitterbot/[0-9]" . PHP_EOL;
		$newRule .= "RewriteCond %{HTTP_USER_AGENT} !Googlebot/[0-9]" . PHP_EOL;

		$directAccessPath = str_replace( trailingslashit( site_url() ), '', 'index.php' ) . "?{$endpoint}=$1&is_direct_access=true&file_type=$2 [QSA,L]" . PHP_EOL;
		// eg. RewriteRule wp-content/uploads(/[a-zA-Z_\-\s0-9\.]+)+\.([a-zA-Z0-9]+)$ index.php?pre_dir_acc_61co625547=$1&is_direct_access=true&file_type=$2 [QSA,L]
		$upload_dir_url = str_replace( "https", "http", wp_upload_dir()['baseurl'] );
		$site_url       = str_replace( "https", "http", site_url() );
		$newRule        .= "RewriteRule " . str_replace( trailingslashit( $site_url ), '', $upload_dir_url ) . "/_pda" . "(\/[A-Za-z0-9_@.\/&+-]+)+\.([A-Za-z0-9_@.\/&+-]+)$ " . $directAccessPath;
		$newRule        .= "# Prevent Direct Access Rewrite Rules End" . PHP_EOL;

		$hot_linking_rules = $this->generate_hot_linking_rules();
		$newRule           .= $hot_linking_rules . PHP_EOL;

		$option_index = $this->add_option_indexes_rule( $rules );

		return $newRule . $rules . $option_index . PHP_EOL;
	}

	/**
	 * Generate image hot linking rules for htaccess file follow feature "Prevent Image Hotlinking" in setting page
	 * If enable feature "Prevent Image Hotlinking" => write rules
	 *
	 * @return string
	 */
	private function generate_hot_linking_rules() {
		$pda_settings     = get_option( PDA_Lite_Constants::OPTION_NAME );
		$not_render_rules = false === $pda_settings || ! is_array( $pda_settings ) || ! array_key_exists( 'enable_image_hot_linking', $pda_settings ) || 'on' !== $pda_settings['enable_image_hot_linking'];
		if ( $not_render_rules ) {
			return '';
		}

		$domain = home_url( '/', is_ssl() ? 'https' : 'http' );
		$rules  = array(
			'# Prevent Direct Access Prevent Hotlinking Rules',
			'RewriteCond %{HTTP_REFERER} !^$',
			"RewriteCond %{HTTP_REFERER} !^$domain [NC]",
			'RewriteRule \.(gif|jpg|jpeg|bmp|zip|rar|mp3|flv|swf|xml|png|css|pdf)$ - [F]',
			'# Prevent Direct Access Prevent Hotlinking Rules End',
			'',
		);

		return implode( "\n", $rules );
	}

	private function add_option_indexes_rule( $rules ) {
		$pda_settings_ip = get_option( 'FREE_PDA_SETTINGS' );

		$enable_directory_listing = is_array( $pda_settings_ip )
		                            && array_key_exists( 'enable_directory_listing', $pda_settings_ip )
		                            && $pda_settings_ip['enable_directory_listing'] === 'on';
		$option_index             = strpos( $rules, "Options -Indexes" ) === false && $enable_directory_listing ? "Options -Indexes" : '';

		return $option_index;
	}

	public function add_upload_columns( $columns ) {
		$is_htaccess_writable = $this->pda_function->htaccess_writable();
		if ( $is_htaccess_writable === true ) {
			$columns['direct_access'] = '<a href="#" status="true">Prevent Direct Access</a>';
		}

		return $columns;
	}

	public function media_custom_columns( $column_name, $id ) {
		$repository   = new Repository;
		$post         = get_post( $id );
		// Change the logic to check whether the protected file which is not using the private links.
		$checked      = $repository->is_protected_file( $post->ID );
		$pda_class    = $checked ? '' : PDA_Lite_Constants::PDA_LITE_CLASS_FOR_FILE_UNPROTECTED;
		$pda_icon     = $checked ? '<i class="fa fa-check-circle" aria-hidden="true"></i>' : '<i class="fa fa-times-circle" aria-hidden="true"></i>';
		$pda_text     = $checked ? PDA_Lite_Constants::PDA_LITE_FILE_PROTECTED : PDA_Lite_Constants::PDA_LITE_FILE_UNPROTECTED;
		$title_text   = $checked ? PDA_Lite_Constants::PDA_LITE_TITLE_FOR_FILE_PROTECTED : PDA_Lite_Constants::PDA_LITE_TITLE_FOR_FILE_UNPROTECTED;
		if ( $column_name == 'direct_access' ) {
			?>
			<div id="pda-v3-column_<?php echo esc_attr( $post->ID ); ?>" class="pda-gold-v3-tools">
				<p id="pda-v3-wrap-status_<?php echo esc_attr( $post->ID ); ?>">
                    <span id="pda-v3-text_<?php echo esc_attr( $post->ID ); ?>"
                          class="protection-status <?php echo esc_attr( $pda_class ); ?>"
                          title="<?php echo esc_attr( $title_text ); ?>">
                        <?php echo $pda_icon; ?>
                        <?php echo esc_html( $pda_text ); ?>
                    </span>
				</p>
				<div>
					<a class="pda_gold_btn"
					   id="pda_gold-<?php echo $post->ID ?>"><?php echo esc_html__( 'Configure file protection', 'prevent-direct-access' ) ?></a>
				</div>
			</div>
			<?php
		}

		if ( $column_name == 'hits_count' ) {
			$hits_count = ( isset( $advance_file ) && isset( $advance_file->hits_count ) ) ? $advance_file->hits_count : 0;
			?>
			<label><?php echo $hits_count; ?></label>
			<?php
		}
	}

	public function free_add_upload_columns( $columns ) {
		$columns['direct_access'] = __( 'Prevent Direct Access', 'prevent-direct-access' );

		return $columns;
	}

	public function so_wp_ajax_function() {
		if ( ! isset( $_REQUEST['security_check'], $_POST['id'], $_POST['is_prevented'] ) ) {
			wp_die( 'Invalid data' );
		}

		$nonce   = $_REQUEST['security_check'];
		$post_id = absint( $_POST['id'] );
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce' . $post_id ) ) {
			wp_die( 'invalid_nonce' );
		}

		$is_prevented = wp_validate_boolean( $_POST['is_prevented'] );
		$file_result  = $this->insert_prevent_direct_access( $post_id, $is_prevented );

		//move file to _pda
		$this->handle_move_file( $post_id );

		wp_send_json( $file_result );
		wp_die();
	}

	public function handle_move_file( $post_id ) {
		$handle         = new Pda_Free_Handle();
		$protected      = new Repository;
		$protected_file = $protected->get_status_advance_file_by_post_id( $post_id, true );
		if ( isset( $protected_file ) && $protected_file->is_prevented === "1" ) {
			$handle->move_file_to_pda( $post_id );
		} else {
			$handle->un_protect_file( $post_id );
		}
	}

	public function check_nonce( $nonce, $post_id ) {
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce' . $post_id ) ) {
			wp_die( 'invalid_nonce' );
		}
	}

	public function so_wp_ajax_regenerate_url() {
		if ( ! isset( $_REQUEST['security_check'], $_POST['id'] ) ) {
			wp_die( 'Invalid data' );
		}

		$nonce   = $_REQUEST['security_check'];
		$post_id = absint( $_POST['id'] );
		$this->check_nonce( $nonce, $post_id );

		$repository = new Repository;
		$result  = $repository->update_private_link_by_post_id( $post_id );
		if ( $result < 1 || $result === false ) {
			$file_result = array( 'error' => "Cannot re-generate private link" );
		} else {
			$file_result      = $repository->get_advance_file_by_post_id( $post_id );
			$file_result->url = site_url() . '/private/' . $file_result->url;
		}
		wp_send_json( $file_result );
		wp_die();
	}

	public function is_file_limitation_over() {

		$repository = new Repository;
		$limitation = $repository->check_advance_file_limitation();
		$config     = include( 'includes/config.php' );

		return $limitation >= $config->ms;
	}

	public function is_file_limitation_to_show_warn() {

		$repository = new Repository;
		$limitation = $repository->check_advance_file_limitation();
		$config     = include( 'includes/config.php' );

		return $limitation >= $config->ms_warn;
	}

	public function insert_prevent_direct_access( $post_id, $is_prevented ) {
		$repository = new Repository;
		if ( $is_prevented && $this->is_file_limitation_over() ) {
			$file_result = array(
				'error' => __( "Our Free version only allows you to protect up to 9 files. Please upgrade to the Gold version for many more premium features!", 'prevent-direct-access' ),
			);
		} else {
			$file_info = array(
				'time'         => current_time( 'mysql' ),
				'post_id'      => $post_id,
				'is_prevented' => $is_prevented,
				'url'          => Pda_Helper::generate_unique_string(),
			);
			$result    = $repository->create_advance_file( $file_info );
			if ( $result < 1 || $result === false ) {
				$file_result = array(
					'error' => __( "This file is already protected. Please reload your page.", 'prevent-direct-access' ),
				);
			} else {
				$file_result      = $repository->get_advance_file_by_post_id( $file_info['post_id'] );
				$file_result->url = site_url() . '/private/' . $file_result->url;
			}
			if (is_multisite()) {
				$file_result = array(
					'error' => __( "This file is not protect with multisite.", 'prevent-direct-access' ),
				);
			}
		}

		return $file_result;
	}

	public function delete_prevent_direct_access( $post_id ) {
		$repository = new Repository;
		$repository->delete_advance_file_by_post_id( $post_id );
	}

	public function deactivate() {
		remove_filter( 'mod_rewrite_rules', array( $this, 'htaccess_contents' ) );
		flush_rewrite_rules();
		$this->db->remove_db_options();
	}

	public function plugin_install() {
		flush_rewrite_rules();
		$this->db->create_new_table();
	}

	public static function plugin_uninstall() {
		if ( get_option( 'pda_is_licensed' ) ) {
			return;
		}

		Pda_Database::uninstall_static();
		$un_protect = new Repository();
		$un_protect->un_protect_files();
	}

	public function create_new_table_and_migrate_data() {
		if ( get_option( 'pda_free_migrated' ) !== 'true' ) {
			$this->db->create_new_table();
			$repository = new Repository;
			$repository->migrate_data_to_new_table();
			update_option( 'pda_free_migrated', 'true' );
		}
	}

	public function pda_ajax_subscribe() {
		$check = check_ajax_referer( 'pda_subscribe', 'security_check' );
		if ( $check == 1 ) {
			if ( $_POST['action'] == 'pda_subscribe' ) {
				$uid = get_current_user_id();
				update_user_meta( $uid, 'pda_subscribed', true );
			}
		}
	}

	public function pda_add_settings_page() {
		$pda_settings_page = add_menu_page( __( 'Prevent Direct Access', 'prevent-direct-access' ), __( 'Prevent Direct Access', 'prevent-direct-access' ), 'manage_options', 'wp_pda_options', null, 'dashicons-hidden' );
		$go_pro_icon       = "<span style=\"color:#F44F45;\"><svg width=\"12\" height=\"12\" viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path fill=\"currentColor\" d=\"M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z\"/></svg></span>";
		add_submenu_page( 'wp_pda_options', __( 'Settings', 'prevent-direct-access' ), __( 'Settings', 'prevent-direct-access' ), 'manage_options', 'wp_pda_options', array(
			$this,
			'pda_options_do_page',
		) );
		$go_pro_page = add_submenu_page( 'wp_pda_options', __( 'Go PRO', 'prevent-direct-access' ), __( 'Go Pro ', 'prevent-direct-access' ) . $go_pro_icon, 'manage_options', 'wp_pda_gopro', array(
			$this,
			'pda_options_do_go_pro_page',
		) );
		add_action( 'admin_print_styles-' . $pda_settings_page, array( $this, 'pda_setting_pages' ) );
		add_action( 'admin_print_styles-' . $go_pro_page, array( $this, 'pda_setting_go_pro' ) );
	}

	public function pda_setting_pages() {
		wp_register_style( 'pda_setting_css', plugin_dir_url( __FILE__ ) . ( 'css/prevent-direct-access-lite-setting.css' ), array() );
		wp_enqueue_style( 'pda_setting_css' );
		wp_register_style( 'pda_rating_subscribe_css', plugin_dir_url( __FILE__ ) . ( 'css/prevent-direct-access-lite-rating-subscribe.css' ), array() );
		wp_enqueue_style( 'pda_rating_subscribe_css' );
	}

	public function pda_setting_go_pro() {
		wp_register_style( 'pda_setting_go_pro_css', plugin_dir_url( __FILE__ ) . ( 'css/pda_setting_go_pro.css' ), array() );
		wp_enqueue_style( 'pda_setting_go_pro_css' );
	}

	public function pda_options_do_page() {
		$setting_page = new SettingsPage();
		$setting_page->render_settings_page();
	}

	public function pda_options_do_go_pro_page() {
		$setting_page = new SettingsPage();
		$setting_page->render_go_pro_page();
	}

	function pda_custom_robots_txt( $output ) {
		$repository      = new Repository;
		$protected_posts = $repository->get_protected_post();
		$rules           = "Disallow: /wp-includes/" . PHP_EOL . "Disallow: /wp-content/plugins/" . PHP_EOL;
		foreach ( $protected_posts as $post ) {
			$post_link = str_replace( site_url(), '', get_permalink( $post ) );
			$url       = str_replace( site_url(), '', wp_get_attachment_url( $post->ID ) );
			$rules     .= "Disallow: $post_link" . PHP_EOL;
			$rules     .= "Disallow: $url" . PHP_EOL;
		}
		$output .= $rules . PHP_EOL;

		return $output;
	}

	public function check_protected_file_yes_or_no( $is_prevented ) {
		$protected      = new Repository;
		$protected_true = $protected->get_protected_posts( $is_prevented );

		return $protected_true;
	}

	public function modify_protected_media( $post_object ) {
		$protected          = $this->check_protected_file_yes_or_no( 1 );
		$protected_files    = array();
		$un_protected_files = array();
		for ( $i = 0; $i < count( $post_object ); $i ++ ) {
			if ( in_array( $post_object[ $i ]->ID, array_column( $protected, 'post_id' ) ) ) {
				array_push( $protected_files, $post_object[ $i ] );
			} else {
				array_push( $un_protected_files, $post_object[ $i ] );
			}
		}
		if ( isset( $_GET['protected_media'] ) ) {
			if ( $_GET['protected_media'] == 1 ) {
				return $protected_files;
			} elseif ( $_GET['protected_media'] == 2 ) {
				return $un_protected_files;
			} elseif ( $_GET['protected_media'] == 0 ) {
				return $post_object;
			}
		} else {
			return $post_object;
		}
	}

	public function pda_load_text_domain() {
		load_plugin_textdomain( 'prevent-direct-access', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	function pda_rest_api_init_cb() {
		$api = new PDA_Lite_API();
		$api->register_rest_routes();
	}

	function pda_add_affiliate_submenu() {
		$setting_affiliate = new PDA_Lite_Affiliate();

		add_submenu_page( 'wp_pda_options', __( 'Invite & Earn', 'prevent-direct-access' ), __( 'Invite & Earn', 'prevent-direct-access' ), 'manage_options', PDA_Lite_Constants::AFFILIATE_PAGE_PREFIX, array(
			$setting_affiliate,
			'render_ui',
		) );
	}

	function pda_lite_update_general_settings() {
		if ( ! isset( $_REQUEST['settings'], $_REQUEST['security_check'] ) ) {
			wp_die( 'Invalid data' );
		}

		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_v3' ) ) {
			wp_die( 'invalid_nonce' );
		}
		$settings = $_REQUEST['settings'];
		$settings = array_map( 'sanitize_text_field', $settings );

		update_option( PDA_Lite_Constants::OPTION_NAME, array(
			'hide_protected_files_in_media' => array_key_exists( 'hide_protected_files_in_media', $settings ) ? $settings['hide_protected_files_in_media'] : null,
			'disable_right_click'	   		=> array_key_exists( 'disable_right_click', $settings ) ? $settings['disable_right_click'] : null,
			'enable_image_hot_linking' 		=> array_key_exists( 'enable_image_hot_linking', $settings ) ? $settings['enable_image_hot_linking'] : null,
			'search_result_page_404'   		=> array_key_exists( 'search_result_page_404', $settings ) ? $settings['search_result_page_404'] : null,
			'enable_directory_listing' 		=> array_key_exists( 'enable_directory_listing', $settings ) ? $settings['enable_directory_listing'] : null,
			'file_access_permission'   		=> array_key_exists( 'file_access_permission', $settings ) ? $settings['file_access_permission'] : '',
		) );

		/**
		 * Add filter to write htaccess rules
		 */
		add_filter( 'mod_rewrite_rules', array( $this, 'htaccess_contents' ) );
		flush_rewrite_rules();

		wp_send_json( $settings );
		wp_die();
	}

	public function pda_lite_update_ip_restriction_settings() {
		$nonce = isset( $_REQUEST['security_check'] ) ? $_REQUEST['security_check'] : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_v3' ) ) {
			return wp_send_json_error(
				array(
					'success' => false,
					'message' => 'Invalid nonce',
				),
				400
			);
		}
		if ( ! isset( $_POST['settings'] ) ) {
			return wp_send_json_error(
				array(
					'success' => false,
					'message' => 'IP does not exist',
				),
				400
			);
		}
		$settings = $_POST['settings'];
		$settings = array_map( 'sanitize_text_field', $settings );

		update_option( 'FREE_PDA_SETTINGS_IP', array( 'ip_lock' => $settings['pda_free_pl_blacklist_ips'] ) );

		return wp_send_json_success(
			array(
				'success' => true,
			)
		);
	}

	function pda_ajax_pda_free_subscribe() {
		if ( ! isset( $_POST['email'] ) ) {
			return wp_send_json_error(
				array(
					'success' => false,
					'message' => 'Email is required',
				),
				400
			);
		}
		$check = check_ajax_referer( 'pda_free_subscribe', 'security_check' );
		if ( $check == 1 ) {
			if ( $_POST['action'] == 'pda_free_subscribe' ) {
				$data     = array(
					'email'  => sanitize_email( $_POST['email'] ),
					'plugin' => 'pda',
				);
				$args     = array(
					'body'        => json_encode( $data ),
					'timeout'     => '100',
					'redirection' => '5',
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'x-api-key'    => 'SUDi1LNlAv3T2nQ4QIX9Sadtr7Ghg9UD1PnHvyWe',
						'Content-Type' => 'application/json',
					),
				);
				$response = wp_remote_post(
					'https://loks4vx5i7.execute-api.ap-southeast-1.amazonaws.com/prod/mail',
					$args
				);
				if ( is_wp_error( $response ) ) {
					$result['message'] = $response->get_error_message();
				} else {
					$result['data'] = json_decode( wp_remote_retrieve_body( $response ) );
					$uid            = get_current_user_id();
					update_user_meta( $uid, 'pda_free_subscribe', true );
				}

				return $result;
			}

		}
	}

	/**
	 * Show admin notices to remind user to upgrade PDA Gold because PDA Free is only supported single site.
	 */
	public function multisite_admin_notices() {
		global $pagenow;

		if (
			$pagenow !== 'plugins.php'
			&& $pagenow !== 'upload.php'
			&& (!isset($_GET['page'])
			|| $_GET['page'] !== 'wp_pda_options' )) {
			return;
		}

		$plugin_name = 'Prevent Direct Access';

		/* translators: %1$s The guide link */
		$message = sprintf(
				__( ': Our PDA Lite only supports WordPress single site. Please <a target="_blank" rel="noopener" href="%s">upgrade to Gold version</a> for our file protection to work properly.', 'password-protect-page' ),
				sprintf( constant( 'PDA_HOME_PAGE' ), 'notification', 'notification-link'
				)
		);
		?>
		<div class="error is-dismissible notice">
			<p>
				<b><?php echo esc_html( $plugin_name ); ?></b><?php echo wp_kses_post( $message ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Prevent right-click
	 */
	function prevent_right_click() {
		$pda_option       = get_option( 'FREE_PDA_SETTINGS' );
		if ( is_array( $pda_option ) && array_key_exists( 'disable_right_click', $pda_option ) && $pda_option['disable_right_click'] === "on" ) {
			$noscript_message            = apply_filters( 'pda_noscript_message', 'Please enable JavaScript in your browser to view the content' );
			$disable_right_click_message = apply_filters( 'pda_disable_right_click_message', 'Right-click is disabled' );
			$show_alert                  = apply_filters( 'pda_show_alert_on_right_clicks', false );

			?>
			<noscript>
				<div style="position: fixed; top: 0px; left: 0px; z-index: 30000000;
                height: 100%; width: 100%; background-color: #FFFFFF">
					<p style="margin-left: 10px"><?php esc_html_e( $noscript_message, 'prevent-direct-access' ); ?></p>
				</div>
			</noscript>
			<script>
			  const show_alert = "<?php echo $show_alert; ?>";

			  document.addEventListener('contextmenu', event => {
				if (show_alert) {
					alert('<?php esc_attr_e( $disable_right_click_message, 'prevent-direct-access' ); ?>');
				}
				event.preventDefault()
			  });
			  document.onkeydown = function (e) {
				// disable F12 key
				if(e.keyCode == 123) {
				  return false;
				}
				var ctrlOrMeta = e.ctrlKey || e.metaKey;

				// disable I key
				if(ctrlOrMeta && e.shiftKey && e.keyCode == 73){
				  return false;
				}

				// disable J key
				if(ctrlOrMeta && e.shiftKey && e.keyCode == 74) {
				  return false;
				}

				// disable P key
				if(ctrlOrMeta && e.keyCode == 80) {
				  return false;
				}

				// disable S key
				if(ctrlOrMeta && e.keyCode == 83) {
				  return false;
				}

				// disable U key
				if(ctrlOrMeta && e.keyCode == 85) {
				  return false;
				}
			  }
			</script>
			<style>
				/* Disable select text */
                * {
                    -webkit-touch-callout: none; /* Safari */
                    -webkit-user-select: none; /* Chrome */
                    -moz-user-select: none; /* Firefox */
                    -ms-user-select: none; /* Internet Explorer/Edge */
                    user-select: none;
                }

				/* Disable drag image */
                img {
                    -webkit-user-drag: none;
                    -khtml-user-drag: none;
                    -moz-user-drag: none;
                    -o-user-drag: none;
                    user-drag: none;
                }
			</style>
			<?php
		}
	}

}

$pda_admin = new Pda_Admin();

?>
