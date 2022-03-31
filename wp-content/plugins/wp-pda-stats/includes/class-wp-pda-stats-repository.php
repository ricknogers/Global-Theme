<?php

if ( ! class_exists( 'PDA_Stats_Repository' ) ) {
	class PDA_Stats_Repository {

		/**
		 * @var object global
		 */
		private $wpdb;

		/**
		 * @var string
		 */
		private $table_name;

		/**
		 * @var string
		 */
		private $prevent_table;

		/**
		 * @var string
		 */
		private $posts_table;

		/**
		 * PDA_Stats_Repository constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wpdb          = &$wpdb;
			$this->table_name    = $wpdb->prefix . 'prevent_direct_access_statistics';
			$this->prevent_table = $wpdb->prefix . 'prevent_direct_access';
			$this->posts_table   = $wpdb->prefix . 'posts';
		}

		function insert_data_to_db( $user_id, $link_id, $link_type, $can_view ) {
			$has_data_db = $this->check_data_isset_db( $user_id, $link_id, $link_type, $can_view );
			if ( empty( $has_data_db ) ) {
				if ( $link_type === PDA_v3_Constants::PDA_ORIGINAL_LINK ) {
					$post = get_post( $link_id );
					if ( ! isset( $post ) ) {
						return;
					}
				}
				$this->wpdb->insert(
					$this->table_name,
					array(
						'link_id'   => $link_id,
						'user_id'   => $user_id,
						'link_type' => $link_type,
						'count'     => 1,
						'can_view'  => $can_view
					)
				);
			} else {
				$this->wpdb->update(
					$this->table_name,
					array(
						'count' => $has_data_db->count + 1,
					),
					array(
						'ID' => $has_data_db->ID,
					)
				);
			}
		}

		function check_data_isset_db( $user_id, $link_id, $link_type, $can_view ) {
			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE link_id = %s AND user_id = %s AND link_type = %s AND can_view = %s", $link_id, $user_id, $link_type, $can_view );
			$result       = $this->wpdb->get_row( $query_string );

			return $result;
		}

		function get_all_data_in_db() {
			$query_string = "SELECT * FROM $this->table_name";
			$results      = $this->wpdb->get_results( $query_string );

			return $results;
		}

		function convert_data_for_api() {
			$tracking_data     = $this->get_all_data_in_db();
			$only_existed_post = array_values(
				array_filter(
					$tracking_data,
					function ( $data ) {
						if ( PDA_v3_Constants::PDA_ORIGINAL_LINK === $data->link_type ) {
							$post = get_post( $data->link_id );
							if ( ! is_null( $post ) ) {
								return $data;
							}
						} elseif ( PDA_v3_Constants::PDA_PRIVATE_LINK === $data->link_type ) {
							$private_link = $this->get_private_link_by_id( $data->link_id );
							if ( ! is_null( $private_link ) ) {
								$post = get_post( $private_link->post_id );
								if ( ! is_null( $post ) ) {
									return $data;
								}
							}
						}
					}
				)
			);

			return array_map(
				function ( $data ) {
					$admin = new Wp_Pda_Stats_Admin( '', '' );
					if ( PDA_v3_Constants::PDA_PRIVATE_LINK === $data->link_type ) {
						list( $edit_link, $file_name, $full_link, $user_name, $user_info ) = $this->massage_private_link( $data, $admin );
					} else {
						list( $edit_link, $full_link, $file_name, $user_name, $user_info ) = $this->massage_original_link( $data, $admin );
					}

					return array(
						'full_link' => $full_link,
						'user_name' => $user_name,
						'user_info' => array(
							'name' => $user_name,
							'url'  => $user_info,
						),
						'clicks'    => (int) $data->count,
						'link_type' => PDA_v3_Constants::PDA_PRIVATE_LINK === $data->link_type ? PDA_Stats_Constants::LINK_TYPE['PRIVATE'] : PDA_Stats_Constants::LINK_TYPE['ORIGINAL'],
						'file_info' => array(
							'name' => empty( $file_name ) ? PDA_Stats_Constants::PPWP_NA : $file_name->post_title,
							'link' => is_null( $edit_link ) ? PDA_Stats_Constants::PPWP_NA : $edit_link,
						),
						'file_name' => empty( $file_name ) ? PDA_Stats_Constants::PPWP_NA : $file_name->post_title,
						'can_view'  => 1 == $data->can_view ? 'Yes' : 'No',
					);
				},
				$only_existed_post
			);
		}

		function get_private_link_by_id( $id ) {
			$pda_table    = $this->wpdb->prefix . 'prevent_direct_access';
			$query_string = $this->wpdb->prepare( " SELECT * FROM $pda_table WHERE ID = %s ", $id );
			$results      = $this->wpdb->get_row( $query_string );

			return $results;
		}

		function get_link_profile_user( $user_id ) {
			$link_profile = add_query_arg( 'user_id', $user_id, self_admin_url( 'user-edit.php' ) );

			return $link_profile;
		}

		/**
		 * Massage private link before return to client.
		 *
		 * @param mixed $data Tracking data.
		 * @param mixed $admin Admin helper functions.
		 *
		 * @return array
		 */
		private function massage_private_link( $data, $admin ) {
			$private_link = $this->get_private_link_by_id( $data->link_id );
			$edit_link    = $admin->get_edit_post_link_for_api( $private_link->post_id, '' );
			$file_name    = get_post( $private_link->post_id );
			$helper       = new Pda_v3_Gold_Helper();
			$full_link    = $helper->get_private_url( $private_link->url );
			if ( ! empty( get_userdata( $data->user_id ) ) ) {
				$user_name = get_userdata( $data->user_id )->user_login;
				$user_info = $this->get_link_profile_user( $data->user_id );
			} else {
				$user_name = PDA_Stats_Constants::PDA_ANONYMOUS;
				$user_info = '';
			}

			return array( $edit_link, $file_name, $full_link, $user_name, $user_info );
		}

		/**
		 * Massage original link before return to client.
		 *
		 * @param mixed $data Tracking data.
		 * @param mixed $admin Admin helper functions.
		 *
		 * @return array
		 */
		private function massage_original_link( $data, $admin ) {
			$edit_link = $admin->get_edit_post_link_for_api( $data->link_id, '' );
			$full_link = wp_get_attachment_url( $data->link_id );
			$file_name = get_post( $data->link_id );
			if ( ! empty( get_userdata( $data->user_id ) ) ) {
				$user_name = get_userdata( $data->user_id )->user_login;
				$user_info = $this->get_link_profile_user( $data->user_id );
			} else {
				$user_name = PDA_Stats_Constants::PDA_ANONYMOUS;
				$user_info = '';
			}

			return array( $edit_link, $full_link, $file_name, $user_name, $user_info );
		}

		/**
		 * Get Top Files with Most Click
		 *
		 * @param array $data Input data with limit
		 *
		 * @return array
		 */
		public function get_files_which_have_download_clicks( $data ) {
			$page  = isset( $data['page'] ) ? absint( $data['page'] ) : 0;
			$limit = isset( $data['limit'] ) ? absint( $data['limit'] ) : 0;
			$query = "SELECT {$this->posts_table}.ID as id, {$this->posts_table}.guid as filename, SUM(hits_count) as sum_click FROM {$this->prevent_table} INNER JOIN {$this->posts_table} ON {$this->prevent_table}.post_id = {$this->posts_table}.ID WHERE {$this->posts_table}.post_type = 'attachment' AND {$this->prevent_table}.type = '' AND {$this->prevent_table}.is_default = 0 group by post_id HAVING sum_click > 0 ORDER BY sum_click DESC LIMIT %d,%d";

			return $this->wpdb->get_results( $this->wpdb->prepare( $query, $page, $limit ) );
		}

		/**
		 * Get Top Download Links with Most Clicks
		 *
		 * @param array $data Input data with limit.
		 *
		 * @return array
		 */
		public function get_private_links_have_download_clicks( $data ) {
			$page  = isset( $data['page'] ) ? absint( $data['page'] ) : 0;
			$limit = isset( $data['limit'] ) ? absint( $data['limit'] ) : 0;
			$query = "SELECT {$this->posts_table}.ID as id, {$this->posts_table}.guid as filename, {$this->prevent_table}.url, {$this->prevent_table}.hits_count FROM {$this->prevent_table} INNER JOIN {$this->posts_table} ON {$this->prevent_table}.post_id = {$this->posts_table}.ID WHERE {$this->posts_table}.post_type = 'attachment' AND {$this->prevent_table}.hits_count > 0 AND {$this->prevent_table}.type = '' AND {$this->prevent_table}.is_default = 0 ORDER BY {$this->prevent_table}.hits_count DESC LIMIT %d,%d";

			return $this->wpdb->get_results( $this->wpdb->prepare( $query, $page, $limit ) );
		}

		/**
		 * Get Top going-to-expire Download Links
		 *
		 * @param array $data Input data with limit.
		 *
		 * @return array mixed
		 */
		public function get_expire_download_links_nearly( $data ) {
			$page        = isset( $data['page'] ) ? absint( $data['page'] ) : 0;
			$limit       = isset( $data['limit'] ) ? absint( $data['limit'] ) : 0;
			$queryString = "SELECT {$this->posts_table}.ID as id, {$this->posts_table}.guid as filename, url, expired_date FROM {$this->prevent_table} INNER JOIN {$this->posts_table} ON {$this->prevent_table}.post_id = {$this->posts_table}.ID WHERE {$this->prevent_table}.expired_date > UNIX_TIMESTAMP() AND ( {$this->prevent_table}.limit_downloads is NULL OR {$this->prevent_table}.limit_downloads > {$this->prevent_table}.hits_count ) AND {$this->posts_table}.post_type = 'attachment' AND is_prevented = 1 AND {$this->prevent_table}.is_default = 0 AND {$this->prevent_table}.type = '' order by expired_date asc limit %d,%d";
			$preparation = $this->wpdb->prepare( $queryString, $page, $limit );

			return $this->wpdb->get_results( $preparation );
		}

		/**
		 * Top 10 Files with Most Download Links
		 *
		 * @param array $data Input data with limit.
		 *
		 * @return array
		 */
		public function get_top_files_with_most_download_links( $data ) {
			$page  = isset( $data['page'] ) ? absint( $data['page'] ) : 0;
			$limit = isset( $data['limit'] ) ? absint( $data['limit'] ) : 0;
			$query = "SELECT {$this->posts_table}.ID as id, {$this->posts_table}.guid as filename, COUNT(is_prevented) as sum_private_link FROM {$this->prevent_table} INNER JOIN {$this->posts_table} ON {$this->prevent_table}.post_id = {$this->posts_table}.ID WHERE {$this->posts_table}.post_type = 'attachment' AND {$this->prevent_table}.type = '' AND {$this->prevent_table}.is_default = 0 group by post_id ORDER BY sum_private_link DESC LIMIT %d,%d";

			return $this->wpdb->get_results( $this->wpdb->prepare( $query, $page, $limit ) );
		}

		/**
		 * Get total of Private Download Links for a post
		 *
		 * @param array $data
		 *
		 * @return string|null Database query result (as string), or null on failure
		 */
		public function get_total_private_download_link( $data ) {
			$query = "SELECT COUNT(*) FROM {$this->prevent_table} WHERE post_id = %d AND type = '' AND is_default = 0";

			return $this->wpdb->get_var( $this->wpdb->prepare( $query, $data["post_id"] ) );
		}

		/**
		 * Get total of click to all Private Download Links for a post
		 *
		 * @param array $data
		 *
		 * @return string|null Database query result (as string), or null on failure
		 */
		public function get_total_click_to_private_download_link( $data ) {
			$query = "SELECT SUM(hits_count) FROM {$this->prevent_table} WHERE post_id = %d AND type = '' AND is_default = 0";

			return $this->wpdb->get_var( $this->wpdb->prepare( $query, $data["post_id"] ) );
		}

		/**
		 * Get Private Download Links by Link ID
		 *
		 * @param integer $id Link ID.
		 *
		 * @return string|null Database query result (as string), or null on failure
		 */
		public function get_private_download_link_by_id( $id ) {
			$query = "SELECT COUNT(*) FROM {$this->prevent_table} WHERE ID = %d AND type = '' AND is_default = 0";

			return $this->wpdb->get_var( $this->wpdb->prepare( $query, $id ) );
		}


	}
}
