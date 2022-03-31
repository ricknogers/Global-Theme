<?php

/**
 * Created by PhpStorm.
 * User: linhlbh
 * Date: 6/3/19
 * Time: 09:23
 */

if ( ! class_exists( 'PDA_Stats_Helpers' ) ) {

	/**
	 * Class PDA_Stats_Service
	 */
	class PDA_Stats_Helpers {
		protected static $instance;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Check list_all_password method is exist
		 * @return bool
		 */
		public function is_ppw_list_all_password_exist() {
			return class_exists( 'WP_Protect_Password_Service' )
			       && method_exists( 'WP_Protect_Password_Service', 'ppw_get_all_password' );
		}

		/**
		 * Check ppw_get_all_post_protected method is exist
		 * @return bool
		 */
		public function is_ppw_get_all_post_protected_exist() {
			return class_exists( 'WP_Protect_Password_Service' )
			       && method_exists( 'WP_Protect_Password_Service', 'ppw_get_all_protected_posts' );
		}

		/**
		 * Check ppw_get_all_post_protected method is exist
		 * @return bool
		 */
		public function is_ppw_massage_role_of_password_exist() {
			return class_exists( 'WP_Protect_Password_Service' )
			       && method_exists( 'WP_Protect_Password_Service', 'massage_role_of_password' );
		}


		/**
		 * Check is_protected_page_post_exist function is exist
		 * @return bool
		 */
		public function is_ppwp_get_post_page_parent_and_status_exist() {
			return function_exists( 'ppwp_get_post_page_parent_and_status' );
		}


		/**
		 * @param array $list_password_in_stats
		 * @param array $list_password_in_ppw
		 *
		 * @return array
		 */
		public function merge_two_password_array( $list_password_in_stats, $list_password_in_ppw ) {
			$list_password_in_ppw   = array_map( function ( $value ) {
				return (array) $value;
			}, $list_password_in_ppw );
			$list_password_in_stats = array_map( function ( $value ) {
				return (array) $value;
			}, $list_password_in_stats );

			$results = $this->merge_two_array_by_password_key( $list_password_in_stats, $list_password_in_ppw );

			return $results;
		}

		/**
		 * @param $list_password_in_stats
		 * @param $list_password_in_ppw
		 *
		 * @return array
		 */
		private function merge_two_array_by_password_key( $list_password_in_stats, $list_password_in_ppw ) {
			if ( empty( $list_password_in_stats ) ) {
				return array();
			}
			$results        = $list_password_in_stats;
			$results_column = array_column( $list_password_in_stats, 'password' );
			foreach ( $list_password_in_ppw as $value ) {
				$index_password_in_stats = $this->get_index_password_in_stats_data( $list_password_in_stats, $results_column, $value );
				if ( $index_password_in_stats !== - 1 ) {
					$results[ $index_password_in_stats ] = array_merge( $results[ $index_password_in_stats ], $value );
					continue;
				}
				array_push( $results, $value );
			}
			$results = $this->massage_list_password( $results );

			return $results;
		}

		/**
		 * @param $post_id
		 * @param $detail_post
		 * @param $is_ppwp_get_post_page_parent_and_status_exist
		 *
		 * @return array
		 */
		private function get_parent_id_and_status( $post_id, $detail_post, $is_ppwp_get_post_page_parent_and_status_exist ) {
			if ( ! $is_ppwp_get_post_page_parent_and_status_exist || ! $detail_post || empty( $detail_post->post_parent ) || strval( $detail_post->post_parent ) === $post_id ) {
				return array();
			}
			$post_parent_protected = ppwp_get_post_page_parent_and_status( $post_id );
			if ( empty( $post_parent_protected ) ) {
				return array();
			}

			return array(
				'is_protected' => $post_parent_protected['status'] ? '1' : '0',
				'parent_id'    => $post_parent_protected['parent_post_id'],
			);
		}

		/**
		 * @param array $post_using_password
		 * @param array $list_protected_post
		 *
		 * @return array
		 */
		public function handle_data_after_tracked( $post_using_password, $list_protected_post ) {
			$list_protected_post_id                        = array_column( $list_protected_post, 'post_id' ); // phpcs:ignore
			$is_ppwp_get_post_page_parent_and_status_exist = $this->is_ppwp_get_post_page_parent_and_status_exist();
			$post_using_password                           = array_map(
				function ( $element ) use ( $list_protected_post_id, $is_ppwp_get_post_page_parent_and_status_exist ) {
					$element                 = (array) $element;
					$post_id                 = $element['post_id'];
					$detail_post             = get_post( $element['post_id'] );
					$element['access_count'] = intval( $element['access_count'] );
					$element['unique_ip']    = intval( $element['unique_ip'] );
					$element['post_title']   = isset( $detail_post->post_title ) ? $detail_post->post_title : null;
					$element['link']         = get_permalink( $element['post_id'] );
					$element['post_type']    = $this->stats_get_post_type( $element['post_type'], $post_id );
					$element['post_status']  = isset( $detail_post->post_status ) ? $detail_post->post_status : null;
					$parent_detail           = $this->get_parent_id_and_status( $post_id, $detail_post, $is_ppwp_get_post_page_parent_and_status_exist );
					if ( empty( $parent_detail ) ) {
						$element['is_protected'] = in_array( $post_id, $list_protected_post_id ) ? '1' : '0';

						return $element;
					}
					$element['is_protected'] = $parent_detail['is_protected'] ? '1' : '0';
					$element['parent_id']    = $parent_detail['parent_id'];

					return $element;
				},
				$post_using_password
			);

			return $post_using_password;
		}

		/**
		 * Get post type follow old post type and post ID.
		 *
		 * @param string     $post_type The post type.
		 * @param string|int $post_id   The post ID.
		 *
		 * @return string
		 */
		public function stats_get_post_type( $post_type, $post_id ) {
			if ( PDA_Stats_Constants::PPWP_NA === $post_type ) {
				return $post_type;
			}
			if ( ! is_null( $post_type ) ) {
				$post_type_obj = get_post_type_object( $post_type );

				return isset( $post_type_obj->labels->name ) ? $post_type_obj->labels->name : PDA_Stats_Constants::PPWP_NA;
			}
			$post_type = get_post_type( $post_id );
			if ( false === $post_type ) {
				return PDA_Stats_Constants::PPWP_NA;
			}
			$post_type_obj = get_post_type_object( $post_type );

			return isset( $post_type_obj->labels->name ) ? $post_type_obj->labels->name : PDA_Stats_Constants::PPWP_NA;
		}

		/**
		 * Check plugin is activated & Enter license in PPWP 1.1.x
		 *
		 * @codeCoverageIgnore
		 * @return bool
		 */
		public function is_wpp_gold_activated() {
			return function_exists( 'wpp_is_gold_function' ) && wpp_is_gold_function();
		}

		/**
		 * Only check PPWP Gold plugin is activated in 2 version 1.0.x & 1.1.x
		 *
		 * @codeCoverageIgnore
		 * @return bool
		 */
		public function is_ppwp_gold_plugin_activated() {
			return class_exists( 'Wp_Protect_Password' ) || class_exists( 'Password_Protect_Page_Pro' );
		}

		/**
		 * Only check PDA Gold plugin is activated in version smaller or equal 2.x & version 3.x
		 *
		 * @codeCoverageIgnore
		 * @return bool
		 */
		public function is_pda_gold_plugin_activated() {
			return class_exists( 'Prevent_Direct_Access_Gold' ) || class_exists( 'Pda_Gold_Admin' );
		}

		/**
		 * Check is PDA Gold and PPWP Pro deactivate.
		 *
		 * @return bool
		 * @codeCoverageIgnore
		 * @since 1.0 init function.
		 * @since 2.0 Change check deactivate plugins
		 */
		public function is_deactive_pda_or_ppwp() {
			return ! $this->is_pda_gold_plugin_activated() && ! $this->is_ppwp_gold_plugin_activated();
		}

		/**
		 * Get license key of PPWP
		 *
		 * @return bool
		 */
		public function get_ppwp_license_key() {
			$license_key = get_option( 'wp_protect_password_license_key' );

			return $license_key ? $license_key : '';
		}

		/**
		 * Check array have isValid
		 *
		 * @param array $addons array.
		 *
		 * @return bool
		 */
		public function is_addons_valid( $addons ) {
			foreach ( $addons as $addon ) {
				if ( isset( $addon['isValid'] ) && $addon['isValid'] ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * @param $server array
		 *
		 * @return string
		 */
		public function get_ip( $server ) {
			if ( isset( $server['HTTP_CF_CONNECTING_IP'] ) ) {
				return $server['HTTP_CF_CONNECTING_IP'];
			}

			return $server['REMOTE_ADDR'];
		}

		/**
		 * Get index password in stats data
		 *
		 * @param array $list_password_in_stats
		 * @param array $results_column
		 * @param array $value
		 *
		 * @return int
		 */
		private function get_index_password_in_stats_data( $list_password_in_stats, $results_column, $value ) {
			for ( $column_index = 0; $column_index < sizeof( $results_column ); $column_index ++ ) {
				if ( $value['password'] !== $results_column[ $column_index ] ) {
					continue;
				}
				$stats_value = $list_password_in_stats[ $column_index ];
				if ( $stats_value['post_id'] === $value['post_id'] ) {
					return $column_index;
				}
			}

			return - 1;
		}

		/**
		 * @param $results
		 *
		 * @return array
		 */
		private function massage_list_password( $results ) {
			$ppwp_service = null;
			if ( $this->is_ppw_massage_role_of_password_exist() ) {
				$ppwp_service = new WP_Protect_Password_Service();
			}
			$results = array_map( function ( $value ) use ( $ppwp_service ) {
				$value['access_count'] = ! isset( $value['access_count'] ) ? 0 : intval( $value['access_count'] );
				$value['unique_ip']    = ! isset( $value['unique_ip'] ) ? 0 : intval( $value['unique_ip'] );
				if ( isset ( $value['campaign_app_type'] ) && ! is_null( $ppwp_service ) ) {
					$value['campaign_app_type'] = $ppwp_service->massage_role_of_password( $value['campaign_app_type'] );
				}

				return $value;
			}, $results );

			return $results;
		}

		/**
		 * Retrieves the URL for the current site where the front end is accessible.
		 *
		 * @return string Home url with ssl
		 */
		public function get_home_url_with_ssl() {
			return is_ssl() ? home_url( '/', 'https' ) : home_url( '/' );
		}

		/**
		 * Handle data before send to client
		 * Check status password(Active/Deleted)
		 *
		 * @param array $list_passwords_tracked List password need to track.
		 * @param array $list_using_password    List password active.
		 *
		 * @return array
		 */
		public function entire_site_handle_data_before_send_to_client( $list_passwords_tracked, $list_using_password ) {
			$passwords = array_map(
				function ( $element ) {
					return (string) $element;
				},
				array_keys( $list_using_password )
			);

			return array_map(
				function ( $data ) use ( $passwords, $list_using_password ) {
					$data          = (array) $data;
					$password_text = $data['password'];

					// Get status of sitewide password if User has been migrated sitewide password on PPWP Suite.
					if ( isset( $list_using_password[ $password_text ]['status'] ) ) {
						$data['status'] = $list_using_password[ $password_text ]['status'];
						$data['label']  = isset( $list_using_password[ $password_text ]['label'] ) ? $list_using_password[ $password_text ]['label'] : '';
					} else {
						$data['status'] = in_array( $password_text, $passwords, true ) ? 'Active' : 'Deleted';
					}

					return $data;
				},
				$list_passwords_tracked
			);
		}

		/**
		 * Handle data before send to client
		 * Check status password(Active/Deleted)
		 *
		 * @param array $list_passwords_tracked List password need to track.
		 * @param array $list_using_password    List password active.
		 *
		 * @return array
		 */
		public function handle_password_data_before_send_to_client( $list_passwords_tracked, $list_using_password, $is_al_password = false ) {
			$passwords_info = array_map(
				function ( $data ) use ( $list_using_password, $is_al_password ) {
					$data = (array) $data;

					$index               = array_search( $data['password'], array_column( $list_using_password, 'password' ), true );
					$data['status']      = 'Deleted';
					$data['password_id'] = 0;

					if ( false === $index ) {
						return $data;
					}

					// Default status for password.
					$data['status']      = 'Active';
					$password_obj        = $list_using_password[ $index ];
					$data['password_id'] = (int) $password_obj->id;

					/**
					 * Check password expired
					 */
					$is_expired_by_date  = null !== $password_obj->expired_date && (int) $password_obj->expired_date <= time();
					$is_expired_by_count = null !== $password_obj->hits_count && null !== $password_obj->usage_limit && (int) $password_obj->usage_limit <= (int) $password_obj->hits_count;

					/**
					 * Set status for password.
					 */
					$data['status'] = $is_expired_by_date || $is_expired_by_count ? 'Expired' : $data['status'];
					$data['status'] = '1' === $password_obj->is_activated ? $data['status'] : 'Inactive';
					$data['label']  = isset( $password_obj->label ) ? $password_obj->label : '';

					$data['label'] = isset( $password_obj->label ) ? $password_obj->label : '';

					/**
					 * Support property for access level.
					 */
					if ( $is_al_password ) {
						$data['status'] = isset( $password_obj->is_active_base ) && 1 === (int) $password_obj->is_active_base ? $data['status'] : 'Inactive';
					}
					$data['type']      = isset( $password_obj->type ) ? $password_obj->type : '';
					$data['base_id']   = isset( $password_obj->base_id ) ? (int) $password_obj->base_id : null;
					$data['base_name'] = isset( $password_obj->base_name ) ? $password_obj->base_name : null;

					return $data;
				},
				$list_passwords_tracked
			);

			usort(
				$passwords_info,
				function ( $a, $b ) {
					return $b['password_id'] - $a['password_id'];
				}
			);

			return $passwords_info;
		}

		/**
		 * Check condition to hide "Check for updates" button.
		 *
		 * @return bool
		 * Return false: If all main plugins is deactivate
		 * Else: return true
		 */
		public function main_plugins_is_deactivated() {
			$main_plugins = array( 'pda_gold', 'pda_v3' );
			foreach ( $main_plugins as $main_plugin ) {
				if ( - 1 === Yme_Plugin_Utils::is_plugin_activated( $main_plugin ) ) {
					return false;
				}
			}

			return ! $this->ppwp_is_activated();
		}

		/**
		 * Check PPWP Pro is deactivated
		 *
		 * @return mixed
		 */
		public function ppwp_is_activated() {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			return is_plugin_active( 'wp_protect_password/wp-protect-password.php' );
		}

		/**
		 * Check valid version for PPWP Pro.
		 *
		 * @param string $version The version need to compare.
		 *
		 * @return bool
		 */
		public function ppwp_check_version_greater_than( $version ) {
			return defined( 'PPW_PRO_VERSION' ) && version_compare( PPW_PRO_VERSION, $version, '>=' );
		}

		/**
		 * Get server info from client.
		 *
		 * @param array $server_env Server environment.
		 *
		 * @return array|string
		 */
		public function get_user_agent( $server_env ) {
			return isset( $server_env['HTTP_USER_AGENT'] ) && ! empty( $server_env['HTTP_USER_AGENT'] ) ? $server_env['HTTP_USER_AGENT'] : PDA_Stats_Constants::PPWP_NA;
		}

		/**
		 * Should skip password.
		 *
		 * @return bool
		 */
		public function should_skip_password() {
			return defined( 'PPWP_STATS_SKIP_PWD_STATUS' ) && true === PPWP_STATS_SKIP_PWD_STATUS;
		}

		/**
		 * Check PPWP Access Level activate.
		 *
		 * @return bool
		 */
		public function check_ppwp_al_activate() {
			return defined( 'PPWP_AL_VERSION' );
		}

		/**
		 * Check user have migrated sitewide passwords.
		 * FIXME: Need to get form PPWP Suite
		 * @return bool
		 */
		public function check_ppwp_ps_sitewide_activate() {
			if ( ! defined( 'PPWP_PS_VERSION' ) ) {
				return false;
			}
			$migrated_data = get_option( 'ppw_entire_site_passwords_migrate' );

			return is_array( $migrated_data ) && is_array( $migrated_data['passwords'] ) && count( $migrated_data['passwords'] ) === 0;
		}

		/**
		 * Get migrated sitewide passwords from PPWP PS.
		 */
		public function get_migrated_sitewide_passwords() {
			// Check user has been migrated sitewide password and activate PPWP Suite.
			if ( ! $this->check_ppwp_ps_sitewide_activate() ) {
				return false;
			}

			// Require class and method is exist before handle.
			if ( ! class_exists( 'PPW_Pro_Repository' ) || ! method_exists( 'PPWP_PS_Repo_Passwords', 'fetch_sitewide_passwords' ) ) {
				return false;
			}

			$ppwp_ps_repo_passwords = new PPWP_PS_Repo_Passwords( new PPW_Pro_Repository() );
			$sitewide_passwords     = $ppwp_ps_repo_passwords->fetch_sitewide_passwords( new PPW_Pro_Repository() );

			if ( empty( $sitewide_passwords ) ) {
				return array();
			}

			// Check status of sitewide passwords.
			$passwords = array();
			foreach ( $sitewide_passwords as $sitewide_password ) {
				$passwords[ $sitewide_password->password ] = array(
					'status' => $this->get_status_of_password( $sitewide_password ),
					'label'  => isset( $sitewide_password->label ) ? $sitewide_password->label : '',
				);
			}

			return apply_filters( 'pda_stats_migrated_sitewide_passwords', $passwords, $sitewide_passwords );
		}

		/**
		 * Get status of password.
		 *
		 * @param object $password_obj Password object information.
		 *
		 * FIXME: Should put this function in PPWP Pro.
		 *
		 * @return string Status.
		 */
		public function get_status_of_password( $password_obj ) {
			// Default status for password.
			$status = 'Active';

			/**
			 * Check password expired
			 */
			$is_expired_by_date  = null !== $password_obj->expired_date && (int) $password_obj->expired_date <= time();
			$is_expired_by_count = null !== $password_obj->hits_count && null !== $password_obj->usage_limit && (int) $password_obj->usage_limit <= (int) $password_obj->hits_count;

			/**
			 * Set status for password.
			 */
			$status = $is_expired_by_date || $is_expired_by_count ? 'Expired' : $status;
			$status = '1' === $password_obj->is_activated ? $status : 'Inactive';

			return $status;
		}

		/**
		 * Get all child page which user turn on "Password Protect Child Pages" on setting.
		 *
		 * @param integer $post_id Post ID
		 *
		 * @return array Empty if page has not child and option is off.
		 */
		public function get_all_child_page( $post_id ) {
			if ( ! method_exists( 'PPW_Pro_Password_Services', 'get_all_id_child_page' )
			     || ! function_exists( 'ppw_core_get_setting_type_bool' )
			     || ! defined( 'PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES' )
			) {
				return array();
			}

			$protect_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
			// List all id child page follow feature "Password Protect Child Pages".
			$list_child_page = array();
			if ( $protect_child_pages ) {
				$repository      = new PPW_Pro_Password_Services();
				$list_child_page = $repository->get_all_id_child_page( $post_id );
			}

			return $list_child_page;
		}

		/**
		 * Generate condition to check post ID.
		 *
		 * @param integer $post_id Post ID.
		 *
		 * @return string
		 */
		public function generate_condition_to_check_post_id( $post_id ) {
			$post_id  = (int) $post_id;
			$pages_id = PDA_Stats_Helpers::get_instance()->get_all_child_page( $post_id );
			if ( is_array( $pages_id ) && count( $pages_id ) > 0 ) {
				$pages_id []  = $post_id;
				$pages_id_str = implode( ',', $pages_id );

				return "post_id IN ({$pages_id_str})";
			}

			return 'post_id = ' . $post_id;
		}
	}
}
