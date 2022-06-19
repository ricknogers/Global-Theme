<?php

class Pda_JS_Loader {

    private $version;
    private $plugin_name;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    private function get_home_url_with_ssl() {
        return is_ssl() ? home_url( '/', 'https' ) : home_url( '/' );
    }

    public function admin_load_js() {
        wp_register_script( 'ajaxHandle', plugins_url( '../js/custom-file.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'ajaxHandle' );
		wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'pda_sub_nonce' =>  wp_create_nonce('pda_subscribe') ) );
		if ( function_exists( 'get_current_screen' ) ) {
			$screen          = get_current_screen();
			$screen_map      = $this->get_screen_map_id();

			if ( $screen_map['media'] === $screen->id && (! get_option( 'pda_is_licensed' ) && !is_multisite()) ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../js/dist/style/pda_gold_v3_bundle.css', array(), $this->version, 'all' );
				Pda_JS_Loader::pda_add_style();

				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../js/dist/pda_gold_v3_bundle.js', array( 'jquery' ), $this->version, false );
				wp_localize_script( $this->plugin_name, 'pda_gold_v3_data', array(
					'home_url'                             => $this->get_home_url_with_ssl(),
					'nonce'                                => wp_create_nonce( 'wp_rest' ),
					'stats_activated'                      => false,
					'magic_link_activated'                 => false,
					'pda_s3_activated'                     => false,
					'pda_membership_integration_activated' => false,
					'user_access_manager'                  => false,
					'memberships_2'                        => false,
					'paid_memberships_pro'                 => false,
					'woo_memberships'                      => false,
					'woo_subscriptions'                    => false,
					'pda_v3_plugin_url'                    => PDA_LITE_BASE_URL,
					'rest_api_prefix'                      => 'wp-json',
					'is_license_expired'                   => false,
					'api_url'                              => get_rest_url(),
				) );
				$userRoles = [];
				wp_localize_script( $this->plugin_name, 'ip_block_server_data', array(
					'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
					'ip_block_activated'       => false,
					'pda_magic_link_activated' => false,
					'roles'                    => $userRoles,
				) );
			}

			if ( $screen_map['pda_settings'] === $screen->id ) {
				wp_enqueue_script( $this->plugin_name . 'pda_search_setting', plugin_dir_url( __FILE__ ) . '../js/pda-setting-search.js', array( 'jquery' ), $this->version, false );
				wp_localize_script( $this->plugin_name . 'pda_search_setting', 'server_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
				wp_enqueue_style( $this->plugin_name . 'pda_toastr_css', PDA_LITE_BASE_URL . 'css/toastr.min.css', array(), $this->version, 'all' );
				wp_enqueue_script( $this->plugin_name . 'pda_toastr', PDA_LITE_BASE_URL . 'js/lib/toastr.min.js', array( 'jquery' ), $this->version, false );
			}
		}
	}

    public function pda_add_style() {
        wp_register_style( 'pda_style_css', plugin_dir_url( __FILE__ ) . ('../css/pda.css'),  array(), $this->version, 'all');
        wp_enqueue_style( 'pda_style_css' );
    }

	public function get_screen_map_id() {
		$screens = array(
			'media'        => 'upload',
			'pda_settings' => 'toplevel_page_wp_pda_options',
			'status'       => 'prevent-direct-access-gold_page_pda-status',
			'attachment'   => 'attachment',
			'affiliate'    => 'prevent-direct-access-gold_page_pda-affiliate',
			'upload'       => 'upload',
			'plugins'      => 'plugins',
			'page'         => 'page',
			'post'         => 'post',
		);
		return $screens;
	}
}
?>
