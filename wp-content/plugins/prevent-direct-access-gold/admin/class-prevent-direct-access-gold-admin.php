<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda_gold
 * @since      1.0.0
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/admin
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class Prevent_Direct_Access_Gold_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Gold service
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private $gold_service;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->gold_service = new PDA_Services();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Prevent_Direct_Access_Gold_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Prevent_Direct_Access_Gold_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( function_exists( 'get_current_screen' ) ) {
			$screen          = get_current_screen();
			$support_screens = PDA_v3_Constants::get_screen_map_id();

			if ( $screen->id === $support_screens['affiliate'] ) {
				wp_enqueue_style( $this->plugin_name . '-affiliate-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-affiliate.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-rating-subscribe-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-rating-subscribe.css', array(), $this->version, 'all' );
			}

			if ( $screen->id === $support_screens['pda_settings'] || $screen->id === $support_screens['media'] || $screen->id === $support_screens['status'] ) {
				wp_enqueue_style( $this->plugin_name . '-bundle-css', plugin_dir_url( PDA_V3_PLUGIN_BASE_FILE ) . 'js/dist/style/pda_gold_v3_bundle.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-admin-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-rating-subscribe-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-rating-subscribe.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-tags-input-css', plugin_dir_url( __FILE__ ) . 'css/tagsinput.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-jquery-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-toastr-css', plugin_dir_url( __FILE__ ) . 'css/lib/toastr.min.css', array(), $this->version, 'all' );
			}

			if ( $support_screens['attachment'] === $screen->id ) {
				wp_enqueue_style( $this->plugin_name . '-edit-media-metabox-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-metabox.css', array(), $this->version, 'all' );
			}

			wp_enqueue_style( $this->plugin_name . '-select2-css', plugin_dir_url( __FILE__ ) . 'css/lib/select2.min.css', array(), '1.0', 'all' );
		}

		if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'pda-quick-tour' ) {
			wp_enqueue_style( $this->plugin_name . '-custom-google-fonts', 'https://fonts.googleapis.com/css?family=Gochi+Hand%7CKalam', false );
			wp_enqueue_style( $this->plugin_name . '-quick-tour-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-quick-tour.css', array(), '1.0', 'all' );
		}

	}

	private function load_3rd_party_scripts() {
		wp_enqueue_script( $this->plugin_name . '-pda-s3-toastr', plugin_dir_url( __FILE__ ) . 'js/lib/toastr.min.js', $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-select2-js', plugin_dir_url( __FILE__ ) . '/js/lib/select2.full.min.js', array( 'jquery' ), '4.0.6', true );

		if ( ! wp_script_is( 'jquery_ui_min' ) ) {
			wp_enqueue_script( $this->plugin_name . '-jquery-ui-min', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array( 'jquery' ), $this->version, true );
		}
		if ( ! wp_script_is( 'jquery_tagsinput_min' ) ) {
			wp_enqueue_script( $this->plugin_name . '-jquery-tagsinput-min', plugin_dir_url( __FILE__ ) . 'js/jquery.tagsinput.min.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Prevent_Direct_Access_Gold_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Prevent_Direct_Access_Gold_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'pda-quick-tour' ) {
			wp_enqueue_script( $this->plugin_name . '-quick-tour-js', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-gold-quick-tour.js', array( 'jquery' ), $this->version );
		}

		global $userAccessManager;
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			$screen_map      = PDA_v3_Constants::get_screen_map_id();
			$helpers         = new Pda_Gold_Functions();
			$rest_api_prefix = $helpers->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ? '?rest_route=' : 'wp-json';
			//$this->load_3rd_party_scripts();
			$has_license = get_option( 'pda_is_licensed' );
			if ( $screen_map['media'] === $screen->id && $has_license ) {
				wp_enqueue_script( $this->plugin_name . '-bundle-js', plugin_dir_url( PDA_V3_PLUGIN_BASE_FILE ) . 'js/dist/pda_gold_v3_bundle.js', array( 'jquery' ), $this->version, false );
				wp_localize_script( $this->plugin_name . '-bundle-js', 'pda_gold_v3_data', array(
					'home_url'                             => $this->get_home_url_with_ssl(),
					'nonce'                                => wp_create_nonce( 'wp_rest' ),
					'stats_activated'                      => Yme_Plugin_Utils::is_plugin_activated( 'statistics' ),
					'magic_link_activated'                 => Yme_Plugin_Utils::is_plugin_activated( 'magic_link' ),
					'pda_s3_activated'                     => is_plugin_active( 'pda-s3/pda-s3.php' ) && apply_filters( PDA_Private_Hooks::PDA_HOOK_CHECK_S3_HAS_BUCKET, false ),
					'pda_membership_integration_activated' => Yme_Plugin_Utils::is_plugin_activated( 'membership' ),
					'user_access_manager'                  => is_object( $userAccessManager ),
					'memberships_2'                        => class_exists( 'MS_Model_Membership' ),
					'paid_memberships_pro'                 => function_exists( 'pmpro_hasMembershipLevel' ),
					'woo_memberships'                      => function_exists( 'wc_memberships_is_user_member' ),
					'woo_subscriptions'                    => class_exists( "WC_Subscriptions_Admin" ),
					'ar_member'                            => is_plugin_active( 'armember/armember.php' ),
					'restrict_content_pro'                 => is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ),
					'pda_v3_plugin_url'                    => PDA_BASE_URL,
					'rest_api_prefix'                      => $rest_api_prefix,
					'is_license_expired'                   => Pda_Gold_Functions::is_license_expired(),
					'api_url'                              => get_rest_url(),
				) );
			}
			if ( $screen_map['attachment'] === $screen->id ) {
				wp_enqueue_script( $this->plugin_name . '-edit-media-metabox-js', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-acess-gold-metabox.js', array( 'jquery' ), $this->version, true );
				wp_localize_script( $this->plugin_name . '-edit-media-metabox-js', 'pda_gold_v3_metabox', array(
					'home_url'        => $this->get_home_url_with_ssl(),
					'nonce'           => wp_create_nonce( 'wp_rest' ),
					'rest_api_prefix' => $rest_api_prefix,
					'api_url'         => get_rest_url(),
				) );
			}

			if ( $screen_map['pda_settings'] === $screen->id || $screen_map['upload'] === $screen->id || $screen_map['attachment'] === $screen->id ) {
				$this->load_3rd_party_scripts();
				$check_install_ip_block       = Yme_Plugin_Utils::is_plugin_activated( 'ip_block' );
				$check_install_pda_magic_link = Yme_Plugin_Utils::is_plugin_activated( 'magic_link' );

				$roles     = get_editable_roles();
				$userRoles = [];
				foreach ( $roles as $roleName => $roleValue ) {
					array_push( $userRoles, $roleName );
				}

				wp_enqueue_script( $this->plugin_name . '-ip-block-js', plugin_dir_url( __FILE__ ) . 'js/pdav3-ip-block.js', array(), $this->version );
				wp_localize_script( $this->plugin_name . '-ip-block-js', 'ip_block_server_data', array(
					'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
					'ip_block_activated'       => $check_install_ip_block,
					'pda_magic_link_activated' => $check_install_pda_magic_link,
					'roles'                    => $userRoles,
				) );
			}

			// Load notification message when de-activate in plugin networks.
			if ( 'plugins-network' === $screen->id ) {
				$deactivate_script = $this->plugin_name . '-pda-deactivate-notice';
				wp_enqueue_script(
					$deactivate_script,
					plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-deactivate-notice.js',
					array( 'jquery' ),
					$this->version,
					true
				);

				wp_localize_script(
					$deactivate_script,
					'pda_deactivate_data',
					array(
						'message' => __( 'It’s highly recommended not to network deactivate Prevent Direct Access Gold due to performance issues.', 'prevent-direct-access-gold' ),
					)
				);
			}
		}

		// Testing un-used script.
//		wp_enqueue_script( $this->plugin_name . '-toastr-js', plugin_dir_url( __FILE__ ) . 'js/lib/toastr.min.js', $this->version );
//
//		wp_enqueue_script( $this->plugin_name . '-select2-js', plugin_dir_url( __FILE__ ) . 'js/lib/select2.full.min.js', array( 'jquery' ), '4.0.6', true );
//
//		if ( ! wp_script_is( 'jquery_ui_min' ) ) {
//			wp_enqueue_script( $this->plugin_name . '-jquery-ui-min-js', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array( 'jquery' ), $this->version );
//		}
//		if ( ! wp_script_is( 'jquery_tagsinput_min' ) ) {
//			wp_enqueue_script( $this->plugin_name . '-jquery-tags-input-min-js', plugin_dir_url( __FILE__ ) . 'js/jquery.tagsinput.min.js', array( 'jquery' ), $this->version );
//		}

	}

	private function get_home_url_with_ssl() {
		$home_url = is_ssl() ? home_url( '/', 'https' ) : home_url( '/' );
		return apply_filters( 'pda_get_home_url', $home_url );
	}


	public function pda_rest_api_init_cb() {
		$api = new PDA_Api_Gold;
		$api->register_rest_routes();
	}

	function pda_add_upload_columns( $columns ) {
		$columns[ PDA_v3_Constants::COLUMN_ID ] = '<label>Prevent Direct Access</label>';

		return $columns;
	}

	function pda_media_custom_column( $column_name, $post_id ) {
		if ( $column_name == PDA_v3_Constants::COLUMN_ID ) {
			require PDA_V3_BASE_DIR . '/includes/views/column/view-prevent-direct-access-gold-column.php';
		}
	}

	public function pda_db_handle() {
		$db = new PDA_v3_DB;
		$db->run();
	}

	public function Prevent_Direct_Access_Gold_create_plugin_menu() {

		add_submenu_page( 'pda-gold', __( 'Settings', 'prevent-direct-access-gold' ), __( 'Settings', 'prevent-direct-access-gold' ), 'manage_options', 'pda-gold' );

		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-prevent-direct-access-gold-settings.php';
		$setting_page   = new Prevent_Direct_Access_Gold_Settings();
		$menu_page_name = add_menu_page( 'Prevent Direct Access Gold Settings', 'Prevent Direct Access Gold', 'administrator', PDA_v3_Constants::SETTING_PAGE_PREFIX, array(
			$setting_page,
			'render_ui',
		), PDA_BASE_URL . 'public/assets/pda-logo-20px.png' );

		$this->pda_add_status_submenu();
		$this->pda_add_affiliate_submenu();

		add_action( 'admin_print_styles-' . $menu_page_name, array(
			$this,
			'prevent_direct_access_gold_setting_assets',
		) );

	}

	public function add_body_classes_for_quick_tour( $classes ) {
		$is_quick_tour = isset( $_GET['tab'] ) && $_GET['tab'] === "pda-quick-tour" ? true : false;
		if ( $is_quick_tour ) {
			return $classes . ' toplevel_page_pda-wthrou-guide';
		}

		return $classes;
	}

	public function pda_add_status_submenu() {
		$setting_status = new PDA_Status;

		add_submenu_page( 'pda-gold', __( 'Status', 'prevent-direct-access-gold' ), __( 'Status', 'prevent-direct-access-gold' ), 'manage_options', PDA_v3_Constants::STATUS_PAGE_PREFIX, array(
			$setting_status,
			'render_ui',
		) );
	}

	public function pda_add_affiliate_submenu() {
		$setting_affiliate = new PDA_Affiliate;

		add_submenu_page( 'pda-gold', __( 'Invite & Earn', 'prevent-direct-access-gold' ), __( 'Invite & Earn', 'prevent-direct-access-gold' ), 'manage_options', PDA_v3_Constants::AFFILIATE_PAGE_PREFIX, array(
			$setting_affiliate,
			'render_ui',
		) );
	}

	function prevent_direct_access_gold_setting_assets() {
		if ( Pda_v3_Gold_Helper::is_wp_version_compatible( '5.3' ) ) {
			wp_enqueue_style( $this->plugin_name . '-css-wp-5-3', plugin_dir_url( PDA_V3_PLUGIN_BASE_FILE ) . 'admin/css/css-for-5-3/general.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( $this->plugin_name . '-setting-css', plugin_dir_url( PDA_V3_PLUGIN_BASE_FILE ) . 'admin/css/prevent-direct-access-gold-setting.css', array(), $this->version, 'all' );
		wp_enqueue_script( $this->plugin_name . '-setting-js', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-gold-setting-general.js', array( 'jquery' ), $this->version );
		wp_localize_script( $this->plugin_name . '-setting-js', 'prevent_direct_access_gold_setting_data',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'home_url' => $this->get_home_url_with_ssl(),
			)
		);
		wp_enqueue_script( $this->plugin_name . '-license-js', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-gold-license.js', array( 'jquery' ), $this->version );
		wp_localize_script( $this->plugin_name . '-license-js', 'prevent_direct_access_gold_license_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_script( $this->plugin_name . '-search-js', plugin_dir_url( __FILE__ ) . 'js/pdav3_setting_search.js', array( 'jquery' ), $this->version );
		wp_localize_script( $this->plugin_name . '-search-js', 'server_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_script( $this->plugin_name . '-newsletter-js', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-gold-newsletter.js', array( 'jquery' ), $this->version );
		wp_localize_script( $this->plugin_name . '-newsletter-js', 'newsletter_data',
			array(
				'newsletter_url'   => admin_url( 'admin-ajax.php' ),
				'newsletter_nonce' => wp_create_nonce( 'pda_gold_subscribe' ),
			)
		);

		if ( is_multisite() && 1 === get_current_blog_id() ) {
			wp_enqueue_script( $this->plugin_name . '-setting-license-multi-site', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-gold-setting-general-license-multisite.js', array( 'jquery' ), $this->version );
		}

	}

	public function Prevent_Direct_Access_Gold_Check_Licensed() {
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, PDA_v3_Constants::LICENSE_FORM_NONCE ) ) {
			error_log( 'not verify nonce', 0 );
			wp_die( 'invalid_nonce' );
		}
		$license = $_REQUEST['license'];

		if ( empty( $license ) ) {
			update_option( PDA_v3_Constants::LICENSE_ERROR, 'Invalid license', 'no' );
			wp_send_json( false );
		}

		if ( ! empty( $_REQUEST['product_id'] ) ) {
			$product_id = $_REQUEST['product_id'];
		} else {
			$product_id = get_site_option( PDA_v3_Constants::APP_ID );
		}

		$result = YME_LICENSE::checkLicense( $license, 'pda', $product_id );
		$data   = $result['data'];

		$logger = new PDA_Logger;
		$logger->info( sprintf( "License checking response: %s", json_encode( $data ) ) );

		if ( ! $data ) {
			$result['data']['errorMessage'] = "There is something's wrong. Please <a href=\"hello@preventdirectaccess.com\">contact</a> the plugin owner!";
			update_option( PDA_v3_Constants::LICENSE_ERROR, $data['errorMessage'], 'no' );
		} elseif ( is_object( $data ) && property_exists( $data, 'errorMessage' ) ) {
			update_option( PDA_v3_Constants::LICENSE_ERROR, $data->errorMessage, 'no' );
		} else {
			update_option( PDA_v3_Constants::LICENSE_KEY, $license, 'no' );
			update_option( PDA_v3_Constants::LICENSE_OPTIONS, true, 'no' );
			update_option( PDA_v3_Constants::LICENSE_ERROR, '', 'no' );
			delete_option( PDA_v3_Constants::LICENSE_EXPIRED );
			update_site_option( PDA_v3_Constants::APP_ID, $product_id );
			delete_option( 'pda_gold_update_info' );

			Pda_v3_Gold_Helper::set_default_settings_for_multisite();

			$service = new PDA_Services();
			$service->handle_license_info();
			$cronjob_handler = new PDA_Cronjob_Handler();
			$cronjob_handler->unschedule_ls_cron_job();

			$db = new PDA_v3_DB;
			$db->run();

			// If free is activated, need to register Gold rules.
			if ( Pda_Gold_Functions::is_pda_free_activated() ) {
				Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();
			}

		}

		wp_send_json( $result );
		wp_die();
	}

	/**
	 * TODO: Need to refactor this function.
	 */
	function Prevent_Direct_Access_Gold_admin_notices() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		if ( function_exists( 'get_current_screen' ) ) {
			$screen              = get_current_screen();
			$default_show_screen = array(
				'plugins-network',
				'plugins',
				'options-media',
				'upload',
				'media',
				'attachment',
				'toplevel_page_pda-gold',
			);
			$permalink_screens   = $default_show_screen;
			$permalink_screens[] = 'options-permalink';
			$function            = new Pda_Gold_Functions();
			$this->notice_for_permalink( $screen, $permalink_screens, $function );

			if ( ! in_array( $screen->id, $default_show_screen, true ) ) {
				return;
			}

			$is_licensed = get_option( PDA_v3_Constants::LICENSE_OPTIONS );
			if ( ! Pda_v3_Gold_Helper::is_migrated_data_from_v2() ) {
				$this->notice_migrate_data();
			} elseif ( empty( $is_licensed ) ) {
				$message = '<p>' . sprintf(
						esc_html__( 'Please enter your license key under %1$s to activate our premium features.', 'prevent-direct-access-gold' ),
						'<a href="' . admin_url( 'admin.php?page=pda-gold&tab=license' ) . '">' . esc_html__( 'Prevent Direct Access Gold settings tab', 'prevent-direct-access-gold' ) . '</a>'
					) . '</p>';

				$this->show_notice_error_message( $message );
			} elseif ( is_plugin_active( 'json-rest-api/plugin.php' ) ) {
				//plugin is activated
				?>
				<div class="error is-dismissible notice">
					<p>
						<b><?php echo "Prevent Direct Access: "; ?></b><?php echo esc_html__( 'You are using WP REST API which is deprecated.', 'prevent-direct-access-gold' ) ?>
						<?php echo esc_html__( 'Please update to', 'prevent-direct-access-gold' ) ?> <a
								href="https://wordpress.org/plugins/rest-api/"><?php echo esc_html__( 'WordPress REST API (Version 2)', 'prevent-direct-access-gold' ) ?></a>
					</p>
				</div>
				<?php
			} elseif ( ! Pda_Gold_Functions::is_fully_activated() ) {
				if ( in_array( $screen->id, array( 'plugins', 'upload', 'attachment' ) ) ) {
					$this->notice_htaccess();
				}
			} elseif ( Pda_Gold_Functions::is_license_expired() ) {
				$message = PDA_v3_Constants::LICENSE_EXPIRED_MESSAGE;
				?>
				<div class="error is-dismissible notice">
					<p><?php echo html_entity_decode( $message ) ?> </p>
				</div>
				<?php
			}

			$version        = explode( '.', PHP_VERSION );
			$php_version_id = $version[0] * 10000 + $version[1] * 100;
			if ( $php_version_id < 50500 ) {
				?>
				<div class="error is-dismissible notice">
					<p><b><?php echo "Prevent Direct Access Gold: "; ?></b>
						<?php echo esc_html__( 'You\'re using an outdated version of PHP which is not compatible with our plugin. Please upgrade to PHP version 5.5 or greater.', 'prevent-direct-access-gold' ) ?>
						<br>
						<?php echo esc_html__( 'Outdated PHP or MySQL versions may also expose your site to security vulnerabilities.', 'prevent-direct-access-gold' ) ?>
					</p>
				</div>
				<?php
			}
			if ( $function->is_move_files_after_activate_async() && ! empty( get_option( PDA_v3_Constants::PDA_NOTICE_CRONJOB_AFTER_ACTIVATE_OPTION ) ) ) {
				$status_files = $function->get_status_move_files();
				?>
				<div class="error is-dismissible notice">
					<p><b><?php echo "Prevent Direct Access Gold: "; ?></b>
						<?php echo esc_html__( 'We’re handling ' . $status_files['num_of_protected_files'] . '/' . $status_files['total_files'] . ' protected files. Please come back in a while.', 'prevent-direct-access-gold' ) ?>
					</p>
				</div>
				<?php
			}

			// Comment here because the customer fells that it's not convenient
			$updates = Pda_v3_Gold_Helper::extensions_has_updates();
			if ( ! empty( $updates ) ) {
				$extensions = '';
				if ( count( $updates ) > 1 ) {
					$last       = $updates[ count( $updates ) - 1 ];
					$tmp        = array_slice( $updates, 0, count( $updates ) - 1 );
					$text       = implode( ', ', $tmp );
					$text       .= " and $last";
					$extensions = 'extensions';
				} else {
					$text       = implode( ', ', $updates );
					$extensions = 'extension';
				}
				?>
				<div class="notice notice-warning is-dismissible">
					<p>
						<b><?php echo "Prevent Direct Access Gold: "; ?></b> Please update <?php echo $text ?>
						<?php echo $extensions ?> for our plugins to work properly.
					</p>
				</div>
				<?php
			}

			// Only show Free notice when user already entered the valid license.
			$have_licensed = get_option( PDA_v3_Constants::LICENSE_OPTIONS );
			if ( $have_licensed ) {
				$this->notice_requiring_pda_free();
			}

			$this->notice_license();

		}
	}

	/**
	 * Only show notice on subsite when
	 *
	 */
	private function notice_license() {
		$message_option = get_option( 'pda_gold_update_info' );

		if ( empty( $message_option ) ) {
			return;
		}
		$message_option = (array) json_decode( $message_option );
		if ( empty( $message_option['message'] ) ) {
			return;
		}

		$message = $message_option['message'];

		echo __( wp_kses_post( $message ), 'prevent-direct-access-gold' );
	}

	/**
	 * PDA Gold requires the Free version.
	 * This function shows admin notice requiring users to:
	 *  1. Install PDA Free if there is no Free plugin currently.
	 *  2. If PDA Free is installed,
	 *          2.1 If PDA Free < 2.7.0, require to update it to the latest version.
	 *          2.2 If PDA Free >= 2.7.0 but inactive, require to activate it.
	 * @since 3.2.0
	 */
	private function notice_requiring_pda_free() {
		$pda_free_version = Pda_Gold_Functions::get_pda_free_version();
		if ( false === $pda_free_version ) {
			$message = Pda_Gold_Functions::get_required_pda_free_message( 'install' );
			$this->show_notice_error_message( $message );
		} else {
			if ( version_compare( $pda_free_version, '2.7.0', '<' ) ) {
				$message = Pda_Gold_Functions::get_required_pda_free_message( 'admin-notice-update' );
				$this->show_notice_error_message( $message );
			} else if ( ! Pda_Gold_Functions::is_pda_free_activated() ) {
				$message = Pda_Gold_Functions::get_required_pda_free_message( 'activate' );
				$this->show_notice_error_message( $message );
			}
		}
	}

	/**
	 * Show notice message in admin notices.
	 *
	 * @param string $message Message to show.
	 *
	 * @since 3.2.0
	 */
	private function show_notice_error_message( $message ) {
		?>
		<div class="notice notice-error is-dismissible">
			<?php echo $message; ?>
		</div>
		<?php
	}

	private function notice_htaccess() {
		$helper_url = 'admin.php?page=pda-gold&tab=helper';
		?>
		<div class="error is-dismissible notice">
			<p><b><?php echo "Prevent Direct Access Gold: "; ?></b> Almost there. Our Gold version requires you to
				insert some simple rewrite rules into the .htaccess file for our plugin to work properly. Please follow
				<a href="<?php echo $helper_url ?>">this instruction</a> on how to do it.</p>
		</div>
		<?php
	}

	private function notice_migrate_data() {
		$setting_url = network_admin_url( 'admin.php?page=pda-gold' );
		?>
		<div class="notice notice-success is-dismissible">
			<p><b><?php echo "Prevent Direct Access Gold: "; ?></b> Congratulations! Prevent Direct Access Gold has
				been updated to version 3.0. Please 1) deactivate and activate the plugin now then 2) migrate your
				data under <a href="<?php echo $setting_url ?>">our Settings page</a> to the latest version.</p>
		</div>
		<?php
	}

	function save_product_id_to_option() {

		if ( get_site_option( PDA_v3_Constants::APP_ID ) === false ) {
			$configs = include( PDA_V3_BASE_DIR . 'includes/class-prevent-direct-access-gold-configs.php' );
			update_site_option( PDA_v3_Constants::APP_ID, $configs->app_id );
		}

		if ( '1' !== get_option( PDA_v3_Constants::MIGRATE_DATA, null ) && '2' !== get_option( PDA_v3_Constants::MIGRATE_DATA, null ) ) {
			update_option( PDA_v3_Constants::MIGRATE_DATA, '1', 'no' );
		}
	}

	public function pda_gold_update_general_settings() {
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_v3' ) ) {
			wp_die( 'invalid_nonce' );
		}

		if ( ! isset( $_REQUEST['settings'] ) || ! PDA_Validators::get_instance()->is_validate_before_update_general_setting( $_REQUEST['settings'] ) ) {
			pda_send_json_error( 'Bad Authentication data.' );
		}
		$settings = $_REQUEST['settings'];

		$custom_link    = $settings['pda_nap_custom_link'];
		$no_access_page = $settings['pda_gold_no_access_page'];
		$custom_link    = strtok( $custom_link, '?' );
		$pda_function   = new Pda_Gold_Functions();
		$attachment_id  = $pda_function->pda_v3_get_post_id_by_original_link( $custom_link );
		if ( $attachment_id ) {
			$repo = new PDA_v3_Gold_Repository();
			if ( 'custom-link' === $no_access_page ) {
				if ( $repo->is_protected_file( $attachment_id ) ) {
					wp_send_json(
						array(
							'is_error' => true,
						)
					);
					wp_die();
				}
			} else {
				if ( $repo->is_protected_file( $attachment_id ) ) {
					$data_settings['pda_nap_custom_link'] = '';
				}
			}
		}

		$keys                = [];
		$group_htaccess_keys = [];

		foreach ( $settings as $key => $value ) {
			if ( $key === PDA_v3_Constants::PDA_PREVENT_ACCESS_LICENSE
			     || $key === PDA_v3_Constants::PDA_GOLD_ENABLE_IMAGE_HOT_LINKING
			     || $key === PDA_v3_Constants::PDA_GOLD_ENABLE_DERECTORY_LISTING
			     || $key === PDA_v3_Constants::PDA_PREFIX_URL
			     || $key === PDA_v3_Constants::REMOVE_LICENSE_AND_ALL_DATA
			     || $key === PDA_v3_Constants::USE_REDIRECT_URLS
			     || $key === PDA_v3_Constants::PDA_GOLD_ENABLE_WEB_CRAWLERS
			     || $key === PDA_v3_Constants::PDA_GOLD_WEB_CRAWLERS
			) {
				array_push( $group_htaccess_keys, $key );
			} else {
				array_push( $keys, $key );
			}
		}
		$options_v3_db = unserialize( get_option( PDA_v3_Constants::OPTION_NAME ) );
		foreach ( $keys as $k ) {
			$options_v3_db[ $k ] = $settings[ $k ];
		}
		$site_options_v3_db = unserialize( get_site_option( PDA_v3_Constants::SITE_OPTION_NAME ) );
		foreach ( $group_htaccess_keys as $k ) {
			$site_options_v3_db[ $k ] = $settings[ $k ];
		}

		$pda_v3      = serialize( $options_v3_db );
		$pda_v3_site = serialize( $site_options_v3_db );

		update_option( PDA_v3_Constants::OPTION_NAME, $pda_v3, 'no' );

		$is_main_site = is_main_site( get_current_blog_id() );
		if ( $is_main_site ) {
			update_site_option( PDA_v3_Constants::SITE_OPTION_NAME, $pda_v3_site );
		}
		Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();
		Prevent_Direct_Access_Gold_Htaccess::handle_htaccess_file_in_folder( $site_options_v3_db[ PDA_v3_Constants::USE_REDIRECT_URLS ], $is_main_site );

		$rewrite_rule_checker = new PDA_v3_Rewrite_Rule_Checker();
		if ( $site_options_v3_db[ PDA_v3_Constants::USE_REDIRECT_URLS ] === 'true' ) {
			$htaccess_result = $rewrite_rule_checker->check_htaccess_file_in_folder_pda();
			if ( $htaccess_result[ PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR ] ) {
				wp_send_json( $htaccess_result );
				wp_die();
			}
		} else {
			$htaccess_is_removed = $rewrite_rule_checker->allow_access_pda_folder();
			if ( ! $htaccess_is_removed ) {
				wp_send_json( array(
					PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR => true,
					PDA_v3_Constants::PDA_HTACCESS_MESSAGE       => PDA_v3_Constants::PDA_MESSAGE_REMOVE_HTACCESS_FILE_IN_PDA_FOLDER,
				) );
				wp_die();
			}
		}

		wp_send_json( $settings );
		wp_die();
	}

	function pda_custom_upload_filter( $metadata, $attachment_id ) {
		$result = apply_filters( 'pda_before_custom_upload_filter', true, $metadata );
		if ( $result ) {
			$pda_gold_functions = new Pda_Gold_Functions();
			$roles              = $pda_gold_functions->pda_get_setting_type_is_array( PDA_v3_Constants::WHITElIST_ROLES_AUTO_PROTECT );
			$current_role       = Pda_v3_Gold_Helper::get_current_role();
			if ( isset( $_POST['is_upload_from_media'] ) ) {
				// Upload media in media-new.php
				// If checkbox is uncheck
				if ( ! isset( $_POST['pda_protect_media_upload'] ) ) {
					return $metadata;
				} elseif ( $_POST['pda_protect_media_upload'] != 'on' ) {
					return $metadata;
				}

			} else {
				// Upload media in post.php
				$auto_protect_new_file = $pda_gold_functions->getSettings( PDA_v3_Constants::PDA_AUTO_PROTECT_NEW_FILE );
				if ( ! $auto_protect_new_file ) {
					return $metadata;
				}

				if ( ! empty( $roles ) && empty( array_intersect( $current_role, $roles ) ) ) {
					return $metadata;
				}
			}

			return $this->pda_protect_file( $metadata, $attachment_id );
		}
	}

	public function so_wp_ajax_update_ip_block() {
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_ip_block' ) ) {
			error_log( 'not verify nonce', 0 );
			wp_die( 'invalid_nonce' );
		}
		$settings = $_REQUEST['settings'];
		update_option( 'pda_gold_ip_block', $settings['pda_gold_ip_block'], 'no' );
		Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();
		wp_send_json( true );
		wp_die();
	}

	public function pda_ajax_pda_gold_subscribe() {
		$check = check_ajax_referer( 'pda_gold_subscribe', 'security_check' );
		if ( $check == 1 ) {
			if ( $_POST['action'] == 'pda_gold_subscribe' ) {
				$data     = array(
					'email'    => $_POST['email'],
					'campaign' => array(
						'campaignId' => 'atMwe',
					),
				);
				$args     = array(
					'body'        => json_encode( $data ),
					'timeout'     => '100',
					'redirection' => '5',
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'X-Auth-Token' => 'api-key ae824cfc3df1a2aa18e8a5419ec1c38b',
						'Content-Type' => 'application/json',
					),
				);
				$response = wp_remote_post(
					'https://api.getresponse.com/v3/contacts',
					$args
				);
				if ( is_wp_error( $response ) ) {
					$result['message'] = $response->get_error_message();
				} else {
					$result['data'] = json_decode( wp_remote_retrieve_body( $response ) );
					$uid            = get_current_user_id();
					update_user_meta( $uid, 'pda_gold_subscribe', true );
				}

				return $result;
			}
		}
	}

	public function pda_v3_custom_robots_txt( $output ) {
		$upload      = wp_upload_dir();
		$upload_path = str_replace( home_url( '/' ), '', $upload['baseurl'] );
		$site_url    = parse_url( site_url() );
		$path        = ( ! empty( $site_url['path'] ) ) ? $site_url['path'] : '';
		$rules       = "Disallow: $path/" . $upload_path . "/_pda/*" . PHP_EOL;

		return $output . $rules;
	}

	public function add_no_index_meta() {
		global $post;

		if ( isset( $post ) ) {
			$repo = new PDA_v3_Gold_Repository;
			if ( $repo->is_protected_file( $post->ID ) ) {
				?>
				<meta name="robots" content="none">
				<?php
			}
		}
	}

	function add_custom_setting_metabox() {
		$gold_function = new Pda_Gold_Functions();
		if ( $gold_function->pda_check_role_protection() ) {
			include( PDA_V3_BASE_DIR . 'includes/class-prevent-direct-access-gold-metabox.php' );
		}
	}

	function pda_gold_migrate_data() {
		$repo  = new PDA_v3_Gold_Repository();
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_v3' ) ) {
			error_log( 'not verify nonce', 0 );
			wp_die( 'invalid_nonce' );
		}
		$attachment_ids = $repo->get_protected_posts();
		$names          = array();
		foreach ( $attachment_ids as $id ) {
			$file = get_post_meta( $id['post_id'], '_wp_attached_file', true );
			if ( ! empty( $file ) ) {
				$info = pathinfo( $file );
				if ( false === stripos( $file, Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/' ) ) ) {
					$reldir = dirname( $file );
					if ( in_array( $reldir, array( '\\', '/', '.' ), true ) ) {
						$reldir = '';
					}
					$protected_dir = path_join( Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir(), $reldir );
					$move_result   = Prevent_Direct_Access_Gold_File_Handler::move_attachment_to_protected( $id['post_id'], $protected_dir );
					if ( ! is_wp_error( $move_result ) ) {
						error_log( "Moved $file successfully. Updating post meta: " . $id['post_id'] );
						update_post_meta( $id['post_id'], PDA_v3_Constants::PROTECTION_META_DATA, true );
					}
					array_push( $names,
						array(
							'basename' => $info['basename'],
							'id'       => $id['post_id'],
						)
					);
				}
			}
		}

		$repo->migrate_pda_options();

		update_option( PDA_v3_Constants::MIGRATE_DATA, '1', 'no' );
		Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();

		return $names;
	}

	function pda_custom_bulk_actions( $actions ) {
		$gold_function = new Pda_Gold_Functions();
		if ( $gold_function->pda_check_role_protection() ) {
			$actions['pda_v3_prevent_all_files']    = __( 'Protect files', 'prevent-direct-access-gold' );
			$actions['pda_v3_un_prevent_all_files'] = __( 'Unprotect files', 'prevent-direct-access-gold' );
		}

		return $actions;
	}

	function pda_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction !== 'pda_v3_prevent_all_files' && $doaction !== 'pda_v3_un_prevent_all_files' ) {
			return $redirect_to;
		}
		if ( isset( $post_ids ) ) {
			$doaction == 'pda_v3_prevent_all_files' ? true : false;
			if ( $doaction == 'pda_v3_prevent_all_files' ) {
				foreach ( $post_ids as $post_id ) {
					$this->protect_prevent_files( $post_id );
				}
			} elseif ( $doaction == 'pda_v3_un_prevent_all_files' ) {
				foreach ( $post_ids as $post_id ) {
					$this->unprotect_prevent_files( $post_id );
				}
			}

		}

		return $redirect_to;
	}

	function protect_prevent_files( $post_id ) {
		PDA_Private_Link_Services::protect_file( $post_id );
	}

	function unprotect_prevent_files( $post_id ) {
		$repo = new PDA_v3_Gold_Repository();
		$repo->un_protect_file( $post_id );
	}

	function pda_gold_check_htaccess() {
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_v3' ) ) {
			error_log( 'not verify nonce', 0 );
			wp_die( 'invalid_nonce' );
		}

		$status = Prevent_Direct_Access_Gold_Htaccess::check_rewrite_rules_by_private_link();

		$rules = array();

		global $is_apache;
		if ( true === $status && $is_apache ) {
			$checker    = new PDA_v3_Rewrite_Rule_Checker();
			$rules      = $checker->check_apache_rules();
			$rr_checker = new PDA_v3_Rewrite_Rule_Checker();
			if ( ! $rr_checker->allow_access_pda_folder() ) {
				$status = - 2;
			}
		}

		$message = Prevent_Direct_Access_Gold_Htaccess::get_rr_status_message( $status );

		if ( true === $status ) {
			Pda_Gold_Functions::fully_activated();
		}

		$result = array(
			'status'         => $status,
			'message'        => $message,
			'rules_checking' => $rules,
		);

		wp_send_json( $result );
		wp_die();
	}

	function pda_gold_enable_raw_url() {
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, 'pda_ajax_nonce_v3' ) ) {
			error_log( 'not verify nonce', 0 );
			wp_die( 'invalid_nonce' );
		}
		Pda_Gold_Functions::update_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS, true );
		$is_main_site = is_main_site( get_current_blog_id() );
		Prevent_Direct_Access_Gold_Htaccess::handle_htaccess_file_in_folder( 'true', $is_main_site );
		Pda_Gold_Functions::fully_activated();
		wp_send_json( true );
		wp_die();
	}

	function delete_table_site_website( $blog_id, $drop ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "prevent_direct_access";
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}

	public function restrict_manage_protected_media( $post_type ) {
		/*
		if ( "attachment" === $post_type ) {
			$gold_function = new Pda_Gold_Functions();
			if ( $gold_function->pda_check_role_protection() ) {
				$pda_repo          = new PDA_v3_Gold_Repository();
				$post_id_protected = $pda_repo->get_all_post_id_protect();
				$test_before = round(microtime(true) * 1000);
				error_log( 'before use count attachments: ' . wp_json_encode( $test_before ) );
				$attachments       = (array) wp_count_attachments();
				$test_after = round(microtime(true) * 1000);
				error_log( 'after use count attachments: ' . wp_json_encode( $test_after ) );
				error_log( 'Total: ' . wp_json_encode( ( $test_after - $test_before )/1000 ) . " mili-seconds" );
				if ( ! empty( $post_id_protected ) && count( $post_id_protected ) !== array_sum( $attachments ) ) {
					$selected     = '';
					$request_attr = 'protected_media';
					if ( isset( $_REQUEST[ $request_attr ] ) ) {
						$selected = $_REQUEST[ $request_attr ];
					}
					$options = array(
						array(
							'label' => 'All files',
							'value' => 0
						),
						array(
							'label' => 'Protected files',
							'value' => 1
						),
						array(
							'label' => 'Unprotected files',
							'value' => 2
						)
					);
					echo '<select id="protected_media" name="protected_media" >';
					foreach ( $options as $opt ) {
						$is_selected = $selected == $opt['value'] ? 'selected' : '';
						echo '<option value="' . $opt['value'] . '"' . $is_selected . '>' . __( $opt['label'] ) . '</option>';
					}
					echo '<select/>';
				}
			}
		}
		*/
		if ( "attachment" === $post_type ) {
			$gold_function = new Pda_Gold_Functions();
			if ( $gold_function->pda_check_role_protection() ) {
				$selected     = '';
				$request_attr = 'protected_media';
				if ( isset( $_REQUEST[ $request_attr ] ) ) {
					$selected = $_REQUEST[ $request_attr ];
				}
				$options = array(
					array(
						'label' => 'All files',
						'value' => 0,
					),
					array(
						'label' => 'Protected files',
						'value' => 1,
					),
					array(
						'label' => 'Unprotected files',
						'value' => 2,
					),
				);
				echo '<select id="protected_media" name="protected_media" >';
				foreach ( $options as $opt ) {
					$is_selected = $selected == $opt['value'] ? 'selected' : '';
					echo '<option value="' . $opt['value'] . '"' . $is_selected . '>' . __( $opt['label'] ) . '</option>';
				}
				echo '<select/> ';
			}
		}
	}

	public function modify_protected_media( $query ) {
		if ( is_admin() && $query->is_main_query() ) {
			if ( isset( $_GET['protected_media'] ) ) {
				$repo = new PDA_v3_Gold_Repository();
				if ( $_GET['protected_media'] == 1 ) {
					$all_post_id_protect = $repo->get_all_post_id_protect();
					$protected_files     = array_map( function ( $post ) {
						return $post->post_id;
					}, $all_post_id_protect );
					$query->set( 'post__in', $protected_files );

					return;
				} elseif ( $_GET['protected_media'] == 2 ) {
					$all_post_id_un_protect = $repo->get_all_post_id_un_protect();
					$un_protected_files     = array_map( function ( $post ) {
						return $post->post_id;
					}, $all_post_id_un_protect );
					$query->set( 'post__in', $un_protected_files );

					return;
				}
			}
		}
	}

	/**
	 * Add UI to protect file in popup media view
	 *
	 * @param array  $form_fields An array of attachment form fields.
	 * @param string $post        The WP_Post attachment object.
	 *
	 * @return array
	 */
	public function add_checkbox_protect_file( $form_fields, $post ) {
		$gold_function = new Pda_Gold_Functions();
		if ( ! function_exists( 'get_current_screen' ) || ! $gold_function->pda_check_role_protection() ) {
			return $form_fields;
		}

		$screen = get_current_screen();
		if ( isset( $screen ) && 'attachment' === $screen->id ) {
			return $form_fields;
		}
		$field_value   = get_post_meta( $post->ID, 'pda_protection_setting', true );
		$pda_add_media = new PDA_Add_Media();
		$pda_url       = plugin_dir_url( __FILE__ );

		$form_fields['pda_protection_setting'] = array(
			'value' => $field_value,
			'label' => '<h2>Prevent Direct Access Gold</h2>',
			'input' => 'html',
			'html'  => "
				<link rel='stylesheet' href='{$pda_url}css/lib/select2.min.css' type='text/css'/>
				<script src='{$pda_url}js/lib/select2.full.min.js' type='text/javascript'></script>
			" . $pda_add_media->pda_add_media_render_ui( $post ),
		);

		return $form_fields;
	}

	function save_file_file_attachment_edit( $post, $attachment ) {
		$attachment_id = $post['ID'];
		if ( isset( $attachment['pda_protection_setting_hidden'] ) && $attachment['pda_protection_setting_hidden'] === 'pda_protection_setting_hidden' ) {
			if ( isset( $attachment['pda_protection_setting'] ) ) {
				PDA_Private_Link_Services::protect_file( $attachment_id );
			} else {
				$repo = new PDA_v3_Gold_Repository();
				$repo->un_protect_file( $post['ID'] );
			}
		}

		if ( isset ( $attachment['pda_protection_setting'] ) ) {
			if ( isset( $attachment['pda_file_access_permission'] ) ) {
				if ( Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 ) {
					$type          = $attachment['pda_file_access_permission'];
					$ip_block_repo = new Pda_Ip_Block_Repository();
					if ( ! method_exists( $ip_block_repo, 'insert_fap_to_db' ) ) {
						return $post;
					}

					$data["post_id"]               = $attachment_id;
					$data["file_access_permision"] = $type;
					if ( 'custom-roles' === $type ) {
						if ( isset( $attachment['pda_fap_choose_custom_roles'] ) && ! empty( $attachment['pda_fap_choose_custom_roles'] ) ) {
							$data["user_roles"] = str_replace( ",", ";", $attachment['pda_fap_choose_custom_roles'] );
							$ip_block_repo->insert_fap_to_db( $data );

						}
					} else {
						$data["user_roles"] = "";
						$ip_block_repo->insert_fap_to_db( $data );
					}
				} else {
					update_post_meta( $attachment_id, PDA_v3_Constants::$pda_meta_key_user_roles, $attachment['pda_file_access_permission'] );
				}
			}
		}

		return $post;
	}

	function handle_plugin_links( $links ) {
		$setting_url    = admin_url( 'admin.php?page=pda-gold' );
		$plugins_link   = [];
		$plugins_link[] = '<a href="' . $setting_url . '">' . esc_html__( 'Settings', 'prevent-direct-access-gold' ) . '</a>';
		$plugins_link[] = '<a href="https://preventdirectaccess.com/extensions/" style="color: #DC0000; font-weight: bold">' . esc_html__( 'Extensions', 'prevent-direct-access-gold' ) . '</a>';
		$plugins_link   = array_merge( $plugins_link, $links );

		return $plugins_link;
	}

	// Remove WordPress version number
	public function luke_remove_version( $generator_type ) {
		$setting = new Pda_Gold_Functions;
		if ( $setting->getSettings( PDA_v3_Constants::PDA_PREVENT_ACCESS_VERSION ) ) {
			return "";
		}

		return $generator_type;
	}

	function handle_plugin_updates( $upgrader_object, $options ) {
		$logger     = new PDA_Logger();
		$our_plugin = plugin_basename( __FILE__ );
		$logger->info( 'Updating plugin: ' . $our_plugin );
		if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
			foreach ( $options['plugins'] as $plugin ) {
				if ( $plugin == $our_plugin ) {
					$logger->info( 'Updating completed for the plugin: ' . $our_plugin );
					// Set a transient to record that our plugin has just been updated
					Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();
					$db = new PDA_v3_DB;
					$db->run();
					$cronjob_handler = new PDA_Cronjob_Handler();
					$cronjob_handler->schedule_delete_expired_private_links_cron_job();
					if ( version_compare( PDA_GOLD_V3_VERSION, '3.0.19.6', '<=' ) ) {
						$service = new PDA_Services();
						$service->handle_license_info();
					}
					// Set default role protection
					$logger->info( 'Setting the default settings' );
					$pda_service = new PDA_Services();
					$pda_service->pda_gold_set_default_setting_for_role_protection();
				}
			}
		}
	}

	function update_default_settings() {
		$pda_service = new PDA_Services();
		$pda_service->pda_gold_set_default_setting_for_role_protection();
	}

	function decorate_media_grid_view( $response, $attachment, $meta ) {

		$repo = new PDA_v3_Gold_Repository;
		if ( $repo->is_protected_file( $attachment->ID ) ) {
			$response['customClass'] = 'pda-protected-grid-view';
		} else {
			$response['customClass'] = '';
		}

		return $response;
	}

	public function handle_ninja_forms_after_submission( $form_data ) {
		error_log( json_encode( $form_data ) );
	}

	public function pda_register_default_log_handler( $handlers ) {
		if ( defined( 'WC_LOG_HANDLER' ) && class_exists( WC_LOG_HANDLER ) ) {
			$handler_class   = WC_LOG_HANDLER;
			$default_handler = new $handler_class();
		} else {
			$default_handler = new Pda_Log_Handler_File();
		}

		array_push( $handlers, $default_handler );

		return $handlers;
	}

	public function pda_cleanup_logs() {
		$logger = Pda_Gold_Functions::pda_get_logger();
		if ( is_callable( array( $logger, 'clear_expired_logs' ) ) ) {
			$logger->info( "Cleaning the house!!!" );
			$logger->clear_expired_logs();
		}
	}

	/**
	 * @codeCoverageIgnore
	 * TODO: refactor it in next Sprint
	 *
	 * @param $url
	 * @param $attachment_id
	 *
	 * @return mixed|string
	 */
	function change_file_url_in_media( $url, $attachment_id ) {
		$repo = new PDA_v3_Gold_Repository();
		if ( ! $repo->is_protected_file( $attachment_id ) ) {
			return $url;
		}

		$setting = new Pda_Gold_Functions;
		$url     = $this->force_get_attachment_url( $attachment_id );
		if ( $setting->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
			$upload      = wp_upload_dir();
			$baseurl = $upload['baseurl'];
			// We met the bug when the site configured SSL certificate but the Administrator set http for site URL.
			// Need to replace the http by https if ssl configured.
			if ( is_ssl() ) {
				$baseurl = str_replace( 'http://', 'https://', $baseurl );
			}
			$upload_path = str_replace( site_url( '/' ), '', $baseurl );

			$url = str_replace( $upload_path, "index.php?" . PDA_v3_Constants::$secret_param . "=", $url );

			/**
			 * Handle with WordPress directory
			 * $url come to index.php?...
			 * Need to add hostname.
			 */
			if ( 0 === strpos( $url, 'index.php' ) ) {
				return site_url('/') . $url;
			}
		}

		return $url;
	}

	/**
	 * Copy from WordPress function wp_get_attachment_url from line 5247 to 5292 then no need to apply UT.
	 *
	 *
	 * @param $id
	 *
	 * @return mixed|string
	 */
	private function force_get_attachment_url( $id ) {
		$url = '';
		// Get attached file.
		if ( $file = get_post_meta( $id, '_wp_attached_file', true ) ) {
			// Get upload directory.
			if ( ( $uploads = wp_get_upload_dir() ) && false === $uploads['error'] ) {
				// Check that the upload base exists in the file location.
				if ( 0 === strpos( $file, $uploads['basedir'] ) ) {
					// Replace file location with url location.
					$url = str_replace( $uploads['basedir'], $uploads['baseurl'], $file );
				} elseif ( false !== strpos( $file, 'wp-content/uploads' ) ) {
					// Get the directory name relative to the basedir (back compat for pre-2.7 uploads)
					$url = trailingslashit( $uploads['baseurl'] . '/' . _wp_get_attachment_relative_path( $file ) ) . wp_basename( $file );
				} else {
					// It's a newly-uploaded file, therefore $file is relative to the basedir.
					$url = $uploads['baseurl'] . "/$file";
				}
			}
		}

		/*
		 * If any of the above options failed, Fallback on the GUID as used pre-2.7,
		 * not recommended to rely upon this.
		 */
		if ( empty( $url ) ) {
			$url = get_the_guid( $id );
		}

		// On SSL front end, URLs should be HTTPS.
		if ( is_ssl() && ! is_admin() && 'wp-login.php' !== $GLOBALS['pagenow'] ) {
			$url = set_url_scheme( $url );
		}

		return $url;
	}

	function get_attachment_id_by_url( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
		if ( $attachment ) {
			return $attachment[0];
		}
	}

	/**
	 * Ensures fatal errors are logged so they can be picked up in the status report.
	 */
	public function log_errors() {
		$error = error_get_last();
		if ( ! is_null( $error ) ) {
			$logger  = Pda_Gold_Functions::pda_get_logger();
			$message = sprintf( "%s at file %s at line %s.", $error['message'], $error['file'], $error['line'] );
			$logger->critical(
				$message . PHP_EOL,
				array(
					'source' => 'fatal-errors',
				)
			);
		}
	}

	function pda_update_file_access_permission() {
		$data["file_access_permision"] = $_REQUEST['select_role'];
		$data["post_id"]               = $_REQUEST['attachment_id'];
		$data["user_roles"]            = '';
		$data["user_access_manager"]   = '';
		$data["memberships_2"]         = '';
		$data["paid_memberships_pro"]  = '';
		$data["woo_memberships"]       = '';
		$data["woo_subscriptions"]     = '';
		if ( Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 ) {
			$ip_block_repo = new Pda_Ip_Block_Repository();
			if ( ! method_exists( $ip_block_repo, 'insert_fap_to_db' ) ) {
				return false;
			}
			$ip_block_repo->insert_fap_to_db( $data );
		} else {
			$api = new PDA_Api_Gold();
			$api->add_user_roles_to_meta_post( $data );
		}
	}

	function add_checkbox_auto_protect_file() {
		$gold_function = new Pda_Gold_Functions();
		if ( function_exists( 'get_current_screen' ) ) {
			$screen  = get_current_screen();
			$setting = new Pda_Gold_Functions;
			if ( 'media' === $screen->id && 'add' == $screen->action ) {
				$pda_gold_functions = new Pda_Gold_Functions();
				$roles              = $pda_gold_functions->pda_get_setting_type_is_array( PDA_v3_Constants::WHITElIST_ROLES_AUTO_PROTECT );
				$current_role       = Pda_v3_Gold_Helper::get_current_role();
				$is_edit            = $gold_function->pda_check_role_protection();
				?>
				<table>
					<tr>
						<td width="300">
							<h4>Prevent Direct Access Gold</h4>
						</td>
						<td>
							<?php if ( $setting->getSettings( PDA_v3_Constants::PDA_AUTO_PROTECT_NEW_FILE )
							           && ( empty( $roles ) || ! empty( array_intersect( $current_role, $roles ) ) ) ) { ?>
								<input type="checkbox" id="pda_protect_media_upload" name="pda_protect_media_upload"
								       checked <?php echo $is_edit ? '' : 'onclick="return false;"' ?> />
							<?php } else { ?>
								<input type="checkbox" id="pda_protect_media_upload"
								       name="pda_protect_media_upload" <?php echo $is_edit ? '' : 'onclick="return false;"' ?> />
							<?php } ?>
							<input type="hidden" id="is_upload_from_media" name="is_upload_from_media" value="1"/>
							<label for="pda_protect_media_upload">Tick this box to protect upcoming file uploads</label>
						</td>
					</tr>
				</table>
			<?php }
		}
	}

	function add_js_for_auto_protect_file() {
		?>
		<script type="text/javascript">
          (function ($) {
            'use strict';
            var input = $('input[name="pda_protect_media_upload"]');
            var ctrl = document.getElementById('pda_protect_media_upload');
            wpUploaderInit.multipart_params.is_upload_from_media = 1;

            $("#pda_protect_media_upload").change(function () {
              var check = ctrl.checked ? 'on' : 'off';
              wpUploaderInit.multipart_params.pda_protect_media_upload = check;
            });

            setTimeout(function () {
              input.change();
            }, 200);

          }(jQuery));
		</script>
		<?php
	}

	function replace_protected_file( $content ) {
		return $this->gold_service->find_and_replace_protected_file( $content );
	}

	/**
	 * @deprecated
	 */
	public function pda_ls_cron_exec() {
		$cronjob_handler = new PDA_Cronjob_Handler();
		$cronjob_handler->pda_ls_cron_exec();
	}

	public function pda_delete_expired_private_links_cron_exec() {
		$cronjob_handler = new PDA_Cronjob_Handler();
		$cronjob_handler->pda_delete_expired_private_links_cron_exec();
	}

	public function pda_custom_intervals( $schedules ) {
		$cronjob_handler = new PDA_Cronjob_Handler();

		return $cronjob_handler->add_custom_intervals( $schedules );
	}

	/**
	 * @param $metadata
	 * @param $attachment_id
	 * @param $data
	 *
	 * @return bool
	 */
	private function pda_protect_file( $metadata, $attachment_id ) {
		$repo = new PDA_v3_Gold_Repository;

		if ( $repo->is_protected_file( $attachment_id ) ) {
			return $metadata;
		}

		$move_result = Prevent_Direct_Access_Gold_File_Handler::move_attachment_file( $attachment_id, $metadata );
		if ( is_wp_error( $move_result ) ) {
			return $metadata;
		}
		$repo->updated_file_protection( $attachment_id, true );

		//Auto create new private link
		$settings = new Pda_Gold_Functions();
		if ( $settings->getSettings( PDA_v3_Constants::PDA_AUTO_CREATE_NEW_PRIVATE_LINK ) ) {
			$data['id'] = $attachment_id;
			$service    = new PDA_Services();
			$service->check_before_create_private_link( $data );
		}

		//Auto sync file to s3
		do_action( PDA_Hooks::PDA_HOOK_AFTER_PROTECT_FILE_WHEN_UPLOAD, $attachment_id );

		return $move_result;
	}

	function add_button_handle_protect_or_unprotect( $actions, $post, $detached ) {
		$gold_function = new Pda_Gold_Functions();
		if ( $gold_function->pda_check_role_protection() ) {
			$repo = new PDA_v3_Gold_Repository();
			wp_enqueue_script( $this->plugin_name . '-handle-protect-file-js', plugin_dir_url( __FILE__ ) . 'js/prevent-direct-access-gold-handle-protect-file.js', array(), $this->version );
			wp_enqueue_style( $this->plugin_name . '-handle-protect-file-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-handle-protect-file.css', array(), $this->version, 'all' );

			if ( ! $repo->is_protected_file( $post->ID ) ) {
				$actions['pda_protect'] = '<a id="pda-protect-file_' . $post->ID . '" class="pda-protect-file" title="Protect this file" aria-label="PDA ' . $post->post_title . '">Protect</a>';
			} else {
				$actions['pda_protect'] = '<a id="pda-protect-file_' . $post->ID . '" class="pda-protect-file" title="Unprotect this file" aria-label="PDA ' . $post->post_title . '">Unprotect</a>';
			}
		}

		return $actions;
	}

	function replace_private_link_for_dflip( $content ) {
		if ( is_plugin_active( 'dflip/dflip.php' ) ) {
			$func_v3 = new Pda_Gold_Functions();
			if ( $func_v3->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ) {
				$selected_posts = $func_v3->pda_get_setting_type_is_array( PDA_v3_Constants::PDA_REPLACED_PAGES_POSTS );
				if ( in_array( get_the_ID(), $selected_posts ) ) {
					$content = $func_v3->find_and_replace_private_link_for_dflip( $content );
				}
			}
		}

		return $content;
	}

	function pda_gold_activate_all_sites() {
		$nonce = $_REQUEST['security_check'];
		if ( ! wp_verify_nonce( $nonce, PDA_v3_Constants::LICENSE_FORM_NONCE ) ) {
			error_log( 'not verify nonce', 0 );
			wp_die( 'invalid_nonce' );
		}
		$activate = new PDA_Activate_All_Sites();
		$activate->push_to_queue( false );
		$activate->save()->dispatch();
		wp_send_json( 0 );
	}

	function pda_auto_activate_new_site( $blog_id, $user_id, $domain, $path ) {
		if ( ! method_exists( 'Yme_AWS_Api_v2', 'updateCountAndUserAgents' ) ) {
			return $blog_id;
		}

		$settings      = new Pda_Gold_Functions();
		$auto_activate = $settings->pda_get_setting_type_is_array( PDA_v3_Constants::PDA_AUTO_ACTIVATE_NEW_SITE );
		if ( 'true' !== $auto_activate ) {
			return $blog_id;
		}

		$pda_gold_functions = new Pda_Gold_Functions();
		$api                = new Yme_AWS_Api_v2();

		$activate_data = array(
			'pda_license_key'  => get_option( 'pda_license_key' ),
			'pda_is_licensed'  => get_option( 'pda_is_licensed' ),
			'pda_License_info' => get_option( 'pda_License_info' ),
		);

		$agents            = ';' . get_blog_details( $blog_id )->domain . get_blog_details( $blog_id )->path;
		$response          = $api->updateCountAndUserAgents( $activate_data['pda_license_key'], $agents, 1 );
		$is_active_license = property_exists( $response, 'status' ) && true === $response->status;

		if ( $is_active_license ) {
			$pda_gold_functions->activate_site_by_id( $blog_id, $activate_data );
		}

		return $blog_id;
	}

	public function pda_gold_activated_statistics() {
		$pda_gold_func   = new Pda_Gold_Functions();
		$sites_activated = get_option( PDA_v3_Constants::PDA_V3_ACTIVATE_ALL_SITES_OPTION_NAME );
		if ( empty( trim( $sites_activated ) ) ) {
			wp_die( 'invalid' );
		}
		$response         = json_decode( $sites_activated );
		$response->status = $pda_gold_func->is_activate_all_sites_async();
		wp_send_json( $response );
	}

	public function before_render_pda_column( $filtered, $post_id ) {
		$func = new Pda_Gold_Functions();
		if ( ! $func->is_move_files_after_activate_async() || empty( get_option( PDA_v3_Constants::PDA_NOTICE_CRONJOB_AFTER_ACTIVATE_OPTION ) ) ) {
			return false;
		}
		?>
		<div class="pda_bulk_processing">
			Handling file...
		</div>
		<?php
		return true;
	}

	public function pda_handle_update_services() {
		$pda_service = new Pda_Update_Service();
		$pda_service->pda_migrate_old_data();
	}

	/**
	 * Check condition to load asset
	 */
	public function pda_add_script_for_check_box_auto_protect_file() {
		global $pagenow, $mode;
		$pda_should_add = wp_script_is( 'media-views' ) || ( 'upload.php' === $pagenow && 'grid' === $mode );
		if ( $pda_should_add ) {
			wp_enqueue_style( $this->plugin_name . '-add-media-css', plugin_dir_url( __FILE__ ) . 'css/prevent-direct-access-gold-add-media.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-media-library-grid-elements-css', plugin_dir_url( __FILE__ ) . 'css/decorate_grid_view.css', array(), $this->version, 'all' );
			wp_enqueue_script( $this->plugin_name . '-media-library-grid-elements-js', plugin_dir_url( __FILE__ ) . 'js/decorate_grid_view.js', array( 'jquery' ), $this->version );
		}
	}

	/**
	 * Handle auto upgrade extension
	 */
	public function pda_handle_upgrade_extension() {

		// TODO: better to define the array of extensions here.
		if ( ! Pda_v3_Gold_Helper::is_plugin_installed( 'wp-pda-ip-block' ) &&
		     ! Pda_v3_Gold_Helper::is_plugin_installed( 'pda-membership' ) ) {
			return;
		}

		$updates = get_plugin_updates();
		if ( true === Pda_v3_Gold_Helper::is_wp_version_compatible( '5.3' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-wp-upgrader-5-3.php';
			$pda_skin = new PDA_WP_Upgrader_Skin_5_3();
		} else {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prevent-direct-access-gold-wp-upgrader.php';
			$pda_skin = new PDA_WP_Upgrader_Skin();
		}

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		$upgrader = new Plugin_Upgrader( $pda_skin );
		foreach ( $updates as $plugin ) {
			if ( 'PDA Access Restriction' === $plugin->Name ) {
				if ( version_compare( $plugin->Version, '1.0.4.5' ) < 0 ) {
					$result = $upgrader->upgrade( $plugin->update->plugin );
					if ( $result ) {
						activate_plugin( $plugin->update->plugin, '', false, true );
					}
				}
			}
			if ( 'PDA Membership Integration' === $plugin->Name ) {
				if ( version_compare( $plugin->Version, '1.1.6' ) < 0 ) {
					$result = $upgrader->upgrade( $plugin->update->plugin );
					if ( $result ) {
						activate_plugin( $plugin->update->plugin, '', false, true );
					}
				}
			}
		}
	}

	/**
	 * Hook before protected file handler
	 */
	public function before_handle_protected_file() {
		$service = new PDA_Services();
		$service->parse_x_cookies_value();
	}

	/**
	 * Handle calculate image srcset
	 *
	 * @param $sources
	 * @param $size_array
	 * @param $image_src
	 * @param $image_meta
	 * @param $attachment_id
	 *
	 * @return array
	 */
	public function pda_handle_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		$repo = new PDA_v3_Gold_Repository();
		if ( ! $repo->is_protected_file( $attachment_id ) ) {
			return $sources;
		}

		$pda_func = new Pda_Gold_Functions();

		// Admin + attachment page
		if ( is_admin() || is_attachment() ) {
			return $pda_func->pda_handle_image_srcset_for_admin_and_attachment_page( $sources, $image_meta, $attachment_id );
		}

		// Front-end
		$sources = $pda_func->pda_handle_image_srcset_for_front_end( $sources, $image_meta, $attachment_id );

		return apply_filters( 'pda_handle_srcset', $sources, $size_array, $image_src, $image_meta, $attachment_id );
	}

	/**
	 * Function handle image meta for srcset
	 *
	 * @param $image_meta
	 * @param $size_array
	 * @param $image_src
	 * @param $attachment_id
	 *
	 * @return image meta
	 */
	public function pda_handle_calculate_image_srcset_meta( $image_meta, $size_array, $image_src, $attachment_id ) {
		/**
		 * Not handle image meta on Admin + attachment page
		 */
		if ( is_admin() || is_attachment() ) {
			return $image_meta;
		}

		$repo = new PDA_v3_Gold_Repository();
		if ( ! $repo->is_protected_file( $attachment_id ) ) {
			return $image_meta;
		}

		$pda_func = new Pda_Gold_Functions;

		// Front-end
		if ( ! $pda_func->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ) {
			return $image_meta;
		}

		$selected_posts = $pda_func->pda_get_setting_type_is_array( PDA_v3_Constants::PDA_REPLACED_PAGES_POSTS );
		if ( ! in_array( get_the_ID(), $selected_posts ) ) {
			return $image_meta;
		}

		return $pda_func->pda_handle_image_meta_for_srcset( $image_meta, $image_src );
	}


	/**
	 * Replace unprotected URL with protected URL.
	 *
	 * @param array $data       Array contains list url & post_id
	 * @param array $conditions Check settings in plugin.
	 *
	 * @return array List URL after handle if setting is turn on.
	 */
	public function pda_handle_link_of_the_content( $data, $conditions ) {
		return $this->gold_service->massage_url_search_and_replace( $data, $conditions );
	}


	/**
	 * Force to get attachment metadata by copying from WordPress wp_get_attachment_metadata function
	 *
	 * @codeCoverageIgnore
	 *
	 * @param $content
	 * @param $post_id
	 *
	 * @return bool|mixed
	 */
	public function handle_attachment_metadata( $content, $post_id ) {
		if ( is_admin() ) {
			return $content;
		}

		$attachment_id = (int) $post_id;
		if ( ! $post = get_post( $attachment_id ) ) {
			return false;
		}

		return get_post_meta( $post->ID, '_wp_attachment_metadata', true );
	}

	/**
	 * Prevent qTranslate X from redirecting REST calls.
	 *
	 * @param string $url_lang Language URL to redirect to.
	 * @param string $url_orig Original URL.
	 * @param array  $url_info Pieces of original URL.
	 *
	 * @return bool
	 * @since 5.3
	 *
	 */
	public function jetpack_no_qtranslate_rest_url_redirect( $url_lang, $url_orig, $url_info ) {
		return Pda_v3_Gold_Helper::get_instance()->jetpack_no_qtranslate_rest_url_redirect( $url_lang, $url_orig, $url_info );
	}

	/**
	 * Handle plugin changes
	 */
	public function handle_plugin_changes() {
		$plugin_changes_service = new PDA_Plugin_Changes();
		$plugin_changes_service->process_plugin_changes();
	}

	/**
	 * @param WP_Screen          $screen
	 * @param array              $permalink_screens
	 * @param Pda_Gold_Functions $function
	 */
	private function notice_for_permalink( $screen, $permalink_screens, $function ) {
		global $is_apache;
		if ( $is_apache && in_array( $screen->id, $permalink_screens, true ) && empty( get_option( 'permalink_structure' ) ) ) {
			if ( ! $function->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
				?>
				<div class="error is-dismissible notice">
					<p>
						<b><?php echo "Prevent Direct Access Gold: "; ?></b> <?php echo esc_html_e( 'You are ', 'prevent-direct-access-gold' ) ?>
						<a rel="noreferrer" target="_blank"
						   href="https://preventdirectaccess.com/docs/known-limitations/#plain-permalink"><?php echo esc_html__( 'using Plain Permalink', 'prevent-direct-access-gold' ) ?></a>
						<?php echo esc_html_e( ' which our plugin doesn\'t support.', 'prevent-direct-access-gold' ) ?>
					</p>
				</div>
				<?php
			}
		}
	}

	/**
	 * @param $options
	 *
	 * @return mixed
	 */
	public function puc_add_token_to_headers( $options ) {
		$license_key = get_option( PDA_v3_Constants::LICENSE_KEY );
		$product_id  = get_site_option( PDA_v3_Constants::APP_ID );
		if ( empty( $product_id ) ) {
			$configs    = include( PDA_V3_BASE_DIR . 'includes/class-prevent-direct-access-gold-configs.php' );
			$product_id = empty( $configs->app_id ) ? '' : $configs->app_id;
		}
		$options['headers'] = array(
			'Accept'        => 'application/json',
			'Authorization' => base64_encode(
				json_encode(
					array(
						'key'        => empty( $license_key ) ? '' : $license_key,
						'product_id' => $product_id,
					)
				) ),
		);

		return $options;
	}

	/**
	 * Check settings before handle the_content.
	 *
	 * @param array $conditions Condition settings. ( Ex: Turn on search & replace in pda gold)
	 * @param array $data       Array contain post_id.
	 *
	 * @return array Conditions after check.
	 */
	public function pda_before_handle_the_content( $conditions, $data ) {
		$conditions[ PDA_v3_Constants::PDA_IS_USING_SEARCH_REPLACE ] = Pda_v3_Gold_Helper::get_instance()->is_use_search_and_replace( $data );
		if ( defined( 'PPW_PRO_VERSION' ) && version_compare( PPW_PRO_VERSION, '1.1.3' ) >= 0 ) {
			$conditions['ppwp_is_using_search_and_replace'] = apply_filters( 'pda_handle_the_content_for_ppwp', true );
		}

		return $conditions;
	}

	/**
	 * Check license expired and upgrade product id.
	 */
	public function pda_gold_recheck_license() {
		wp_send_json( $this->gold_service->recheck_license() );
	}

	/**
	 * Plugin message.
	 *
	 * @param array $plugin_data An array of plugin metadata.
	 * @param array $res         An array of metadata about the available plugin update.
	 */
	public function plugin_update_msg_cb( $plugin_data, $res ) {
		// Check download url is exist.
		if ( ! empty( $res->package ) ) {
			return;
		}
		$admin_url = get_admin_url();
		printf(
			'<span style="display:block;padding:10px 20px;margin:10px 0; background: #D54E21; color: #fff;"><strong>UPDATE UNAVAILABLE!</strong>&nbsp;&nbsp;&nbsp;<a href="%1$sadmin.php?page=pda-gold&tab=license" target="_blank" style="color: #fff; text-decoration: underline;">%2$s</a> %3$s <a href="https://preventdirectaccess.com/docs/entering-license-key/#update" target="_blank" style="color: #fff; text-decoration: underline;">%4$s</a> %5$s.</span>',
			$admin_url,
			__( 'Enter license key', 'prevent-direct-access-gold' ),
			__( 'or make sure', 'prevent-direct-access-gold' ),
			__( 'your license is valid', 'prevent-direct-access-gold' ),
			__( 'for automatic updates', 'prevent-direct-access-gold' )
		);
	}


	/**
	 * Fires once activated plugins have loaded.
	 * Pluggable functions are also available at this point in the loading order.
	 */
	public function plugins_loaded() {
		$wpml_integration = new PDA_WPML();
		if ( $wpml_integration->is_installed() ) {
			$wpml_integration->init();
		}

		$poly_integration = new PDA_Polylang();
		if ( $poly_integration->is_installed() ) {
			$poly_integration->init();
		}

		PDA_Shortcode_Service::get_instance();
	}

	public function register_plugins_links( $links, $plugin_file_name ) {
		if ( PDA_PLUGIN_BASE_NAME === $plugin_file_name ) {
			$links[] = '<span style="color: #cc0000; font-weight: bold;">' . __( 'Do not update plugin before activating license', 'prevent-direct-access-gold' ) . '</span>';
		}

		return $links;
	}

	public function puc_handle_result( $info ) {
		if ( ! isset( $info->message ) ) {
			delete_option( 'pda_gold_update_info' );

			return $info;
		}

		$blocked_download = empty( $info->download_url );
		$data = wp_json_encode(
			array(
				'message'          => $info->message,
				'blocked_download' => $blocked_download,
			)
		);

		update_option( 'pda_gold_update_info', $data, 'no' );

		return $info;
	}
	/**
	 * Load notice dismiss script.
	 */
	public function load_notice_dismiss_script() {
		global $pda_load_notice_dismiss_script;
		if ( ! $pda_load_notice_dismiss_script ) {
			return;
		}

		?>
		<script>
		  (function ($) {
			// Create cookie
			function createCookie(name, value, days) {
			  var expires

			  if (days) {
				var date = new Date()
				date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000)
				expires = '; expires=' + date.toGMTString()
			  } else {
				expires = ''
			  }

			  document.cookie = name + '=' + value + expires + '; path=/'
			}

			$(function () {
			  $('div.notice.pda-notice.is-dismissible button').on('click', function () {
				  $notice = $(this).parent().parent();
				  $notice.hide(100);
				  $name = $notice.data('pdaname');
				  createCookie($name, '1', 30);
				}
			  );
			});
		  })(jQuery);
		</script>
		<?php
	}

}
