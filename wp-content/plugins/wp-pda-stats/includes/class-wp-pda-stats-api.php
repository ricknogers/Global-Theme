<?php
if ( ! class_exists( "PDA_Stats_API" ) ) {
	/**
	 * Class PDA_Stats_API
	 */
	class PDA_Stats_API {
		/**
		 * @var PDA_Stats_Service
		 */
		private $service;

		/**
		 * @var PDA_Stats_Repository
		 */
		private $pda_services;

		/**
		 * PDA_Stats_API constructor.
		 *
		 * @param null|PDA_Stats_Service $service
		 * @param null|WP_Stats_PDA_Services $pda_services
		 */
		public function __construct( $service = null, $pda_services = null ) {
			$this->service      = is_null( $service ) ? new PDA_Stats_Service() : $service;
			$this->pda_services = is_null( $pda_services ) ? new WP_Stats_PDA_Services() : $pda_services;
		}

		/**
		 * Register rest routes
		 */
		public function register_rest_routes() {
			register_rest_route( 'pda-stats', 'top-ten-private-links', array(
				'methods'  => 'POST',
				'callback' => array( $this, 'top_ten_result' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'file-most-private-links-clicks', array(
				'methods'  => 'POST',
				'callback' => array( $this, 'file_most_private_clicks' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'file-and-domain', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_file_and_domain' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'top-ten-files', array(
				'methods'  => 'POST',
				'callback' => array( $this, 'file_most_private_links' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'private-summary/(?P<post_id>[0-9-]+)', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'private_summary' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'going-to-expired-links', array(
				'methods'  => 'POST',
				'callback' => array( $this, 'going_to_expired_links' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'top-ten-country', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'top_ten_country' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'top-ten-browser', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'top_ten_browser' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'all-data-in-pda-stats', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'all_data_in_pda_stats' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats', 'stats-for-share-private-link', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_data_for_share_private_link' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'stats-for-post', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_post_using_ppw' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'all', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_ppw' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'stats-for-post/list', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'get_data_for_post_using_ppw' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'stats-for-post/remove-expired-date', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'update_data_for_pwd_expired_date' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'stats-for-entire-site', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_password_entire_site' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'stats-for-pcp', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_password_pcp' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );

			register_rest_route( 'pda-stats/ppw', 'stats-for-al', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_password_al' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			) );
		}

		/**
		 * Top 10 Download Links with Most Clicks
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function top_ten_result( $data ) {
			return $this->pda_services->get_private_links_which_have_download_clicks( $data );
		}

		/**
		 * API to get Top 10 Files with Most Click
		 *
		 * @param array $data Body data from request
		 *
		 * @return array
		 */
		public function file_most_private_clicks( $data ) {
			return $this->pda_services->get_files_which_have_download_clicks( $data );
		}

		public function get_file_and_domain() {
			global $wpdb;
			$pda_hotlinkling_table = $wpdb->prefix . 'pda_hotlinking';
			$posts_table           = $wpdb->prefix . 'posts';
			$query                 = "SELECT {$posts_table}.ID as id, {$posts_table}.guid as filename, {$pda_hotlinkling_table}.domain FROM {$pda_hotlinkling_table} JOIN {$posts_table} ON {$pda_hotlinkling_table}.post_id = {$posts_table}.ID";
			$results               = $wpdb->get_results( $query );
			$results               = array_map( array( $this, 'cut_file_name_by_url' ), $results );

			return $results;
		}

		/**
		 * API to get Top 10 Files with Most Download Links
		 *
		 * @param array $data Body data from request.
		 *
		 * @return array
		 */
		public function file_most_private_links( $data ) {
			return $this->pda_services->get_top_files_with_most_download_links( $data );
		}


		/**
		 * Stats private download link from pop-up
		 *
		 * @param array $data
		 *
		 * @return array Data from database
		 */
		public function private_summary( $data ) {
			return $this->pda_services->stat_for_private_download_link( $data );
		}

		/**
		 * Top going-to-expire Download Links
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function going_to_expired_links( $data ) {
			return $this->pda_services->get_expire_download_links_nearly( $data );
		}

		public function top_ten_country() {
			global $wpdb;
			$prevent_table = $wpdb->prefix . 'prevent_direct_access';
			$query         = "SELECT country FROM {$prevent_table} WHERE country != '' AND type = '' AND is_default = 0";
			$query_result  = $wpdb->get_results( $query );

			$query_result = array_map( function ( $data ) {
				return unserialize( $data->country );
			}, $query_result );

			$summary = [];
			foreach ( $query_result as $key => $value ) {
				$countries = array_keys( $value );
				foreach ( $countries as $c ) {
					if ( ! array_key_exists( $c, $summary ) ) {
						$summary[ $c ] = $value[ $c ];
					} else {
						$summary[ $c ] += $value[ $c ];
					}
				}
			}
			arsort( $summary );

			return $summary;
		}

		public function all_data_in_pda_stats() {
			$repo     = new PDA_Stats_Repository();
			$all_data = $repo->convert_data_for_api();

			return $all_data;
		}

		public function get_data_for_share_private_link() {
			if ( Yme_Plugin_Utils::is_plugin_activated( 'magic_link' ) === - 1 ) {
				global $wpdb;
				$pda_v3_table     = $wpdb->prefix . 'prevent_direct_access';
				$magic_link_table = $wpdb->prefix . 'prevent_direct_access_downloads';

				$query_string = "SELECT $pda_v3_table.post_id, $pda_v3_table.time, $pda_v3_table.url, $pda_v3_table.limit_downloads, $pda_v3_table.expired_date, $pda_v3_table.roles, $magic_link_table.user_id, $magic_link_table.downloads, $magic_link_table.expired_date as created_date FROM $pda_v3_table INNER JOIN $magic_link_table ON $pda_v3_table.ID = $magic_link_table.private_link_id";
				$full_data    = $wpdb->get_results( $query_string );
				$results = array_reduce( $full_data, function ( $pre, $data ) {
					if ( is_null( get_post( $data->post_id ) ) ) {
						return array();
					}
					$helper    = new Pda_v3_Gold_Helper();
					$full_link = $helper->get_private_url( $data->url );
					$post      = get_post( $data->post_id );
					$edit_link = $this->get_edit_post_link_for_api( $data->post_id, '' );

					$user_info  = get_userdata( $data->user_id );
					$user_name  = $user_info->user_login;
					$user_roles = implode( ";", $user_info->roles );

					$repo      = new PDA_Stats_Repository();
					$link_user = $repo->get_link_profile_user( $data->user_id );
					if ( $data->expired_date === null ) {
						$expired_date = null;
					} else {
						$expire_day   = date( 'Y-m-d', $data->expired_date );
						$create_day   = date_format( date_create( $data->time ), 'Y-m-d' );
						$date1        = new DateTime( $expire_day );
						$date2        = new DateTime( $create_day );
						$result       = $date1->diff( $date2 );
						$days         = $result->days;
						$expired_date = strtotime( '+' . $days . ' days', $data->created_date );
					}

					$massage_data = array(
						'roles_access'    => str_replace( ";", ", ", $data->roles ),
						'user'            => array(
							'user_name'  => $user_name,
							'link_user'  => $link_user,
							'user_roles' => $user_roles,
						),
						'url'             => $data->url,
						'full_url'        => $full_link,
						'file_name'       => array(
							'name' => $post->post_title,
							'link' => $edit_link,
						),
						'created_time'    => strtotime( $data->time ),
						'expired_date'    => $expired_date,
						'downloads_limit' => $data->limit_downloads,
						'downloads'       => (int) $data->downloads,
						'first_download'  => $data->created_date,
					);
					return array_merge( $pre, array( $massage_data ) );
				}, array() );

				return $results;
			}
		}


		/**
		 * Get all Password.
		 *
		 * @param array $data Request data user.
		 *
		 * @return array
		 */
		public function get_all_ppw( $data ) {
			return PDA_Stats_Service::get_instance()->get_all_ppw();
		}

		public function get_all_post_using_ppw() {
			$post_using_password = PDA_Stats_Service::get_instance()->get_all_post_using_password_tracked();
			$list_password       = PDA_Stats_Service::get_instance()->get_all_post_with_password();

			return array(
				'post_using_password' => array_reverse( $post_using_password ),
				'list_password'       => $list_password
			);
		}

		/**
		 * API get all entire site passwords for Stats
		 *
		 * @return array
		 */
		public function get_all_password_entire_site() {
			$passwords_tracked = PDA_Stats_Service::get_instance()->list_entire_site_passwords_tracked();
			$list_password     = PDA_Stats_Service::get_instance()->list_entire_site_passwords_data();

			return array(
				'entire_site_passwords_tracked' => $passwords_tracked,
				'list_entire_site_passwords'    => $list_password,
			);
		}

		/**
		 * API get all entire site passwords for Stats
		 *
		 * @return array
		 */
		public function get_all_password_pcp() {
			$passwords_tracked = PDA_Stats_Service::get_instance()->list_pcp_passwords_tracked();
			$list_password     = PDA_Stats_Service::get_instance()->list_pcp_passwords_data();

			return array(
				'pcp_passwords_tracked' => $passwords_tracked,
				'list_pcp_passwords'    => $list_password,
			);
		}

		/**
		 * API get all access level passwords for Stats
		 *
		 * @return array
		 * @since 1.2.1
		 */
		public function get_all_password_al() {
			$passwords_tracked = PDA_Stats_Service::get_instance()->list_al_passwords_tracked();
			$list_password     = PDA_Stats_Service::get_instance()->list_al_passwords_data();

			return array(
				'al_passwords_tracked' => $passwords_tracked,
				'list_al_passwords'    => $list_password,
			);
		}


		function get_data_for_post_using_ppw( $data ) {
			if ( ! isset( $data['password'] ) || ! isset( $data['post_id'] ) ) {
				return wp_send_json_error( 'Please check value' );
			}
			$post_using_password = PDA_Stats_PPW_Repository::get_instance()->get_individual_post_password( $data['password'], $data['post_id'] );

			return $post_using_password;
		}

		/**
		 * @param array $data Request data user.
		 *
		 * @return mixed
		 */
		function update_data_for_pwd_expired_date( $data ) {
			if ( empty( $data['expired_date'] ) ) {
				return wp_send_json_error( 'No data to remove' );
			}

			if ( ! isset( $data['password'] ) || ! isset( $data['post_id'] ) || ! isset( $data['access_date'] ) ) {
				return wp_send_json_error( 'Invalid value' );
			}

			$decoded_meta_data = json_decode( $data['meta_data'], true );
			unset( $decoded_meta_data['expired_date'] );
			$encoded_meta_data = json_encode( $decoded_meta_data );

			$update_expired_date = PDA_Stats_PPW_Repository::get_instance()->update_expired_date_individual_pwd( $data['password'], $data['post_id'], $data['access_date'], $encoded_meta_data );

			return $update_expired_date;
		}

		public function top_ten_browser() {
			global $wpdb;
			$prevent_table = $wpdb->prefix . 'prevent_direct_access';
			$query         = "SELECT browser FROM {$prevent_table} WHERE browser != '' AND type = '' AND is_default = 0";
			$query_result  = $wpdb->get_results( $query );

			$query_result = array_map( function ( $data ) {
				$try = unserialize( $data->browser );
				if ( ! $try ) {
					return null;
				} else {
					return unserialize( $data->browser );
				}
			}, $query_result );

			$query_result = array_filter( $query_result, function ( $d ) {
				return $d != null;
			} );

			$summary = [];
			foreach ( $query_result as $key => $value ) {
				$countries = array_keys( $value );
				foreach ( $countries as $c ) {
					if ( ! array_key_exists( $c, $summary ) ) {
						$summary[ $c ] = $value[ $c ];
					} else {
						$summary[ $c ] += $value[ $c ];
					}
				}
			}
			arsort( $summary );

			return $summary;
		}

		/**
		 * @param $v
		 *
		 * @return mixed
		 * @deprecated
		 */
		public function map_value_top_ten( $v ) {
			$v->edit_link = $this->get_edit_post_link_for_api( $v->id, '' );
			$filename     = explode( '/', $v->filename );
			$v->filename  = end( $filename );
			if ( property_exists( $v, 'url' ) ) {
				$v->full_url = $this->get_full_private_url( $v->url );
			}

			return $v;
		}

		public function cut_file_name_by_url( $result ) {
			$result->edit_link = $this->get_edit_post_link_for_api( $result->id, '' );
			$filename          = explode( '/', $result->filename );
			$result->filename  = end( $filename );

			return $result;
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
		 * @param $url
		 *
		 * @return string
		 * @deprecated
		 */
		private function get_full_private_url( $url ) {
			$func       = new Pda_Gold_Functions();
			$prefix_url = $func->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );
			if ( isset( $prefix_url ) && ! empty( $prefix_url ) ) {
				if ( ! is_multisite() ) {
					$url = site_url() . "/{$prefix_url}/" . $url;
				} else {
					$url = site_url() . "/{$prefix_url}/site/" . get_current_blog_id() . '/' . $url;
				}
			}

			return $url;
		}

		/**
		 * @param $link
		 *
		 * @return mixed
		 * @deprecated
		 */
		public function map_full_url( $link ) {
			if ( property_exists( $link, 'url' ) ) {
				$link->full_url = $this->get_full_private_url( $link->url );
			}

			return $link;
		}


	}
}
