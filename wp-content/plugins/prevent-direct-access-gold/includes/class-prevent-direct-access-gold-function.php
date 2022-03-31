<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You do not have sufficient permissions to access this file.' );
}

/**
 * Class Pda_Gold_Functions
 */
class Pda_Gold_Functions {

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public static function tooltip_content( $key ) {
		$contents = array(
			'enable_remote_logs'                  => 'Allow us to get necessary data that makes troubleshooting easier when something goes wrong. No sensitive data is tracked.',
			'grant_access_to_all_logged-in_users' => 'All logged-in users including admins and subscribers will be able to access your protected files.',
			'grant_access_to_these_roles_only'    => 'Only allow the following user roles to access your protected files.',
			'private_url_prefix'                  => 'Change “private”  prefix of your download link.',
			'auto-protect_future_uploaded_files'  => 'Prevent Direct Access will automatically protect all your future uploaded files.',
			'prevent_hotlinking'                  => 'Stop unwanted users from stealing and using your images and files without permission.',
			'pda_gold_enable_directory_listing'   => 'Disable directory browsing of all folders and subdirectories.',
			'pda_gold_no_access_page'             => 'Select which page to display when users have no access to your protected files.',
			'file_access_permission'              => 'Select user roles who can access your protected files.',
			'hiden_wp_version'                    => 'Removing WordPress generator meta tag.',
			'block_access_files'                  => 'Blocking access to readme.html, license.txt and wp-config-sample.php',
//             'pda_enable_protection' => "While this option blocks Google from indexing your protected files through robots.txt, it doesn't block their original links, which are still accessible by anyone."
			'home_url'                            => 'The homepage URL of your site.',
			'site_url'                            => 'The root URL of your site.',
			'version'                             => 'The version of Prevent Direct Access Gold installed on your site.',
			'wp_version'                          => 'The version of WordPress installed on your site.',
			'wp_multisite'                        => 'Whether or not you have WordPress Multisite enabled.',
			'wp_memory_limit'                     => 'The maximum amount of memory (RAM) that your site can use at one time.',
			'wp_debug_mode'                       => 'Displays whether or not WordPress is in Debug Mode.',
			'wp_cron'                             => 'Displays whether or not WP Cron Jobs are enabled.',
			'language'                            => 'The current language used by WordPress. Default = English',
			'external_object_cache'               => 'Displays whether or not WordPress is using an external object cache.',
			'server_info'                         => 'Information about the web server that is currently hosting your site.',
			'php_version'                         => 'The version of PHP installed on your hosting server.',
			'php_post_max_size'                   => 'The largest filesize that can be contained in one post.',
			'php_max_execution_time'              => 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups).',
			'php_max_input_vars'                  => 'The maximum number of variables your server can use for a single function to avoid overloads.',
			'curl_version'                        => 'The version of cURL installed on your server.',
			'suhosin_installed'                   => 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself. If enabled on your server, Suhosin may need to be configured to increase its data submission limits.',
			'mysql_version'                       => 'The version of MySQL installed on your hosting server.',
			'max_upload_size'                     => 'The largest filesize that can be uploaded to your WordPress installation.',
			'default_timezone'                    => 'The default timezone for your server.',
			'fsockopen_or_curl_enabled'           => 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.',
			'soapclient_enabled'                  => 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.',
			'domdocument_enabled'                 => 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.',
			'gzip_enabled'                        => 'GZip (gzopen) is used to open the GEOIP database from MaxMind.',
			'mbstring_enabled'                    => 'Multibyte String (mbstring) is used to convert character encoding, like for emails or converting characters to lowercase.',
			'remote_post_successful'              => 'PayPal uses this method of communicating when sending back transaction information.',
			'remote_get_successful'               => 'Prevetn Direct Access Gold plugins may use this method of communication when checking for plugin updates.',
			'pda_database_version'                => 'The version of Prevent Direct Access Gold that the database is formatted for. This should be the same as your Prevent Direct Access Gold version.',
			'database_prefix'                     => 'Prefix of table in Wordpress',
			'secure_connection'                   => 'Is the connection to your store secure?',
			'hide_errors'                         => 'Error messages can contain sensitive information about your store environment. These should be hidden from untrusted visitors.',
			'name_theme'                          => 'The name of the current active theme.',
			'version_theme'                       => 'The installed version of the current active theme.',
			'author_url_theme'                    => 'The theme developers URL.',
			'is_child_theme_theme'                => 'Displays whether or not the current theme is a child theme.',
			'pda_log_writable'                    => 'The PDA log directory',
			'pda_gold_remove_license'             => 'If this option is checked, your license and ALL related data will be removed from the database upon uninstall. Your license may NOT be used on this website again or elsewhere anymore.',
			'use_redirect_urls'                   => 'You should enable this option ONLY when you\'re using WordPress.com or Ngnix hostings that don\'t allow rewrite rules modification',
			'auto-create-new-private-link'        => 'Auto-generate a private download link after a file is protected',
			'auto-replace-protected-file'         => 'Auto-replace unprotected file URLs already embedded in the following posts and pages',
			'auto-replace-pages-posts'            => 'Only replace unprotected file URLs on these pages or posts',
			'force-download'                      => 'Force download',
			'activate_all_sites'                  => 'Activate main site\'s license on existing subsites',
			'count_activated_for_multisite'       => 'The number of new subsites in which the main license is activated',
		);

		return $contents[ $key ];
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public static function update_site_settings( $name, $value ) {
		$options = get_site_option( PDA_v3_Constants::SITE_OPTION_NAME, false );
		if ( $options === false ) {
			$options = array();
		} else {
			$options = unserialize( $options );
		}
		$options[ $name ] = $value;
		update_site_option( PDA_v3_Constants::SITE_OPTION_NAME, serialize( $options ) );
	}

	/**
	 * @param bool $is_deactivate
	 */
	public static function plugin_clean_up( $is_deactivate = false ) {
		remove_filter( 'mod_rewrite_rules', 'Prevent_Direct_Access_Gold_Htaccess::pda_handle_htaccess_rewrite_rules', 9999, 2 );
		flush_rewrite_rules();

		$cronjob_handler = new PDA_Cronjob_Handler();
		$cronjob_handler->unschedule_ls_cron_job();
		$cronjob_handler->unschedule_delete_expired_private_links_cron_job();

		$timestamp = wp_next_scheduled( 'pda_cleanup_logs' );
		wp_unschedule_event( $timestamp, 'pda_cleanup_logs' );

		if ( ! $is_deactivate ) {
			$function       = new Pda_Gold_Functions();
			$is_remove_data = $function->get_site_settings( PDA_v3_Constants::REMOVE_LICENSE_AND_ALL_DATA );

			if ( is_multisite() ) {
				$function->delete_license_and_all_data_in_multisite( $is_remove_data );
			} else {
				$function->delete_license_and_all_data_in_singlesite( $is_remove_data );
			}

			Prevent_Direct_Access_Gold_Htaccess::handle_htaccess_file_in_folder( 'false' );

			delete_site_option( PDA_v3_Constants::FULLY_ACTIVATED );
			delete_site_option( PDA_v3_Constants::SITE_OPTION_NAME );
			delete_site_option( PDA_v3_Constants::APP_ID );
			delete_site_option( PDA_v3_Constants::PDA_OPTION_PLUGIN_CHANGE_VERSION );
			delete_option( PDA_v3_Constants::LICENSE_EXPIRED );

			// Downgrade DB version of PDA Stats to 1.0.
			if ( false !== get_option( 'jal_db_version_stats' ) ) {
				update_option( 'jal_db_version_stats', '1.0' );
			}
		}
	}

	/**
	 *
	 */
	public static function move_files_after_deactivate() {
		$function     = new Pda_Gold_Functions();
		$status_files = $function->get_status_move_files();
		$total_files  = $status_files['total_files'];
		if ( ! empty( $total_files ) && intval( $total_files ) >= PDA_v3_Constants::PDA_MAX_VALUE_MOVE_FILES ) {
			if ( $function->is_move_files_after_activate_async() ) {
				wp_die( '<pre>We’re handling ' . $status_files['num_of_protected_files'] . '/' . $status_files['total_files'] . ' protected files. Please come back in a while.<br><a href="' . get_admin_url() . '">Click here</a> to go back to your admin dashboard.
			</pre>' );
			}
			$move_files_after_deactivate = new PDA_Move_Files_After_Deactivate();
			$move_files_after_deactivate->push_to_queue( array() );
			$move_files_after_deactivate->save()->dispatch();
		} else {
			$repository = new PDA_v3_Gold_Repository();
			$repository->un_protect_files();
		}
	}

	/**
	 * @param $message
	 */
	public static function write_debug_log( $message ) {
		$logger = new PDA_Logger();
		$logger->info( $message );

	}

	/**
	 * Delete license and all data
	 *
	 * @param bool $is_remove_data
	 */
	public function delete_license_and_all_data_in_singlesite( $is_remove_data ) {
		delete_option( PDA_v3_Constants::MIGRATE_DATA );
		delete_option( PDA_v3_Constants::OPTION_NAME );
		if ( $is_remove_data ) {
			//Delete license
			delete_site_option( PDA_v3_Constants::LICENSE_OPTIONS );
			delete_site_option( PDA_v3_Constants::LICENSE_KEY );
			delete_site_option( PDA_v3_Constants::LICENSE_ERROR );
			delete_site_option( PDA_v3_Constants::LICENSE_INFO );
			delete_site_option( PDA_v3_Constants::$db_version );

			$this->drop_pda_database();
		}
	}

	/**
	 * Drop pda database
	 *
	 * @param string $blog_id
	 */
	public function drop_pda_database( $blog_id = '' ) {
		global $wpdb;
		if ( empty( $blog_id ) ) {
			$wp_prefix = $wpdb->prefix;
		} else {
			$wp_prefix = $wpdb->get_blog_prefix( $blog_id );
		}

		//Drop table
		$table_pda = $wp_prefix . 'prevent_direct_access';
		$wpdb->query( "DROP TABLE IF EXISTS $table_pda" );

		//Delete all file backup
		$table_postmeta = $wp_prefix . "postmeta";
		$wpdb->delete( $table_postmeta, array(
			'meta_key'   => '_pda_protection',
			'meta_value' => 1,
		) );
	}

	/**
	 * Delete license and all data of multisite.
	 *
	 * @param bool $is_remove_data Is remove data;
	 */
	public function delete_license_and_all_data_in_multisite( $is_remove_data = false ) {
		foreach ( get_sites() as $key => $value ) {
			$blog_id = $value->blog_id;
			delete_blog_option( $blog_id, PDA_v3_Constants::MIGRATE_DATA );
			delete_blog_option( $blog_id, PDA_v3_Constants::OPTION_NAME );
			if ( $is_remove_data ) {
				delete_blog_option( $blog_id, PDA_v3_Constants::LICENSE_OPTIONS );
				delete_blog_option( $blog_id, PDA_v3_Constants::LICENSE_KEY );
				delete_blog_option( $blog_id, PDA_v3_Constants::LICENSE_ERROR );
				delete_blog_option( $blog_id, PDA_v3_Constants::LICENSE_INFO );
				delete_blog_option( $blog_id, PDA_v3_Constants::$db_version );

				$this->drop_pda_database( $blog_id );
			}
		}
	}

	/**
	 * TODO: this function name is quite confusing with the inside logic and rename to has_site_settings.
	 * Check whether the setting existed and its boolean value
	 * "true" or "1" is true. Otherwise it is false.
	 *
	 * @param string $setting_name The setting's name.
	 *
	 * @return bool
	 */
	public function get_site_settings( $setting_name ) {
		$settings = get_site_option( PDA_v3_Constants::SITE_OPTION_NAME );

		return $this->check_option_settings( $settings, $setting_name );
	}

	/**
	 * @param $settings
	 * @param $nameSettings
	 *
	 * @return bool
	 */
	public function check_option_settings( $settings, $nameSettings ) {
		if ( $settings ) {
			$options = @unserialize( $settings );
			if ( false === $options || empty( $options ) ) {
				return false;
			}
			if ( array_key_exists( $nameSettings, $options ) && ( $options[ $nameSettings ] == "true" || $options[ $nameSettings ] == "1" ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return string
	 */
	public static function get_license_type() {
		$app_id = get_site_option( PDA_v3_Constants::APP_ID, null );
		if ( is_null( $app_id ) ) {
			return "N/A";
		}
		switch ( $app_id ) {
			case "583147":
				return "1-site license";
			case "584087":
				return "3-site license";
			case "78013383":
				return "10-site license";
			case "77844608":
				return "15-site license";
			case "584088":
				return "Unlimited-site license";
			case "77814469":
				return "Developer license";
			case "77917258":
				return "1-site lifetime license";
			case "77917256":
				return "3-site lifetime license";
			case "78013421":
				return "10-site lifetime license";
			case "77917246":
				return "15-site lifetime license";
			default:
				return "N/A";
		}
	}

	public static function is_life_time( $product_id ) {
		$life_time_products = [ "77917258", "77917256", "78013421", "77917246" ];

		return in_array( $product_id, $life_time_products, true );
	}

	/**
	 * @return bool
	 */
	static function is_data_migrated() {
		return '1' === get_option( PDA_v3_Constants::MIGRATE_DATA );
	}

	/**
	 * @return bool
	 */
	static function is_fully_activated() {
		return ! ! get_site_option( PDA_v3_Constants::FULLY_ACTIVATED, false );
	}

	/**
	 *
	 */
	public static function fully_activated() {
		update_site_option( PDA_v3_Constants::FULLY_ACTIVATED, true );
	}

	/**
	 * Check whether to version 2.x is activated before.
	 *
	 * @return bool
	 */
	public static function is_v2_existed() {

		// It means the v2 activated and loaded the PHP files.
		// Only need to check the class Pda_Gold_Admin existed.
		if ( class_exists( 'Pda_Gold_Admin' ) ) {
			return true;
		}

		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugins      = get_plugins();
		$is_activated = false;

		foreach ( $plugins as $plugin_path => $plugin ) {

			if ( PDA_SLUG === $plugin['TextDomain'] ) {
				// Here mean the site installed the v2. Need to double check the version.
				if ( version_compare( $plugin['Version'], '3.0.0', '<' ) ) {
					$is_activated = true;
					break;
				}
			}
		}

		return $is_activated;
	}

	/**
	 * @return bool
	 * TODO: need to apply UT
	 */
	public static function check_unlimited_license() {
		$app_id   = get_site_option( PDA_v3_Constants::APP_ID, null );
		$licenses = [
			PDA_v3_Constants::UN_LIMITED_LICENSE,
			PDA_v3_Constants::FIFTEEN_SITE_LICENSE,
			PDA_v3_Constants::FIFTEEN_SITE_LIFETIME_LICENSE,
			PDA_v3_Constants::DEV_LICENSE,
		];

		return in_array( $app_id, $licenses );
	}

	/**
	 * @return mixed|PDA_Logger|void|null
	 */
	public static function pda_get_logger() {
		static $logger = null;

		$class = apply_filters( 'pda_logging_class', 'PDA_Logger' );

		if ( null === $logger || ! is_a( $logger, $class ) ) {
			$implements = class_implements( $class );

			if ( is_array( $implements ) && in_array( 'PDA_Logger_Interface', $implements, true ) ) {
				if ( is_object( $class ) ) {
					$logger = $class;
				} else {
					$logger = new $class();
				}
			} else {
				$logger = is_a( $logger, 'PDA_Logger' ) ? $logger : new PDA_Logger();
			}
		}

		return $logger;
	}

	/**
	 *
	 */
	public function set_default_settings() {
		$this->set_migration_or_not();

		// Set default setting for file access permission
		$pda_service = new PDA_Services();
		$pda_service->pda_gold_set_default_setting_for_fap();
		$pda_service->pda_gold_set_default_setting_for_role_protection();
	}

	/**
	 *
	 */
	public function set_migration_or_not() {
		if ( get_option( PDA_v3_Constants::LICENSE_OPTIONS, null ) !== "1" ) {
			update_option( PDA_v3_Constants::MIGRATE_DATA, '1', 'no' );
		}
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function prefix_roles_name( $name ) {
		$settings = get_site_option( PDA_v3_Constants::SITE_OPTION_NAME );
		if ( $settings ) {
			$options = @unserialize( $settings );
			if ( false === $options || empty( $options ) ) {
				return PDA_v3_Constants::$default_private_link_prefix;
			}
			if ( array_key_exists( $name, $options ) && ! empty( $options[ $name ] ) ) {
				return $options[ $name ];
			}
		}

		return PDA_v3_Constants::$default_private_link_prefix;
	}


	/**
	 * Get apply logged user
	 *
	 * @param $enable_protection
	 *
	 * @return array
	 */
	public function getPdaApplyLogged( $enable_protection, $is_logged, $whitelist_roles ) {
		// If option Block Search Indexing Only is enabled then set FAP to anyone.
		error_log( 'getPdaApplyLogged: ' . wp_json_encode( $enable_protection ) );
		if ( "1" === $enable_protection ) {
			$value = 'anyone';
		} else {
			if ( $is_logged == false ) {
				$value = 'blank';
				error_log( 'White list users: ' . wp_json_encode( $whitelist_roles ) );
				if ( ! empty( $whitelist_roles ) ) {
					$value = 'custom_roles';
				}
			} else {
				$value = 'logged_users';
			}
		}

		$file_access_permission = [ 'file_access_permission' => $value ];

		return $file_access_permission;
	}

	/**
	 * @param $nameSettings
	 *
	 * @return bool
	 */
	public function getSettings( $nameSettings ) {
		$settings = get_option( PDA_v3_Constants::OPTION_NAME );

		return $this->check_option_settings( $settings, $nameSettings );
	}

	/**
	 * Get hostname without www
	 *
	 * @return string
	 */
	public function get_hostname() {
		$host_name = parse_url( home_url(), 1 );

		return preg_replace( '/^(www.)/i', '', $host_name );
	}

	/**
	 * Check roles with white list in Default Setting
	 *
	 * @param        $attachment_id
	 * @param string $fap_type
	 *
	 * @return bool
	 */
	public function check_roles_with_whitelist( $attachment_id, &$fap_type = '' ) {
		$option   = $this->pda_get_setting_type_is_string( PDA_v3_Constants::FILE_ACCESS_PERMISSION );
		$fap_type = $option;
		self::write_debug_log( sprintf( 'File access permissions option: %s', $option ) );

		if ( $option === PDA_v3_Constants::PDA_FAP['DEFAULT_SETTING']['CUSTOM_ROLES'] ) {
			return $this->check_user_login_by_roles();
		}

		return $this->check_roles_is_allowed_access( $option, $attachment_id );
	}

	/**
	 * Check roles is allowed access for default setting & FAP
	 *
	 * @param $option
	 * @param $attachment_id
	 *
	 * @return bool
	 */
	public function check_roles_is_allowed_access( $option, $attachment_id ) {
		switch ( $option ) {
			case PDA_v3_Constants::PDA_FAP['MEDIA_FILE']['ADMIN_USER']: // FAP for media file
			case PDA_v3_Constants::PDA_FAP['DEFAULT_SETTING']['ADMIN_USER']: // FAP in default setting
				return current_user_can( 'manage_options' );

			case PDA_v3_Constants::PDA_FAP['AUTHOR']:
				return $this->is_post_author( $attachment_id );

			case PDA_v3_Constants::PDA_FAP['MEDIA_FILE']['LOGGED_IN_USER']: // FAP for a file
			case PDA_v3_Constants::PDA_FAP['DEFAULT_SETTING']['LOGGED_IN_USER']: // FAP default setting
				return is_user_logged_in();

			case PDA_v3_Constants::PDA_FAP['ANYONE']:
				return true;
		}

		return false;
	}

	/**
	 * Private function to only return the array.
	 *
	 * @param array  $settings Settings from db
	 * @param string $name     Key need to get.
	 *
	 * @return array
	 */
	private function _get_array_from_setting( $settings, $name ) {
		if ( $settings ) {
			$options = @unserialize( $settings );
			if ( false === $options || empty( $options ) ) {
				return array();
			}
			if ( array_key_exists( $name, $options ) && ! empty( $options[ $name ] ) ) {
				return $options[ $name ];
			}
		}

		return array();
	}

	/**
	 * Get array of values from settings.
	 *
	 * @param string $name Setting key.
	 *
	 * @return array
	 */
	public function pda_get_setting_type_is_array( $name ) {
		$settings = get_option( PDA_v3_Constants::OPTION_NAME );

		return $this->_get_array_from_setting( $settings, $name );
	}

	/**
	 * Get array of values from site settings.
	 *
	 * @param string $name Setting key.
	 *
	 * @return array
	 */
	public function get_site_setting_type_is_array( $name ) {
		$settings = get_site_option( PDA_v3_Constants::SITE_OPTION_NAME );

		return $this->_get_array_from_setting( $settings, $name );
	}

	/**
	 * @deprecated
	 */
	public function selected_roles( $name ) {
		$settings = get_option( PDA_v3_Constants::OPTION_NAME );
		if ( $settings ) {
			$options = @unserialize( $settings );
			if ( false === $options || empty( $options ) ) {
				return array();
			}
			if ( array_key_exists( $name, $options ) && ! empty( $options[ $name ] ) ) {
				return $options[ $name ];
			}
		}

		return array();
	}

	/**
	 * @return bool
	 * @deprecated
	 */
	public function checkUserLoginByRoles() {
		$user_login = wp_get_current_user()->roles;
		self::write_debug_log( sprintf( 'User roles: %s', wp_json_encode( $user_login ) ) );
		$user_roles = $this->pda_get_setting_type_is_array( PDA_v3_Constants::WHITElIST_ROLES );
		self::write_debug_log( sprintf( 'Whitelist roles: %s', wp_json_encode( $user_roles ) ) );
		if ( ! empty( array_intersect( $user_login, $user_roles ) ) ) {
			return true;
		} elseif ( is_super_admin( wp_get_current_user()->ID ) ) {
			if ( in_array( "administrator", $user_roles ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function check_user_login_by_roles() {
		$user_login = wp_get_current_user()->roles;

		self::write_debug_log( sprintf( 'User roles: %s', wp_json_encode( $user_login ) ) );

		$user_roles = $this->pda_get_setting_type_is_array( PDA_v3_Constants::WHITElIST_ROLES );

		self::write_debug_log( sprintf( 'Whitelist roles: %s', wp_json_encode( $user_roles ) ) );

		if ( ! empty( array_intersect( $user_login, $user_roles ) ) ) {
			return true;
		}

		return is_super_admin( wp_get_current_user()->ID ) && in_array( "administrator", $user_roles );
	}

	/**
	 * @return bool
	 */
	public static function is_license_expired() {
		if ( get_option( PDA_v3_Constants::LICENSE_EXPIRED ) === '1' ) {
			return true;
		}

		return false;
	}

	/**
	 * Update expired license flag.
	 *
	 * @return mixed
	 */
	public static function update_license_expired() {
		error_log( 'License has been expired!' );
		self::write_debug_log( 'License has been expired.! Updating...' );

		return update_option( PDA_v3_Constants::LICENSE_EXPIRED, '1', false );
	}

	/**
	 * @param $post_id
	 *     * @return bool
	 */
	public function check_file_synced_s3( $post_id ) {
		$metadata = get_post_meta( $post_id, PDA_v3_Constants::PDA_S3_LINK_META, true );

		return ( $metadata !== false && ! empty( $metadata ) ) ? true : false;
	}

	/**
	 * PDA Gold requires the Free version (>= 2.7.0). If Free < 2.7.0, prevent PDA Gold from being activated.
	 *
	 * @param string $plugin_basename The name of the plugin sub-directory/file.
	 *
	 * @since 3.2.0
	 */
	public function handle_pda_free_version( $plugin_basename ) {
		$version = self::get_pda_free_version();

		// Do nothing if PDA Free has never installed.
		if ( empty( $version ) ) {
			return;
		}

		if ( version_compare( $version, '2.7.0', '<' ) ) {
			$message = self::get_required_pda_free_message( 'update' );
			wp_die( $message );
		}
	}

	/**
	 * Get PDA Free version if it is installed.
	 *
	 * @return string|false Return plugin version if PDA Free is installed, false when plugin is not installed.
	 * @since 3.2.0
	 */
	public static function get_pda_free_version() {
		if ( defined( 'PDAF_VERSION' ) ) {
			return PDAF_VERSION;
		}

		// Check if get_plugins() function exists. This is required on the front end of the
		// site, since it is in a file that is normally only loaded in the admin.
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();
		foreach ( $installed_plugins as $key => $plugin ) {
			if ( PDA_v3_Constants::PDA_FREE_PATH === $key && isset( $plugin['Version'] ) ) {
				return $plugin['Version'];
			}
		}

		return false;
	}

	/**
	 * Check whether the PDA Free has been activated.
	 *
	 * @return bool True if PDA Free is activate.
	 * @since 3.2.0
	 */
	public static function is_pda_free_activated() {
		if ( defined( 'PDAF_VERSION' ) ) {
			return true;
		}

		// Check if is_plugin_active() function exists. This is required on the front end of the
		// site, since it is in a file that is normally only loaded in the admin.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( PDA_v3_Constants::PDA_FREE_PATH );
	}

	/**
	 * Check user can access by FAP of PDA AR.
	 *
	 * @param integer     $attachment_id Attachment ID
	 * @param string      $fap_type      File Access Permission type(Includes: default setting, admin, author, logged-in user, anyone, no user roles, custom roles).
	 * @param false|array $data          FAP Data from PDA AR table with columns (user_roles, user_access, post_id, id).
	 *
	 * @return bool
	 * If has FAP   => true (Allow access file)
	 * Else         => false (Don't allow access file)
	 */
	public function check_user_access_for_ip_block_addon( $attachment_id, &$fap_type, $data = false ) {
		// Don't need get AR data if we passed data for it.
		if ( false === $data ) {
			$data = Wp_Pda_Ip_Block_Admin::get_ip_block_by_post_id( $attachment_id );
		}
		if ( empty( $data->user_roles ) || 'default' === unserialize( $data->user_roles )['type'] ) {
			return $this->check_roles_with_whitelist( $attachment_id, $fap_type );
		}

		$user_roles = unserialize( $data->user_roles );
		$type       = $user_roles["type"];
		$fap_type   = $type;

		return $this->check_fap_for_ip_block( $attachment_id, $user_roles, $type );
	}


	/**
	 * @param $attachment_id
	 *
	 * @return bool
	 */
	public function check_user_access_normal_way( $attachment_id, &$fap_type ) {
		$type     = get_post_meta( $attachment_id, PDA_v3_Constants::$pda_meta_key_user_roles, true );
		$fap_type = $type;
		if ( empty( $type ) || 'default' === $type ) {
			return $this->check_roles_with_whitelist( $attachment_id, $fap_type );
		}

		return $this->check_roles_is_allowed_access( $type, $attachment_id );
	}

	/**
	 * Check File Access Permission base on Roles or User
	 *
	 * @param int    $attachment_id The attachment ID.
	 * @param string $fap_type      File Access Permission type(Includes: default setting, admin, author, logged-in user, anyone, no user roles, custom roles).
	 *
	 * @return bool
	 * If has FAP   => true (Allow access file)
	 * Else         => false (Don't allow access file)
	 */
	public function check_file_access_permission_for_post( $attachment_id, &$fap_type = '' ) {
		// Default FAP is author.
		if ( true === $this->is_post_author( $attachment_id ) ) {
			return true;
		}

		if ( Yme_Plugin_Utils::is_plugin_activated( 'membership' ) === - 1 && $this->handle_access_membership_integration( $attachment_id ) ) {
			return true;
		}

		if ( Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) === - 1 && class_exists( 'Wp_Pda_Ip_Block_Admin' ) ) {
			return apply_filters( PDA_v3_Constants::HOOK_PDA_FAP, $this->check_user_access_for_ip_block_addon( $attachment_id, $fap_type ), $attachment_id );
		}

		return $this->check_user_access_normal_way( $attachment_id, $fap_type );
	}

	/**
	 * @param $attachment_id
	 * @param $user_roles
	 * @param $type
	 *
	 * @return bool
	 */
	public function check_fap_for_ip_block( $attachment_id, $user_roles, $type ) {
		if ( $type === PDA_v3_Constants::PDA_FAP['MEDIA_FILE']['CUSTOM_ROLES'] ) {
			return $this->check_user_login_by_custom_roles( $user_roles );
		}

		return $this->check_roles_is_allowed_access( $type, $attachment_id );
	}

	/**
	 * @param $user_roles
	 *
	 * @return bool
	 */
	public function check_user_login_by_custom_roles( $user_roles ) {
		if ( ! array_key_exists( 'roles', $user_roles ) ) {
			return false;
		}
		$roles      = explode( ";", $user_roles["roles"] );
		$user_Login = wp_get_current_user()->roles;
		if ( ! empty( array_intersect( $user_Login, $roles ) ) ) {
			return true;
		}

		return is_super_admin( wp_get_current_user()->ID ) && in_array( "administrator", $roles );
	}

	/**
	 * @param $attachment_id
	 *
	 * @return bool
	 */
	public function handle_access_membership_integration( $attachment_id ) {
		if ( class_exists( 'Pda_Membership_Integration_Admin' ) ) {
			$pda_admin = new Pda_Membership_Integration_Admin( "", "" );

			return $pda_admin->pda_check_access_for_membership_integration( $attachment_id );
		}

		return false;
	}

	/**
	 * @param $content
	 *
	 * @return mixed
	 * @deprecated
	 *
	 */
	public function find_and_replace_private_link_for_dflip( $content ) {
		// Replace private link for Dflip plugin
		$content = $this->replace_private_url_for_type_pdf_dflip_plugin( $content );
		$content = $this->replace_private_url_for_thumbnail_dflip_plugin( $content );

		return $content;
	}

	/**
	 * @param $content
	 *
	 * @return mixed
	 * @deprecated
	 *
	 */
	function replace_private_url_for_type_pdf_dflip_plugin( $content ) {
		$elements = array();
		$search   = '\"source\":\"([^\"]*)\"';
		preg_match_all( "/$search/iU", $content, $elements, PREG_PATTERN_ORDER );
		$url_file = array_unique( $elements[1] );

		return $this->pda_v3_handle_content_before_show_ui( $url_file, $content, 'source' );
	}

	/**
	 * @param $content
	 *
	 * @return mixed
	 * @deprecated
	 *
	 */
	function replace_private_url_for_thumbnail_dflip_plugin( $content ) {
		$thumb        = array();
		$search_thumb = '<div\s[^>]*?thumb\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>';
		preg_match_all( "/$search_thumb/iU", $content, $thumb, PREG_PATTERN_ORDER );
		$thumb_link = array_unique( $thumb[1] );

		return $this->pda_v3_handle_content_before_show_ui( $thumb_link, $content, 'thumb' );
	}

	/**
	 * @param $url_file
	 * @param $content
	 * @param $type
	 *
	 * @return mixed
	 */
	function pda_v3_handle_content_before_show_ui( $url_file, $content, $type ) {
		foreach ( $url_file as $file ) {
			$massaged_file = preg_replace( '/^(\S+)-[0-9]{1,4}x[0-9]{1,4}(\.[a-zA-Z0-9\.]{2,})?/', '$1$2', $file );
			if ( $type === 'source' ) {
				$massaged_file = str_replace( "\\", "", $massaged_file );
			}
			$post_id = $this->pda_v3_get_post_id_by_original_link( $massaged_file );
			$repo_v3 = new PDA_v3_Gold_Repository();
			if ( $repo_v3->is_protected_file( $post_id ) ) {
				if ( ! $this->check_file_access_permission_for_post( $post_id ) ) {
					$new_private_link = $this->create_private_link_for_dflip( $post_id );
					$private_link     = Pda_v3_Gold_Helper::get_private_url( $new_private_link );
					if ( $type === 'source' ) {
						$content = str_replace( '"' . $type . '":"' . $file . '"', '"' . $type . '":"' . $private_link . '"', $content );
					} else {
						$content = str_replace( $type . '="' . $file . '"', $type . '="' . $private_link . '"', $content );
					}
				}
			}
		}

		return $content;
	}

	/**
	 * @param $post_id
	 *
	 * @return string
	 */
	function create_private_link_for_dflip( $post_id ) {
		$repo_v3      = new PDA_v3_Gold_Repository();
		$private_link = Pda_v3_Gold_Helper::generate_unique_string();
		$repo_v3->create_private_link( array(
			'post_id'         => $post_id,
			'is_prevented'    => true,
			'limit_downloads' => 1,
			'url'             => $private_link,
			'type'            => 'p_expired',
		) );

		return $private_link;
	}

	/**
	 * @param $link
	 *
	 * @return bool|int
	 */
	function pda_v3_get_post_id_by_original_link( $link ) {
		$wp_upload_dir  = wp_upload_dir();
		$baseurl        = $wp_upload_dir['baseurl'];
		$meta_value     = str_replace( $baseurl . '/', '', $link );
		$arr_meta_value = explode( '/', $meta_value );
		$repo_v3        = new PDA_v3_Gold_Repository();
		if ( ! in_array( '_pda', $arr_meta_value, true ) ) {
			array_unshift( $arr_meta_value, '_pda' );
		}
		$meta_value = implode( '/', $arr_meta_value );
		$post_id    = $repo_v3->get_post_id_by_meta_value( '_wp_attached_file', $meta_value );
		if ( false !== $post_id ) {
			return $post_id;
		}
		$meta_value = str_replace( '_pda/', '', $meta_value );

		return $repo_v3->get_post_id_by_meta_value( '_wp_attached_file', $meta_value );
	}

	/**
	 * @param $id
	 * @param $activate_data
	 *
	 * @return bool
	 */
	public function activate_site_by_id( $id, $activate_data ) {
		if ( ! get_blog_option( $id, 'pda_license_key' ) ) {
			foreach ( array_keys( $activate_data ) as $key ) {
				update_blog_option( $id, $key, $activate_data[ $key ] );
				$this->set_default_settings_for_site( $id );
			}

			return true;
		}

		return false;
	}

	/**
	 * @return int
	 */
	public function activate_all_sites() {
		if ( ! class_exists( 'Yme_AWS_Api_v2' ) ) {
			return 0;
		}
		$count_site_activated = 0;
		if ( is_multisite() ) {
			$activate_data = array(
				'pda_license_key'  => get_option( 'pda_license_key' ),
				'pda_is_licensed'  => get_option( 'pda_is_licensed' ),
				'pda_License_info' => get_option( 'pda_License_info' ),
			);
			$api           = new Yme_AWS_Api_v2();
			if ( method_exists( $api, 'updateCountAndUserAgents' ) ) {
				$response = $api->getAvailableDomain( $activate_data['pda_license_key'] );
				if ( ! property_exists( $response, 'status' ) && ! property_exists( $response, 'num' ) ) {
					return - 1;
				}
				if ( $response->status === true && ( $response->num > 0 || $response->num === 'Infinity' ) ) {
					$sites = get_sites();
					update_option( PDA_v3_Constants::PDA_V3_ACTIVATE_ALL_SITES_OPTION_NAME, json_encode( array(
						'num' => 0,
					) ) );
					$agents = '';
					if ( $response->num !== 'Infinity' ) {
						$available_activate = $response->num;
					} else {
						$available_activate = count( $sites );
					}
					foreach ( $sites as $site ) {
						if ( $available_activate <= 0 ) {
							break;
						}
						if ( $this->activate_site_by_id( $site->blog_id, $activate_data ) ) {
							$count_site_activated ++;
							$available_activate --;
							$agents = $agents . ';' . get_blog_details( $site->blog_id )->domain . get_blog_details( $site->blog_id )->path;

							Pda_v3_Gold_Helper::set_default_settings_for_multisite( $site->blog_id );

							update_option( PDA_v3_Constants::PDA_V3_ACTIVATE_ALL_SITES_OPTION_NAME, json_encode( array(
								'num' => $count_site_activated,
							) ) );
						}
					}
					if ( $count_site_activated > 0 ) {
						$api->updateCountAndUserAgents( $activate_data['pda_license_key'], $agents, $count_site_activated );
					}
				}
			}
		}
		update_option( PDA_v3_Constants::PDA_V3_ACTIVATE_ALL_SITES_OPTION_NAME, json_encode( array(
			'num' => $count_site_activated,
		) ) );

		return $count_site_activated;
	}

	/**
	 * @return bool
	 */
	public function is_activate_all_sites_async() {
		$cron_array = _get_cron_array();
		if ( ! $cron_array ) {
			return false;
		}
		foreach ( array_keys( $cron_array ) as $key ) {
			if ( array_keys( $cron_array[ $key ] )[0] === 'wp_pda_activate_all_sites_cron' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $id
	 */
	public function set_default_settings_for_site( $id ) {
		if ( is_null( get_blog_option( $id, PDA_v3_Constants::OPTION_NAME, null ) ) ) {
			$default_options = array(
				"file_access_permission" => "admin_users",
			);
			add_blog_option( $id, PDA_v3_Constants::OPTION_NAME, serialize( $default_options ), '', 'no' );
		}
	}

	/**
	 * This function loaded when render PDA Protection column in media and admin notices.
	 * @return bool
	 * @deprecated
	 */
	public function is_move_files_after_activate_async() {
		$cron_array = _get_cron_array();
		if ( ! $cron_array ) {
			return false;
		}
		foreach ( array_keys( $cron_array ) as $key ) {
			$cron_keys = array_keys( $cron_array[ $key ] );
			if ( isset( $cron_keys[0] ) && 'wp_pda_move_files_after_activate_cron' === $cron_keys[0] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * This function loaded when user click activate plugin and number of files >= 10000000.
	 * @return bool
	 * @deprecated
	 */
	public function is_move_files_after_deactivate_async() {
		$cron_array = _get_cron_array();
		if ( ! $cron_array ) {
			return false;
		}
		foreach ( array_keys( $cron_array ) as $key ) {
			$cron_keys = array_keys( $cron_array[ $key ] );
			if ( isset( $cron_keys[0] ) && 'wp_pda_move_files_after_deactivate_cron' === $cron_keys[0] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function get_status_move_files() {
		$repo = new PDA_v3_Gold_Repository();

		return array(
			'total_files'            => $repo->get_protected_files(),
			'num_of_protected_files' => get_option( PDA_v3_Constants::PDA_NUM_BACKUP_FILES_OPTION ),
		);
	}

	/**
	 * Check whether to show the protection features.
	 *
	 * @return bool
	 */
	public function pda_check_role_protection() {
		/**
		 * Fire hook that inject the logic before checking whitelisted roles.
		 */
		$handled = apply_filters( 'pda_before_handle_role_protection', null );
		if ( is_bool( $handled ) ) {
			return $handled;
		}

		if ( is_super_admin( wp_get_current_user()->ID ) ) {
			return true;
		}
		$user_login = wp_get_current_user()->roles;
		foreach ( $user_login as $role ) {
			if ( 'administrator' === $role ) {
				return true;
			}
		}
		$role_access = $this->pda_get_setting_type_is_array( PDA_v3_Constants::PDA_GOLD_ROLE_PROTECTION );
		if ( ! empty( array_intersect( $user_login, $role_access ) ) ) {
			return true;
		}

		return apply_filters( 'pda_check_role_protection', false );
	}

	/**
	 * Get setting type is string
	 *
	 * @param $name_settings
	 *
	 * @return string
	 */
	public function pda_get_setting_type_is_string( $name_settings ) {
		$settings = get_option( PDA_v3_Constants::OPTION_NAME, false );
		if ( $settings ) {
			$options = @unserialize( $settings );
			if ( false === $options || empty( $options ) ) {
				return '';
			}
			if ( array_key_exists( $name_settings, $options ) && $options[ $name_settings ] !== '' ) {
				return $options[ $name_settings ];
			}
		}

		return '';
	}

	/**
	 * Send file with condition "no access page"
	 */
	public function pda_file_not_found() {
		$no_access_page = $this->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE );
		if ( 'search-page-post' === $no_access_page ) {
			$nap_existing_page_post = $this->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_NAP_EXISTING_PAGE_POST );
			$link                   = explode( ';', $nap_existing_page_post )[0];
			header( 'Location: ' . $link );
			exit();
		} elseif ( 'custom-link' === $no_access_page ) {
			$nap_custom_link = $this->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_NAP_CUSTOM_LINK );
			$this->handle_redirect_link_no_access_page( $nap_custom_link );
		} else {
			$this->pda_get_template_404();
		}
	}

	/**
	 * Get template 404
	 */
	public function pda_get_template_404() {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	}

	/**
	 * Handle redirect for no access page
	 *
	 * @param $link
	 */
	public function handle_redirect_link_no_access_page( $link ) {
		$custom_link   = strtok( $link, '?' );
		$attachment_id = $this->pda_v3_get_post_id_by_original_link( $custom_link );
		if ( $attachment_id ) {
			$repo = new PDA_v3_Gold_Repository();
			if ( $repo->is_protected_file( $attachment_id ) ) {
				$this->pda_get_template_404();
			} else {
				header( 'Location: ' . $link );
				exit();
			}
		} else {
			header( 'Location: ' . $link );
			exit();
		}
	}

	/**
	 * Handling the file path which having the file size.
	 *
	 * @param string $file_path The file's path
	 * @param string $size      The file's size
	 *
	 * @return string
	 */
	public function handle_file_size( $file_path, $size ) {
		$files_path_components = explode( '/', $file_path );
		$file_name             = array_pop( $files_path_components );
		$ext                   = pathinfo( $file_name, PATHINFO_EXTENSION );
		$file_name             = str_replace( '.' . $ext, '', $file_name ) . '-' . $size . '.' . $ext;
		array_push( $files_path_components, $file_name );

		return implode( '/', $files_path_components );
	}

	/**
	 * replace srcset for file no size
	 *
	 * @param $attachment_id
	 *
	 * @return mixed
	 */
	public function pda_replace_srcset_for_file_no_size( $attachment_id, $value ) {
		$url          = wp_get_attachment_url( $attachment_id );
		$value['url'] = $url;

		return $value;
	}

	/**
	 * Handle srcset for image when enable raw URL
	 *
	 * @param $sources
	 * @param $image_meta
	 * @param $attachment_id
	 *
	 * @return array
	 */
	public function pda_replace_srcset_when_enable_raw_url( $sources, $image_meta, $attachment_id ) {
		$url = wp_get_attachment_url( $attachment_id );

		return array_map( function ( $key, $value ) use ( $image_meta, $attachment_id, $url ) {
			if ( $image_meta['width'] === $key ) {
				return $this->pda_replace_srcset_for_file_no_size( $attachment_id, $value );
			}

			foreach ( $image_meta['sizes'] as $sizes ) {
				if ( $key !== $sizes['width'] ) {
					continue;
				}

				$query_str = parse_url( $url, PHP_URL_QUERY );
				parse_str( $query_str, $query_params );
				if ( array_key_exists( PDA_v3_Constants::$secret_param, $query_params ) ) {
					$pda_v3_pf = $query_params[ PDA_v3_Constants::$secret_param ];
					$pda_v3_pf = explode( '/', $pda_v3_pf );
					array_pop( $pda_v3_pf );
					array_push( $pda_v3_pf, $sizes['file'] );
					$pda_v3_pf = implode( '/', $pda_v3_pf );
					$url       = strtok( $url, '?' );
					$url       = $url . '?' . PDA_v3_Constants::$secret_param . '=' . $pda_v3_pf;
				}

				$value['url'] = $url;

				return $value;
			}
		}, array_keys( $sources ), $sources );
	}

	/**
	 * Get the attachment path relative to the upload directory.
	 * Define this function because _wp_get_attachment_relative_path is a private function.
	 * This function clone from wordpress.
	 *
	 * @param string $file Attachment file name.
	 *
	 * @return string Attachment path relative to the upload directory.
	 * @link https://developer.wordpress.org/reference/functions/_wp_get_attachment_relative_path/
	 */
	public function get_attachment_relative_path( $file ) {
		if ( function_exists( '_wp_get_attachment_relative_path' ) ) {
			return _wp_get_attachment_relative_path( $file );
		}

		$dirname = dirname( $file );

		if ( '.' === $dirname ) {
			return '';
		}

		if ( false !== strpos( $dirname, 'wp-content/uploads' ) ) {
			// Get the directory name relative to the upload directory (back compat for pre-2.7 uploads)
			$dirname = substr( $dirname, strpos( $dirname, 'wp-content/uploads' ) + 18 );
			$dirname = ltrim( $dirname, '/' );
		}

		return $dirname;
	}

	/**
	 * Replace srcset for file search & replace
	 *
	 * @param array $sources       Sources.
	 * @param array $image_meta    Image metadata.
	 * @param int   $attachment_id Attachment ID.
	 *
	 * @return array
	 */
	public function pda_replace_srcset_for_search_replace( $sources, $image_meta, $attachment_id ) {
		global $pda_dirname;
		if ( empty( $pda_dirname ) ) {
			return $sources;
		}
		// Retrieve the uploads sub-directory from the full size image.
		$dirname = $this->get_attachment_relative_path( $image_meta['file'] );

		if ( $dirname ) {
			$dirname = trailingslashit( $dirname );
		}

		$upload_dir    = wp_get_upload_dir();
		$image_baseurl = trailingslashit( $upload_dir['baseurl'] ) . $dirname;

		/*
		 * If currently on HTTPS, prefer HTTPS URLs when we know they're supported by the domain
		 * (which is to say, when they share the domain name of the current request).
		 */
		if ( is_ssl() && 'https' !== substr( $image_baseurl, 0, 5 ) && parse_url( $image_baseurl, PHP_URL_HOST ) === $_SERVER['HTTP_HOST'] ) {
			$image_baseurl = set_url_scheme( $image_baseurl, 'https' );
		}

		$result = array_map(
			function ( $value ) use ( $image_meta, $attachment_id, $image_baseurl, $pda_dirname ) {
				$position = strpos( $value['url'], $image_baseurl );
				if ( false === $position ) {
					return $value;
				}
				$value['url'] = substr_replace( $value['url'], $pda_dirname . '/', strlen( $image_baseurl ), 0 );

				return $value;
			},
			$sources
		);

		// Destroy dirname global.
		$pda_dirname = '';

		return $result;
	}

	public function pda_handle_image_meta_for_srcset( $image_meta, $image_src ) {
		if ( empty( $image_meta['file'] ) ) {
			return $image_meta;
		}

		if ( false !== strpos( $image_src, $image_meta['file'] ) ) {
			return $image_meta;
		}

		/**
		 * Need to improve this code to avoid smell code
		 * Solution: save pda_dirname to meta of image.
		 */
		global $pda_dirname;
		$pda_dirname        = dirname( $image_meta['file'] );
		$image_basename     = wp_basename( $image_meta['file'] );
		$image_meta['file'] = rawurlencode( $image_basename );
		if ( ! empty( $image_meta['sizes'] ) ) {
			$image_meta['sizes'] = array_map( function ( $size ) {
				$size['file'] = rawurlencode( $size['file'] );

				return $size;
			}, $image_meta['sizes'] );
		}

		return $image_meta;
	}

	/**
	 * Handle image srcset for admin and attachment page
	 *
	 * @param $sources
	 * @param $image_meta
	 * @param $attachment_id
	 *
	 * @return mixed
	 */
	public function pda_handle_image_srcset_for_admin_and_attachment_page( $sources, $image_meta, $attachment_id ) {
		if ( $this->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
			return $this->pda_replace_srcset_when_enable_raw_url( $sources, $image_meta, $attachment_id );
		} else {
			return $sources;
		}
	}

	/**
	 * Handle image srcset for front-end
	 *
	 * @param $sources
	 * @param $image_meta
	 * @param $attachment_id
	 *
	 * @return mixed
	 */
	public function pda_handle_image_srcset_for_front_end( $sources, $image_meta, $attachment_id ) {
		if ( ! $this->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
			return $this->pda_replace_srcset_for_search_replace( $sources, $image_meta, $attachment_id );
		}

		/**
		 * Handle Raw URL.
		 */
		if ( ! $this->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ) {
			return $sources;
		}

		$selected_posts = $this->pda_get_setting_type_is_array( PDA_v3_Constants::PDA_REPLACED_PAGES_POSTS );
		if ( ! in_array( get_the_ID(), $selected_posts ) ) {
			return $sources;
		}

		return $this->pda_replace_srcset_when_enable_raw_url( $sources, $image_meta, $attachment_id );
	}

	public static function is_server( $server ) {
		$server_info = isset( $_SERVER['SERVER_SOFTWARE'] ) ? wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) : '';

		return strpos( strtolower( $server_info ), $server ) !== false;
	}

	/**
	 * Check mod rewrite is enable
	 *
	 * @return bool
	 */
	public function pda_mod_rewrite_is_enable() {
		if ( ! function_exists( 'apache_get_modules' ) ) {
			return true;
		}

		return function_exists( 'apache_get_modules' ) && in_array( 'mod_rewrite', apache_get_modules() );
	}

	/**
	 * Check condition and to show notice for multisite
	 *
	 * @return bool
	 * Is not multisite => false
	 * Unlimited license => false
	 * Don't install PDA Multisite plugin => false
	 * Otherwise => true
	 */
	public static function is_show_notice_for_multisite() {
		if ( ! is_multisite() ) {
			return false;
		}

		if ( self::check_unlimited_license() ) {
			return false;
		}

		return ! defined( 'PDA_Multisite_VERSION' );
	}

	/**
	 * Wrapper function to check whether the current user is post's author
	 *
	 * @param int $attachment_id The Attachment's ID
	 *
	 * @return bool
	 *  false: User is anonymous or the post doesn't have the author.
	 *  true: Current user ID equals to post's author ID.
	 */
	public function is_post_author( $attachment_id ) {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		// Post does not have the author or attachment ID cannot find.
		if ( empty( get_post_field( 'post_author', $attachment_id, 'raw' ) ) ) {
			return false;
		}

		return (int) get_current_user_id() === (int) get_post_field( 'post_author', $attachment_id, 'raw' );
	}

	public static function get_supported_crawlers() {
		$crawlers = array(
			array(
				'name'  => 'Facebook',
				'value' => 'facebookexternalhit',
			),
			array(
				'name'  => 'Twitter',
				'value' => 'Twitterbot',
			),
			array(
				'name'  => 'Google',
				'value' => 'Googlebot',
			),
			array(
				'name'  => 'Bing',
				'value' => 'bingbot',
			),
			array(
				'name'  => 'WhatsApp',
				'value' => 'WhatsApp',
			),
		);

		return apply_filters( PDA_v3_Constants::HOOK_SUPPORTED_WEB_CRAWLERS, $crawlers );
	}

	/**
	 * PDA Gold requires the Free version.
	 * This function shows admin notice requiring users to:
	 *  1. Install PDA Free if there is no Free plugin currently.
	 *  2. If PDA Free is installed,
	 *          2.1 If PDA Free < 2.7.0, require to update it to the latest version.
	 *          2.2 If PDA Free >= 2.7.0 but inactive, require to activate it.
	 *
	 * @param string $action Including install, update and  activate.
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	public static function get_required_pda_free_message( $action ) {
		$plugin = 'prevent-direct-access/prevent-direct-access.php';
		switch ( $action ) {

			// Display when PDA Gold is active but Free is not installed.
			case 'install':
				$free_install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=prevent-direct-access' ), 'install-plugin_prevent-direct-access' );
				$message          = '<p>' . __( 'Since version 3.2.0, Prevent Direct Access Gold <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/pda-lite-gold-plugins/">requires the Free version</a> to work properly.', 'prevent-direct-access-gold' ) . '</p>';
				$message          .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $free_install_url, __( 'Install Free version now', 'prevent-direct-access-gold' ) ) . '</p>';

				return $message;

			// Display when PDA Gold is active but Free is inactive.
			case 'activate':
				$free_activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
				$message             = '<p>' . __( 'Since version 3.2.0, Prevent Direct Access Gold <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/pda-lite-gold-plugins/">requires the Free version</a> to work properly.', 'prevent-direct-access-gold' ) . '</p>';
				$message             .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $free_activation_url, __( 'Activate Free version now', 'prevent-direct-access-gold' ) ) . '</p>';

				return $message;

			// Display when PDA Gold is active, install and activate PDA Free < 2.7.0.
			case 'admin-notice-update':
				$message = '<p>' . __( 'Prevent Direct Access Gold is not working properly. Please <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/pda-lite-gold-plugins/">update our PDA Free</a> to its latest version.', 'prevent-direct-access-gold' ) . '</p>';

				return $message;

			// Display when PDA Free < 2.7.0 is active, install and activate PDA Gold.
			case 'update' :
				$message = '<p>' . __( 'Prevent Direct Access Gold is not working properly. Please <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/pda-lite-gold-plugins/">update our PDA Free</a> to its latest version first.', 'prevent-direct-access-gold' ) . '</p>';

				return $message;
		}
	}

	/**
	 * @param null   $blog_id
	 * @param string $path
	 * @param null   $scheme
	 *
	 * @return string
	 */
	public static function get_home_url( $blog_id = null, $path = '', $scheme = null ) {
		global $pagenow;

		$orig_scheme = $scheme;

		if ( empty( $blog_id ) || ! is_multisite() ) {
			$url = get_option( 'home' );
		} else {
			switch_to_blog( $blog_id );
			$url = get_option( 'home' );
			restore_current_blog();
		}

		if ( ! in_array( $scheme, array( 'http', 'https', 'relative' ), true ) ) {
			if ( is_ssl() && ! is_admin() && 'wp-login.php' !== $pagenow ) {
				$scheme = 'https';
			} else {
				$scheme = parse_url( $url, PHP_URL_SCHEME );
			}
		}

		$url = set_url_scheme( $url, $scheme );

		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}

		/**
		 * Filters the home URL.
		 *
		 * @param string      $url         The complete home URL including scheme and path.
		 * @param string      $path        Path relative to the home URL. Blank string if no path is specified.
		 * @param string|null $orig_scheme Scheme to give the home URL context. Accepts 'http', 'https',
		 *                                 'relative', 'rest', or null.
		 * @param int|null    $blog_id     Site ID, or null for the current site.
		 *
		 * @since 3.0.0
		 *
		 */
		return $url;
	}
}

/**
 * Get htaccess rule path
 *
 * @return string Path of Htaccess rule.
 * @since 3.2.0
 * @link  https://developer.wordpress.org/reference/functions/save_mod_rewrite_rules/
 */
function pda_get_htaccess_rule_path() {
	// Ensure get_home_path() is declared.
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$home_path = get_home_path();

	return $home_path . '.htaccess';
}
