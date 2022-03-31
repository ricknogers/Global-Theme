<?php

if ( ! class_exists( 'PDA_PPW_Stats_Repository' ) ) {
	class PDA_Stats_PPW_Repository {

		private $wpdb;
		private $table_name;
		protected static $instance;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			global $wpdb;
			$this->wpdb       = &$wpdb;
			$this->table_name = $wpdb->prefix . PDA_Stats_Constants::PDA_PPW_TABLE;
		}

		/**
		 * Insert a password tracked into a table.
		 *
		 * @param array $data
		 *
		 * @return int|false The number of rows inserted, or false on error.
		 */
		public function insert_data_to_db( $data ) {
			$result = $this->wpdb->insert(
				$this->table_name,
				$data
			);

			return $result;
		}

		public function check_db_is_exist() {
			return $this->wpdb->get_var( "SHOW TABLES LIKE '$this->table_name'" ) == $this->table_name;
		}

		/**
		 * Only accept Individual page post type.
		 *
		 * @since 1.2.1
		 *
		 * @return string
		 */
		public function exclude_types() {
			$sitewide = PDA_Stats_Constants::PPWP_ENTIRE_SITE;
			$pcp      = PDA_Stats_Constants::PPWP_PCP;
			$ppwp_al  = PDA_Stats_Constants::PPWP_AL;

			$exclude_types = array(
				"'{$sitewide}'",
				"'{$pcp}'",
				"'{$ppwp_al}'",
			);

			return implode( ', ', $exclude_types );
		}

		/**
		 * Get password of individual Page/Post.
		 *
		 * @param string  $password Password.
		 * @param integer $post_id  Post ID.
		 *
		 * @return array.
		 */
		public function get_individual_post_password( $password, $post_id ) {
			$exclude_types = $this->exclude_types();

			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE BINARY password = %s AND post_id = %s AND ( post_type NOT IN ({$exclude_types}) OR post_type IS NULL )", $password, $post_id );
			$result       = $this->wpdb->get_results( $query_string );

			return $result;
		}

		/**
		 * @param string $password    Password.
		 * @param string $post_id     Post id.
		 * @param string $access_date Access date.
		 * @param string $meta_data   Meta data after updated.
		 *
		 * @return mixed
		 */
		public function update_expired_date_individual_pwd( $password, $post_id, $access_date, $meta_data ) {
			$query_string = $this->wpdb->prepare( "UPDATE $this->table_name SET meta_data = %s WHERE BINARY password = %s AND post_id = %s AND access_date = %s ", $meta_data, $password, $post_id, $access_date );

			$result = $this->wpdb->query( $query_string );

			return $result;
		}

		/**
		 * Get password of individual Page/Post.
		 *
		 * @param string  $password Password.
		 * @param integer $post_id  Post ID.
		 * @param string  $username Username.
		 *
		 * @return array.
		 */
		public function count_single_password( $password, $post_id, $username ) {
			$exclude_types = $this->exclude_types();
			$where_post_id = PDA_Stats_Helpers::get_instance()->generate_condition_to_check_post_id( $post_id );

			$query_string = $this->wpdb->prepare( "SELECT COUNT(*) FROM $this->table_name WHERE username = %s AND BINARY password = %s AND {$where_post_id} AND ( post_type NOT IN ({$exclude_types}) OR post_type IS NULL )", $username, $password );
			$result       = $this->wpdb->get_var( $query_string );

			return $result;
		}

		/**
		 * Get password of individual Page/Post.
		 *
		 * @param string  $password   Password.
		 * @param integer $post_id    Post ID.
		 * @param string  $ip_address IP Address.
		 *
		 * @return array.
		 */
		public function count_single_ip( $password, $post_id, $ip_address ) {
			$exclude_types = $this->exclude_types();
			$where_post_id = PDA_Stats_Helpers::get_instance()->generate_condition_to_check_post_id( $post_id );

			$query_string = $this->wpdb->prepare( "SELECT COUNT(*) FROM $this->table_name WHERE ip_address = %s AND BINARY password = %s AND {$where_post_id} AND ( post_type NOT IN ({$exclude_types}) OR post_type IS NULL )", $ip_address, $password );
			$result       = $this->wpdb->get_var( $query_string );

			return $result;
		}

		/**
		 * Get lastest single password of individual Page/Post.
		 *
		 * @param string  $password Password.
		 * @param integer $post_id  Post ID.
		 *
		 * @return array.
		 */
		public function get_latest_single_password( $password, $post_id ) {
			$exclude_types = $this->exclude_types();
			$where_post_id = PDA_Stats_Helpers::get_instance()->generate_condition_to_check_post_id( $post_id );

			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE BINARY password = %s AND {$where_post_id} AND ( post_type NOT IN ({$exclude_types}) OR post_type IS NULL ) AND meta_data LIKE '%\"expired_date\"%' ORDER BY access_date DESC LIMIT 1", $password );
			$result       = $this->wpdb->get_row( $query_string );

			return $result;
		}

		/**
		 * Get all Passwords of Individual page.
		 *
		 * @return array
		 */
		public function get_all_ppw() {
			$exclude_types  = $this->exclude_types();
			$password_table = $this->wpdb->prefix . 'pda_passwords';

			$column = '';
			if ( $this->wpdb->get_col( "SHOW COLUMNS FROM $password_table LIKE 'label'" ) ) {
				$column = ', label';
			}

			$query_string = "SELECT username, t1.post_id, access_date, t2.campaign_app_type, t1.password, t1.meta_data, ip_address, user_agent{$column} FROM $this->table_name as t1 LEFT JOIN {$password_table} as t2 ON t1.password = t2.password AND t1.post_id = t2.post_id WHERE ( post_type NOT IN ({$exclude_types}) OR post_type IS NULL )";
			$result       = $this->wpdb->get_results( $query_string );

			// Fire the hook ppwp_pro_all_passwords here that help to sync massaged data across the password extensions.
			$result = apply_filters( 'ppwp_pro_all_passwords', $result );
			return $result;
		}

		/**
		 * Get data to track protected post
		 * post_id, access_count, unique_ip and post_id
		 *
		 * @return mixed
		 */
		public function get_post_stat() {
			$exclude_types = $this->exclude_types();

			return $this->wpdb->get_results( "SELECT post_id, post_type, COUNT(*) as access_count, COUNT(DISTINCT ip_address) as unique_ip FROM $this->table_name WHERE post_type NOT IN ({$exclude_types}) OR post_type IS NULL GROUP BY post_id" ); // phpcs:ignore
		}

		/**
		 * Get data to track password of protected post
		 * password, access_count, unique_ip, post_id
		 * @return mixed
		 */
		public function get_password_stat() {
			$sitewide = PDA_Stats_Constants::PPWP_ENTIRE_SITE;
			$pcp      = PDA_Stats_Constants::PPWP_PCP;

			return $this->wpdb->get_results( "SELECT password, COUNT(*) as access_count, COUNT(DISTINCT ip_address) as unique_ip, post_id FROM $this->table_name WHERE post_type NOT IN ('{$sitewide}', '{$pcp}') OR post_type IS NULL GROUP BY post_id,BINARY password" ); // phpcs:ignore
		}

		/**
		 * Get entire site password need to track for Stats
		 *
		 * @return mixed
		 */
		public function get_entire_site_passwords_stats() {
			$sitewide = PDA_Stats_Constants::PPWP_ENTIRE_SITE;
			return $this->wpdb->get_results( "SELECT password, COUNT(*) as access_count, COUNT(DISTINCT ip_address) as unique_ip FROM $this->table_name WHERE post_type = '{$sitewide}' GROUP BY BINARY password" ); // phpcs:ignore
		}

		/**
		 * Get entire site passwords info
		 *
		 * @return mixed
		 */
		public function entire_site_get_passwords_info() {
			$sitewide = PDA_Stats_Constants::PPWP_ENTIRE_SITE;
			return $this->wpdb->get_results( "SELECT * FROM $this->table_name WHERE post_type = '{$sitewide}'" ); // phpcs:ignore
		}

		/**
		 * Get PCP password need to track for Stats
		 *
		 * @return mixed
		 */
		public function get_pcp_passwords_stats() {
			$pcp_shortcode = PDA_Stats_Constants::PPWP_PCP;

			return $this->wpdb->get_results( "SELECT password, COUNT(*) as access_count, COUNT(DISTINCT ip_address) as unique_ip FROM $this->table_name WHERE post_type = '{$pcp_shortcode}' GROUP BY BINARY password" ); // phpcs:ignore
		}

		/**
		 * Get PCP password need to track for Stats
		 *
		 * @return mixed
		 */
		public function get_al_passwords_stats() {
			$al_type = PDA_Stats_Constants::PPWP_AL;

			return $this->wpdb->get_results( "SELECT password, COUNT(*) as access_count, COUNT(DISTINCT ip_address) as unique_ip FROM $this->table_name WHERE post_type = '{$al_type}' GROUP BY BINARY password" ); // phpcs:ignore
		}

		/**
		 * Get PCP passwords info
		 *
		 * @return mixed
		 */
		public function get_pcp_passwords_info() {
			$pcp_shortcode = PDA_Stats_Constants::PPWP_PCP;

			return $this->wpdb->get_results( "SELECT * FROM $this->table_name WHERE post_type = '{$pcp_shortcode}'" ); // phpcs:ignore
		}

		/**
		 * Get PCP passwords info
		 *
		 * @return mixed
		 */
		public function get_al_passwords_info() {
			$al_type = PDA_Stats_Constants::PPWP_AL;

			return $this->wpdb->get_results( "SELECT * FROM $this->table_name WHERE post_type = '{$al_type}'" ); // phpcs:ignore
		}
	}
}
