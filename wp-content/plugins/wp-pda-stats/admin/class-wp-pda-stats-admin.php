<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.buildwps.com/
 * @since      1.0.0
 *
 * @package    Wp_Pda_Stats
 * @subpackage Wp_Pda_Stats/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Pda_Stats
 * @subpackage Wp_Pda_Stats/admin
 * @author     BWPS <hello@ymese.com>
 */
class Wp_Pda_Stats_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Wp_Pda_Stats_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Pda_Stats_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-pda-stats-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Pda_Stats_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Pda_Stats_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-pda-stats-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function check_plugin_pda_activate() {
		$screen = get_current_screen();
		$allowed_screen = [
			'upload',
			'edit-page',
			'edit-post',
			'plugins',
			'toplevel_page_wp_protect_password_options',
		];

		if ( ! $screen || ! isset( $screen->id ) || ! in_array( $screen->id, $allowed_screen, true ) ) {
			return;
		}

		if ( PDA_Stats_Helpers::get_instance()->is_deactive_pda_or_ppwp() ) {
			$message = PDA_Stats_Constants::YMESE_MESSAGES['PDA_PPWP_NEVER_ACTIVATE'];
		}

		if ( ! empty( $message ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><b><?php echo esc_html( $this->plugin_name ); ?>: </b><?php _e( $message, 'pda-stats' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Add submenu for PDA Gold and PPWP Pro
	 */
	public function add_submenu_pda() {
		// Check is valid license before add submenu for PDA V2.
		if ( get_option( 'pda_is_licensed' ) ) {
			$stats_sub_menu = add_submenu_page(
				'wp_pda_gold_options',
				__( 'Statistics', 'prevent-direct-access-gold' ),
				__( 'Statistics', 'prevent-direct-access-gold' ),
				'manage_options',
				'statistics',
				array( $this, 'pda_options_do_page_statistic' )
			);
			add_action( 'admin_print_styles-' . $stats_sub_menu, array( $this, 'register_settings_assets' ) );
		}

		// Check is valid license before add submenu for PDA V3.
		if ( defined( 'PDA_v3_Constants::LICENSE_OPTIONS' ) && get_option( PDA_v3_Constants::LICENSE_OPTIONS ) ) {
			$stats_sub_menu_v3 = add_submenu_page(
				'pda-gold',
				__( 'Statistics', 'prevent-direct-access-gold' ),
				__( 'Statistics', 'prevent-direct-access-gold' ),
				'manage_options',
				'statistics',
				array( $this, 'pda_options_do_page_statistic' )
			);
			add_action( 'admin_print_styles-' . $stats_sub_menu_v3, array( $this, 'register_settings_assets' ) );
		}

		// Check is valid license before add submenu for PPWP Pro.
		if ( PDA_Stats_Helpers::get_instance()->is_wpp_gold_activated() && defined( 'PPW_Constants::MENU_NAME') ) {
			$stats_sub_menu_ppw = add_submenu_page(
				PPW_Constants::MENU_NAME,
				__( 'Statistics', 'password-protect-wordpress' ),
				__( 'Statistics', 'password-protect-wordpress' ),
				'manage_options',
				'ppw-statistics',
				array( $this, 'pda_options_do_page_statistic' )
			);
			add_action( 'admin_print_styles-' . $stats_sub_menu_ppw, array( $this, 'register_ppwp_settings_assets' ) );
		}
	}

	public function pda_options_do_page_statistic() {
		?>
		<div id="wp-pda-stats-root" class="ppwp-stats-container"></div>
		<?php
	}

	public function register_settings_assets() {
		wp_enqueue_style( $this->plugin_name . 'setttings_stats_style', plugin_dir_url( __FILE__ ) . ( '/js/dist/style/wp-pda-stats.css' ), array() );
		wp_enqueue_script( $this->plugin_name . 'settings_stats', plugin_dir_url( __FILE__ ) . ( '/js/dist/wp-pda-stats-bundle.js' ), array( 'jquery' ) );
		wp_localize_script(
			$this->plugin_name . 'settings_stats',
			'pda_settings_stats',
			array(
				'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
				'home_url'                       => PDA_Stats_Helpers::get_instance()->get_home_url_with_ssl(),
				'is_magic_link_activated'        => Yme_Plugin_Utils::is_plugin_activated( 'magic_link' ),
				'nonce'                          => wp_create_nonce( 'wp_rest' ),
				'api_url'                        => get_rest_url(),
				'is_valid_ppwp'                  => PDA_Stats_Helpers::get_instance()->ppwp_check_version_greater_than( '1.1.6' ),
				'skip_pwd_status'                => PDA_Stats_Helpers::get_instance()->should_skip_password(),
				'has_migrated_sitewide_password' => PDA_Stats_Helpers::get_instance()->check_ppwp_ps_sitewide_activate(),
			)
		);
	}

	public function register_ppwp_settings_assets() {
		wp_enqueue_style( $this->plugin_name . 'ppwp_setttings_stats_style', plugin_dir_url( __FILE__ ) . ( '/js/dist/ppwp-dashboard.css' ), array() );
		wp_enqueue_script( $this->plugin_name . 'ppwp_settings_stats', plugin_dir_url( __FILE__ ) . ( '/js/dist/ppwp-dashboard.js' ), array( 'jquery' ) );
		wp_localize_script(
			$this->plugin_name . 'ppwp_settings_stats',
			'pda_settings_stats',
			array(
				'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
				'home_url'                       => PDA_Stats_Helpers::get_instance()->get_home_url_with_ssl(),
				'is_magic_link_activated'        => Yme_Plugin_Utils::is_plugin_activated( 'magic_link' ),
				'nonce'                          => wp_create_nonce( 'wp_rest' ),
				'api_url'                        => get_rest_url(),
				'is_valid_ppwp'                  => PDA_Stats_Helpers::get_instance()->ppwp_check_version_greater_than( '1.1.6' ),
				'skip_pwd_status'                => PDA_Stats_Helpers::get_instance()->should_skip_password(),
				'is_ppwp_al_activated'           => PDA_Stats_Helpers::get_instance()->check_ppwp_al_activate(),
				'has_migrated_sitewide_password' => PDA_Stats_Helpers::get_instance()->check_ppwp_ps_sitewide_activate(),
				'additional_field_columns'       => PDA_Stats_Service::get_instance()->get_additional_field_columns(),
			)
		);
	}

	public function setup_yme_plugin_rest_api_stats() {
		$stats_api = new PDA_Stats_API();
		$stats_api->register_rest_routes();
	}

	public function get_post_id( $data ) {
		$post_id = $data['post_id'];
		$domain  = $data['domain'];
		Wp_Pda_Stats_Db::insert_tables( $post_id, $domain );
	}

	/**
	 * @param $id
	 * @param string $context
	 *
	 * @return string|void
	 * @deprecated
	 */
	public function get_edit_post_link_for_api( $id, $context = 'display' ) {
		if ( ! $post = get_post( $id ) ) {
			return;
		}

		if ( 'revision' === $post->post_type ) {
			$action = '';
		} elseif ( 'display' == $context ) {
			$action = '&amp;action=edit';
		} else {
			$action = '&action=edit';
		}

		$post_type_object = get_post_type_object( $post->post_type );
		if ( ! $post_type_object ) {
			return;
		}

		if ( $post_type_object->_edit_link ) {
			$link = admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );
		} else {
			$link = '';
		}

		return $link;
	}

	/**
	 * Get country name.
	 *
	 * @param $id File ID.
	 *
	 * @return array
	 */
	public function get_country( $id ) {
		$ip      = $_SERVER['REMOTE_ADDR'];
		$details = json_decode( file_get_contents( "http://api.ipstack.com/${ip}?access_key=9c6d2647b407a98be7ee75db653d0137" ) );
		if ( ! isset( $details->country_name ) ) {
			return [];
		}

		$data = $this->get_info_file_by_id( $id );
		empty( $data->country ) ? $countries = [] : $countries = unserialize( $data->country );

		if ( isset( $countries[ $details->country_name ] ) ) {
			$countries[ $details->country_name ] = $countries[ $details->country_name ] + 1;
		} else {
			$countries[ $details->country_name ] = 1;
		}

		return $countries;
	}

	public function get_browser( $id ) {
		$browser_info = $this->getBrowserFromUA();
		$browser_name_version = $browser_info['name'] . "-" . $browser_info['version'];
		$data                 = $this->get_info_file_by_id( $id );

		empty( $data->browser ) ? $browsers = [] : $browsers = unserialize( $data->browser );

		if ( isset( $browsers[ $browser_name_version ] ) ) {
			$browsers[ $browser_name_version ] = $browsers[ $browser_name_version ] + 1;
		} else {
			$browsers[ $browser_name_version ] = 1;
		}

		return $browsers;
//        $arr_browser = explode(";", $data->browser);
//        if ($data->browser != null) {
//            if (!in_array($browser_name_version, $arr_browser)) {
//                array_push($arr_browser, $browser_name_version);
//            }
//            $browser = join(";", $arr_browser);
//        } else {
//            $browser = $browser_name_version;
//        }
//        return $browser;
	}

	public function insert_country_name( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'prevent_direct_access';
		$browser    = $this->get_browser( $id );
		$country    = $this->get_country( $id );

		$wpdb->update(
			$table_name,
			array(
				'browser' => serialize( $browser ),
				'country' => serialize( $country ),
			),
			array(
				'ID' => $id
			)
		);
	}

	public function get_info_file_by_id( $id ) {
		global $wpdb;
		$table        = $wpdb->prefix . 'prevent_direct_access';
		$query        = "SELECT * FROM {$table} WHERE ID = {$id}";
		$query_result = $wpdb->get_row( $query );

		return $query_result;
	}

	/**
	 * Get browser from User Agent
	 *
	 * IE11 and Microsoft Edge changes: https://docs.microsoft.com/en-us/previous-versions/windows/internet-explorer/ie-developer/compatibility/hh869301(v=vs.85)
	 * @return array
	 */
	function getBrowserFromUA() {
		$u_agent  = $_SERVER['HTTP_USER_AGENT'];
		$bname    = 'Unknown';
		$platform = 'Unknown';
		$version  = "";
		// First get the platform?
		if ( preg_match( '/linux/i', $u_agent ) ) {
			$platform = 'linux';
		} elseif ( preg_match( '/macintosh|mac os x/i', $u_agent ) ) {
			$platform = 'mac';
		} elseif ( preg_match( '/windows|win32/i', $u_agent ) ) {
			$platform = 'windows';
		}
		// Next get the name of the useragent yes seperately and for good reason
		if ( preg_match( '/MSIE/i', $u_agent ) && ! preg_match( '/Opera/i', $u_agent ) ) {
			$bname = 'Internet Explorer';
			$ub    = "MSIE";
		} else {
			foreach ( PDA_Stats_Constants::SUPPORTED_BROWSERS as $browser ) {
				if ( preg_match( '/' . $browser['key'] . '/i', $u_agent ) ) {
					$bname = $browser['bname'];
					$ub    = $browser['ub'];
					break;
				}
			}
		}
		// finally get the correct version number
		$known   = array( 'Version', $ub, 'other' );
		$pattern = '#(?<browser>' . join( '|', $known ) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if ( ! preg_match_all( $pattern, $u_agent, $matches ) ) {
			// we have no matching number just continue
		}

		$i = count( $matches['browser'] );
		if ( $i != 1 ) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if ( strripos( $u_agent, "Version" ) < strripos( $u_agent, $ub ) ) {
				$version = $matches['version'][0];
			} else {
				$version = $matches['version'][1];
			}
		} else {
			$version = $matches['version'][0];
		}
		// check if we have a number
		if ( $version == null || $version == "" ) {
			$version = "?";
		}

		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'   => $pattern
		);
	}

	function delete_table_site_website( $blog_id, $drop ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "pda_hotlinking";
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}

	/**
	 * Hook to insert data to pda stats
	 *
	 * @param integer $user_id User ID.
	 * @param integer $link_id Link ID.
	 * @param string  $link_type Link Type.
	 * @param boolean $can_view User can view ?.
	 */
	function insert_data_for_pda_stats( $user_id, $link_id, $link_type, $can_view ) {
		$repo = new PDA_Stats_Repository();
		if ( PDA_Stats_Constants::PDA_PRIVATE_LINK === $link_type && empty( $repo->get_private_download_link_by_id( $link_id ) ) ) {
			return;
		}
		$repo->insert_data_to_db( $user_id, $link_id, $link_type, $can_view );
	}

	/**
	 * Handle ppwp_after_check_valid_password hook
	 *
	 * @param $stat_data
	 *
	 * @return array
	 */
	public function ppwp_after_check_valid_password( $stat_data ) {
		$is_valid   = $stat_data['is_valid'];
		$server_env = $stat_data['server_env'];
		$post_id    = $stat_data['post_id'];
		$post_type  = get_post_type( $post_id );
		$password   = $stat_data['password'];
		$username   = $stat_data['username'];
		$user_agent = $server_env['HTTP_USER_AGENT'];
		$ip_address = PDA_Stats_Helpers::get_instance()->get_ip( $server_env );

		if ( ! $is_valid ) {
			return $stat_data;
		}

		$meta_data = array(
			'additional_label' => isset( $_POST['username'] ) ? $_POST['username'] : '', //phpcs:ignore
		);
		$meta_data = apply_filters( 'pda_stats_single_meta_data', $meta_data, $stat_data );
		$meta_data = wp_json_encode( $meta_data );

		$data = array(
			'post_id'     => $post_id,
			'ip_address'  => $ip_address,
			'user_agent'  => $user_agent,
			'password'    => $password,
			'username'    => $username,
			'access_date' => time(),
			'post_type'   => false !== $post_type ? $post_type : PDA_Stats_Constants::PPWP_NA,
			'meta_data'   => $meta_data,
		);
		PDA_Stats_PPW_Repository::get_instance()->insert_data_to_db( $data );

		return $data;
	}

	/**
	 * Upgrade plugin actions
	 */
	public function upgrade_plugin() {
		if ( PDA_Stats_Helpers::get_instance()->is_ppwp_gold_plugin_activated() ) {
			$stats_db = new Wp_Pda_Stats_Db();
			$stats_db->create_table_for_ppwp();
		}

		if ( PDA_Stats_Helpers::get_instance()->is_pda_gold_plugin_activated() ) {
			Wp_Pda_Stats_Db::create_table();
		}
	}

	/**
	 * Handle ppwp_after_check_valid_password hook
	 *
	 * @param array $stat_data List data need to track from PPWP Pro.
	 *
	 * @return array
	 */
	public function stats_for_entire_password( $stat_data ) {
		$keys = array(
			'server_env',
			'is_valid',
			'password',
			'redirect_url',
			'username',
			'post_type',
		);
		foreach ( $keys as $key ) {
			if ( ! isset( $stat_data[ $key ] ) ) {
				return $stat_data;
			}
		}
		$is_valid = $stat_data['is_valid'];
		if ( ! $is_valid ) {
			return $stat_data;
		}

		$server_env   = $stat_data['server_env'];
		$password     = $stat_data['password'];
		$username     = $stat_data['username'];
		$post_type    = $stat_data['post_type'];
		$redirect_url = $stat_data['redirect_url'];
		$user_agent   = PDA_Stats_Helpers::get_instance()->get_user_agent( $server_env );
		$referer      = isset( $server_env['HTTP_REFERER'] ) && ! empty( $server_env['HTTP_REFERER'] ) ? $server_env['HTTP_REFERER'] : PDA_Stats_Constants::PPWP_NA;
		$ip_address   = PDA_Stats_Helpers::get_instance()->get_ip( $server_env );

		$data = array(
			'user_agent'   => $user_agent,
			'ip_address'   => $ip_address,
			'password'     => $password,
			'username'     => $username,
			'post_type'    => $post_type,
			'redirect_url' => $redirect_url,
			'post_slug'    => $referer,
			'access_date'  => time(),
		);

		PDA_Stats_PPW_Repository::get_instance()->insert_data_to_db( $data );

		return $data;
	}


	/**
	 * Handle ppwp_after_check_valid_password hook
	 *
	 * @param array $stat_data List data need to track from PPWP Pro.
	 *
	 * @return array
	 */
	public function stats_for_pcp( $stat_data ) {
		$keys = array(
			'server_env',
			'is_valid',
			'password',
			'username',
			'post_type',
			'post_id',
		);
		foreach ( $keys as $key ) {
			if ( ! isset( $stat_data[ $key ] ) ) {
				return $stat_data;
			}
		}
		$is_valid = $stat_data['is_valid'];
		if ( ! $is_valid ) {
			return $stat_data;
		}

		$server_env = $stat_data['server_env'];
		$password   = $stat_data['password'];
		$username   = $stat_data['username'];
		$post_type  = $stat_data['post_type'];
		$post_id    = $stat_data['post_id'];
		$user_agent = PDA_Stats_Helpers::get_instance()->get_user_agent( $server_env );
		$ip_address = PDA_Stats_Helpers::get_instance()->get_ip( $server_env );

		$data = array(
			'user_agent'  => $user_agent,
			'ip_address'  => $ip_address,
			'password'    => $password,
			'username'    => $username,
			'post_type'   => $post_type,
			'access_date' => time(),
			'post_id'     => $post_id,
		);

		PDA_Stats_PPW_Repository::get_instance()->insert_data_to_db( $data );

		return $data;
	}


	/**
	 * Handle ppwp_after_check_valid_password hook
	 *
	 * @param array $stat_data List data need to track from PPWP Pro.
	 *
	 * @return array
	 */
	public function stats_for_al( $stat_data ) {
		$keys = array(
			'server_env',
			'is_valid',
			'password',
			'username',
			'post_type',
			'post_id',
		);
		foreach ( $keys as $key ) {
			if ( ! isset( $stat_data[ $key ] ) ) {
				return $stat_data;
			}
		}
		$is_valid = $stat_data['is_valid'];
		if ( ! $is_valid ) {
			return $stat_data;
		}

		$server_env = $stat_data['server_env'];
		$password   = $stat_data['password'];
		$username   = $stat_data['username'];
		$post_type  = $stat_data['post_type'];
		$post_id    = $stat_data['post_id'];
		$user_agent = PDA_Stats_Helpers::get_instance()->get_user_agent( $server_env );
		$ip_address = PDA_Stats_Helpers::get_instance()->get_ip( $server_env );
		$referer    = isset( $server_env['HTTP_REFERER'] ) && ! empty( $server_env['HTTP_REFERER'] ) ? $server_env['HTTP_REFERER'] : PDA_Stats_Constants::PPWP_NA;

		$data = array(
			'user_agent'  => $user_agent,
			'ip_address'  => $ip_address,
			'password'    => $password,
			'username'    => $username,
			'post_type'   => $post_type,
			'access_date' => time(),
			'post_id'     => $post_id,
			'post_slug'   => $referer,
		);

		PDA_Stats_PPW_Repository::get_instance()->insert_data_to_db( $data );

		return $data;
	}

}


