<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/18/18
 * Time: 15:00
 */

if ( ! class_exists( 'PDA_v3_Gold_Repository' ) ) {

	class PDA_v3_Gold_Repository {

		private $wpdb;
		private $table_name;

		/**
		 * PDA_v3_Gold_Repository constructor.
		 *
		 * @codeCoverageIgnore
		 */
		public function __construct() {
			global $wpdb;
			$this->wpdb       = &$wpdb;
			$this->table_name = $wpdb->prefix . 'prevent_direct_access';
		}

		/**
		 * Create a private link
		 *
		 * @param array $file_info Information of file.
		 *
		 * @return bool|false|int
		 */
		function create_private_link( $file_info ) {

			$file_info['time'] = current_time( 'mysql', true );
			$post_id           = $file_info['post_id'];
			$post              = $this->get_post_by_id( $post_id );

			$result = false;
			if ( isset( $post ) ) {
				$result = $this->wpdb->insert( $this->table_name, $file_info );
			}

			return $result;
		}

		/**
		 * Update private link
		 *
		 * @param string $id ID of private link.
		 * @param array $data Data of private link.
		 *
		 * @return bool
		 */
		function update_private_link( $id, $data ) {
			if ( array_key_exists( 'is_prevented', $data ) && $data['is_prevented'] === false ) {
				$data['is_default'] = 0;
			}

			return $this->wpdb->update( $this->table_name, $data, array(
					'ID' => $id
				) ) > 0;
		}

		/**
		 * Check file protected
		 *
		 * @param string $post_id ID's post.
		 *
		 * @return bool
		 */
		function is_protected_file( $post_id ) {
			$file                     = get_post_meta( $post_id, '_wp_attached_file', true );
			$is_in_protected_folder   = strpos( $file, Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/' ) ) === 0;
			$is_protected_in_metadata = '1' === get_post_meta( $post_id, PDA_v3_Constants::PROTECTION_META_DATA, true );

			return $is_in_protected_folder && $is_protected_in_metadata;
		}

		function get_post_id_by_private_uri( $private_url ) {
			$table_name = $this->table_name;
			$prepare    = $this->wpdb->prepare( "
				SELECT post_id FROM $table_name
				WHERE url = %s
					AND is_prevented = %d
			", $private_url, 1 );

			return $this->wpdb->get_row( $prepare );
		}

		/**
		 * @param $post_id
		 *
		 * @return array|object|null
		 */
		function get_all_private_link_by_post_id( $post_id ) {
			$table_name = $this->table_name;
			$prepare    = $this->wpdb->prepare( "
				SELECT * FROM $table_name
				WHERE post_id = %s
				ORDER BY time DESC
			", $post_id );

			return $this->wpdb->get_results( $prepare, ARRAY_A );
		}

		function get_private_links_by_post_id_and_type_is_null( $post_id ) {
			$table_name = $this->table_name;
			$prepare    = $this->wpdb->prepare( "
				SELECT * FROM $table_name
				WHERE post_id = %s
				AND type = ''
				ORDER BY time DESC
			", $post_id );

			return $this->wpdb->get_results( $prepare, ARRAY_A );
		}

		function get_post_by_id( $post_id ) {
			$post = get_post( $post_id );

			return $post;
		}

		function delete_private_link( $id ) {
			$table_name = $this->table_name;
			$result     = $this->wpdb->delete( $table_name, array(
				'ID' => $id
			) );

			return $result;
		}

		function delete_private_link_by_uri( $data ) {
			$table_name = $this->table_name;
			$result     = $this->wpdb->delete( $table_name, array(
				'url' => $data['url']
			) );

			return $result;
		}

		function deactivate_private_links( $post_id ) {
			$table_name = $this->table_name;

			return $this->wpdb->update( $table_name, array(
				'is_prevented' => false
			), array(
				'post_id' => $post_id
			) );
		}

		function get_protected_posts() {
			$table_name = $this->table_name;
			$query      = "SELECT DISTINCT post_id FROM $table_name where is_prevented = 1";

			return $this->wpdb->get_results( $query, ARRAY_A );
		}

		function updated_file_protection( $post_id, $is_protected ) {
			return update_post_meta( $post_id, PDA_v3_Constants::PROTECTION_META_DATA, $is_protected );
		}

		function get_advance_file_by_url( $url ) {
			$advance_file = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE url LIKE %s", $url ) );

			return $advance_file;
		}

		function un_protect_file( $post_id, $is_updated_meta = true ) {
			$file = get_post_meta( $post_id, '_wp_attached_file', true );

			// check if files are already not in the Media Vault protected folder
			if ( 0 !== stripos( $file, Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/' ) ) ) {
				return true;
			}

			$protected_dir = ltrim( dirname( $file ), Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/' ) );
			$move_result   = Prevent_Direct_Access_Gold_File_Handler::move_attachment_to_protected( $post_id, $protected_dir, array(), true );

			if ( is_wp_error( $move_result ) ) {
				return $move_result;
			}

			if ( $is_updated_meta ) {
				$this->updated_file_protection( $post_id, false );
			}

			do_action( PDA_Hooks::PDA_HOOK_AFTER_UN_PROTECT_FILE, $post_id );

		}

		/**
		 * Using for background process
		 */
		function un_protect_files() {
			$table_name     = $this->wpdb->prefix . 'postmeta';
			$query          = "SELECT post_id FROM $table_name WHERE meta_key = '_pda_protection' and meta_value = 1 ";
			$post_id        = $this->wpdb->get_results( $query, ARRAY_A );
			$num_file_moved = 0;
			foreach ( $post_id as $key => $value ) {
				//the reason why do not update meta here because we need to keep these data for backup.
				$this->un_protect_file( $value['post_id'], false );
				$num_file_moved ++;
				update_option( PDA_v3_Constants::PDA_NUM_BACKUP_FILES_OPTION, $num_file_moved );
			}
			update_option( PDA_v3_Constants::PDA_NUM_BACKUP_FILES_OPTION, 0 );

			return $num_file_moved;
		}

		/**
		 * Migrate pda v2 options to v3.
		 */
		function migrate_pda_options() {
			error_log( 'DEBUG: ' . wp_json_encode( 'Migrating options' ) );
			$table_name        = $this->wpdb->prefix . 'options';
			$query_string      = "SELECT option_name, option_value FROM $table_name where
				option_name = 'remote_log' ||
				option_name = 'pda_auto_protect_new_file' ||
				option_name = 'pda_gold_no_access_page' ||
				option_name = 'whitelist_user_groups' ||
				option_name = 'pda_apply_logged_user' ";
			$site_query_string = "SELECT option_name, option_value FROM $table_name where
                option_name = 'pda_prefix_url' ||
                option_name = 'pda_gold_enable_image_hot_linking' ||
				option_name = 'remove_license_and_all_data' ||
                option_name = 'pda_gold_enable_directory_listing' ";

			$result       = $this->wpdb->get_results( $query_string );
			$site_result  = $this->wpdb->get_results( $site_query_string );
			$site_options = [];

			$helper = new Pda_Gold_Functions();

			$pda_enable_protection = get_option( 'pda_enable_protection', false );
			$pda_apply_logged_user = get_option( 'pda_apply_logged_user', false );
			$whitelist_roles       = get_option( 'whitelist_roles', false );
			error_log( 'Enable protection: ' . wp_json_encode( $pda_enable_protection ) );
			$is_logged  = $helper->getPdaApplyLogged( $pda_enable_protection, $pda_apply_logged_user, $whitelist_roles );
			$list_roles = array();
			if ( 'custom_roles' === $is_logged['file_access_permission'] ) {
				$list_roles = $this->get_list_roles();
			}

			error_log( 'List roles: ' . wp_json_encode( $list_roles ) );
			error_log( 'Is logged: ' . wp_json_encode( $is_logged ) );

			$options = [];

			foreach ( $site_result as $key => $value ) {
				$site_options[ $value->option_name ] = $value->option_value;
			}

			foreach ( $result as $key => $value ) {
				$options[ $value->option_name ] = $value->option_value;
			}

			$data_options = array_merge( $options, $list_roles, $is_logged );

			$pda_v3      = serialize( $data_options );
			$pda_v3_site = serialize( $site_options );

			error_log( 'Options to save: ' . wp_json_encode( $pda_v3 ) );
			error_log( 'Site Options to save: ' . wp_json_encode( $pda_v3_site ) );

			update_option( PDA_v3_Constants::OPTION_NAME, $pda_v3, 'no' );
			update_site_option( PDA_v3_Constants::SITE_OPTION_NAME, $pda_v3_site );

			$update_service = new Pda_Update_Service();
			$update_service->migrate_data_for_no_access_page();
		}

		function get_list_roles() {
			$table_name   = $this->wpdb->prefix . 'options';
			$query_string = "SELECT option_name, option_value FROM $table_name where option_name ='whitelist_roles' ";
			$result       = $this->wpdb->get_results( $query_string );
			$options      = [];
			foreach ( $result as $key => $value ) {
				$options[ $value->option_name ] = unserialize( $value->option_value );
			}

			return $options;
		}

		function set_default_private_link( $id, $post_id ) {
			$table_name = $this->table_name;
			$old_id     = $this->wpdb->get_row(
				"SELECT ID FROM $table_name WHERE post_id = $post_id AND is_default = 1"
			);

			if ( ! is_null( $old_id ) ) {
				$this->update_private_link( $old_id->ID, array(
					'is_default' => false
				) );
			}

			$this->wpdb->query(
				$this->wpdb->prepare( "UPDATE $table_name set is_default = 1 WHERE ID = %d", $id )
			);

		}

		/**
		 * @deprecated
		 * @codeCoverageIgnore
		 */
		function get_all_private_link() {
			$advance_file = $this->wpdb->get_results( "SELECT * FROM $this->table_name WHERE is_prevented = 1 AND (limit_downloads is NULL OR limit_downloads > hits_count) AND (expired_date is NULL OR expired_date > UNIX_TIMESTAMP())" );

			return $advance_file;
		}

		public function get_default_private_link( $post_id ) {
			global $wpdb;
			$table_name   = $this->table_name;
			$query        = "SELECT * FROM $table_name where is_prevented = 1 and is_default = 1 and (limit_downloads is NULL OR limit_downloads > hits_count) and (expired_date is NULL OR expired_date > UNIX_TIMESTAMP()) and post_id = $post_id";
			$query_string = apply_filters( 'pda_magic_link_get_default_private_link', $query, $table_name, $post_id );
			$result       = $wpdb->get_row( $query_string );

			return $result;
		}

		public function insert_default_private_link( $post_id, $time_die ) {
			$url                = Pda_v3_Gold_Helper::generate_unique_string();
			$expired_time_stamp = new DateTime();
			$expired_time_stamp->modify( $time_die );
			$value = array(
				'post_id'         => $post_id,
				'is_prevented'    => true,
				'limit_downloads' => null,
				'expired_date'    => $expired_time_stamp->getTimestamp(),
				'url'             => $url,
				'is_default'      => 1
			);
			$data  = apply_filters( 'pda_magic_link_insert_default_private_link', $value, $post_id, $url );
			$this->create_private_link( $data );

			return $url;
		}

		/**
		 * @codeCoverageIgnore
		 * @deprecated
		 */
		public function get_all_default_private_link() {
			$query_string = "SELECT * FROM $this->table_name where is_default = 1";
			$result       = $this->wpdb->get_results( $query_string );

			return $result;
		}

		/**
		 * @param $post_id
		 * TODO: Need to check
		 *
		 * @return bool|int|WP_Error
		 */
		public function protect_prevent_files( $post_id ) {
			$is_protected_file = $this->is_protected_file( $post_id );
			if ( ! $is_protected_file ) {
				$file = get_post_meta( $post_id, '_wp_attached_file', true );
				if ( 0 === stripos( $file, Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/' ) ) ) {
					return new WP_Error( 'protected_file_existed', sprintf(
						__( 'This file is already protected. Please reload your page.', 'prevent-direct-access-gold' ),
						$file
					), array( 'status' => 500 ) );
				}
				$move_result = Prevent_Direct_Access_Gold_File_Handler::move_attachment_file( $post_id );
				if ( $move_result !== true ) {
					return $move_result;

					return new WP_Error( 'protected_file_existed', sprintf(
						__( 'Can not move the files %s to protected folder.', 'prevent-direct-access-gold' ),
						$file
					), array( 'status' => 500 ) );
				}

				return $this->updated_file_protection( $post_id, true );
			}
		}

		public function generateExpired( $data ) {
			$post_id           = $data['id'];
			$is_protected_file = $this->is_protected_file( $post_id );
			$post_fix          = array_key_exists( 'post_fix', $data ) ? $data['post_fix'] : '';
			if ( $is_protected_file ) {

				//Delete all expired private link
				if ( array_key_exists( "clear_expired_link", $data ) && $data['clear_expired_link'] === true ) {
					$this->delete_all_expired_private_link_by_post_id( $data['id'] );
				}

				$file_info = array(
					'post_id'         => $post_id,
					'is_prevented'    => true,
					'limit_downloads' => null,
					'url'             => Pda_v3_Gold_Helper::generate_unique_string( $post_fix ),
					'type'            => PDA_v3_Constants::PDA_PRIVATE_LINK_EXPIRED,
				);
				if ( isset( $data['expired_date'] ) ) {
					$now                       = new DateTime();
					$expired_date              = $now->modify( $data['expired_date'] );
					$file_info['expired_date'] = $expired_date->getTimestamp();
				}
				$result = $this->create_private_link( $file_info );

				if ( $result ) {
					$settings   = new Pda_Gold_Functions();
					$prefix_url = $settings->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );
					$setting    = new Pda_Gold_Functions;

					if ( $setting->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
						$url = home_url( '/' ) . "index.php?" . PDA_v3_Constants::$secret_param . "=" . $file_info['url'] . "&pdav3_rexypo=ymerexy";
					} else {
						$url = home_url( '/' ) . "{$prefix_url}/" . $file_info['url'];
					}

					return $url;
				}

				return $result; // @codeCoverageIgnore
			} else {
				$url = wp_get_attachment_url( $post_id );

				return $url;
			}
		}

		/**
		 * Backup protection
		 */
		public function backup_protection() {
			$table_name             = $this->wpdb->prefix . 'postmeta';
			$query                  = "SELECT post_id FROM $table_name WHERE meta_key = '_pda_protection' and meta_value = 1 ORDER BY post_id DESC";
			$post_id                = $this->wpdb->get_results( $query, ARRAY_A );
			$num_of_protected_files = 0;
			foreach ( $post_id as $key => $value ) {
				if ( isset( $value['post_id'] ) ) {
					PDA_Private_Link_Services::protect_file( $value['post_id'] );
					$num_of_protected_files ++;
					update_option( PDA_v3_Constants::PDA_NUM_BACKUP_FILES_OPTION, $num_of_protected_files, true );
				}
			}
			update_option( PDA_v3_Constants::PDA_NUM_BACKUP_FILES_OPTION, 0 );

			return $num_of_protected_files;
		}

		/**
		 * Count protected files
		 *
		 * @return number|string|null
		 */
		public function get_protected_files() {
			$table_name = $this->wpdb->prefix . 'postmeta';
			$query      = "SELECT COUNT(*) FROM $table_name WHERE meta_key = '_pda_protection' and meta_value = 1 ";

			return $this->wpdb->get_var( $query );
		}

		public function delete_all_expired_private_link_by_post_id( $post_id ) {
			$pda_private_link_expired = PDA_v3_Constants::PDA_PRIVATE_LINK_EXPIRED;
			$query_string             = "DELETE FROM $this->table_name WHERE time < SUBDATE( NOW(), INTERVAL 1 DAY ) AND post_id = $post_id AND type = '$pda_private_link_expired'";
			$this->wpdb->query( $query_string );
		}

		/**
		 * Retrieve one private link by post id.
		 *
		 * @param string $post_id ID's post
		 *
		 * @return array|object|null
		 */
		function get_private_link_for_user_by_post_id( $post_id ) {
			$prepare = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE post_id = %s AND type != ''", $post_id );

			return $this->wpdb->get_row( $prepare );
		}

		/**
		 * Retrieve one private link by user id.
		 *
		 * @param string $id User id
		 *
		 * @return array|object|null
		 */
		function get_private_link_for_user_by_id( $id ) {
			$prepare = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE ID = %s AND type != ''", $id );

			return $this->wpdb->get_row( $prepare );
		}

		function get_advance_file_by_url_and_type_is_special( $url ) {
			$advance_file = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE url LIKE %s AND ( type = %s OR type = %s )", $url, PDA_v3_Constants::PDA_PRIVATE_LINK_USER, PDA_v3_Constants::PDA_PRIVATE_LINK_S3_USER ) );

			return $advance_file;
		}

		function get_all_post_id_protect() {
			$postmeta_table       = $this->wpdb->prefix . 'postmeta';
			$protection_meta_data = PDA_v3_Constants::PROTECTION_META_DATA;
			$query_string         = "SELECT post_id FROM $postmeta_table WHERE meta_key = '$protection_meta_data' AND meta_value = '1'";
			$results              = $this->wpdb->get_results( $query_string );

			return array_filter( $results, function ( $item ) {
				return $this->is_protected_file( $item->post_id );
			} );
		}

		function get_all_post_id_un_protect() {
			$postmeta_table = $this->wpdb->prefix . 'postmeta';
			$query_string   = "SELECT post_id FROM $postmeta_table WHERE meta_key = '_wp_attached_file' AND meta_value NOT LIKE '%_pda%'";
			$results        = $this->wpdb->get_results( $query_string );

			return array_filter( $results, function ( $item ) {
				return ! $this->is_protected_file( $item->post_id );
			} );

		}

		function get_all_post_and_page_publish() {
			$postmeta_table = $this->wpdb->prefix . 'posts';
			$query_string   = "SELECT ID, post_title FROM $postmeta_table WHERE post_status = 'publish' AND ( post_type ='post' OR post_type = 'page' )";
			$results        = $this->wpdb->get_results( $query_string );

			return $results;
		}

		function delete_all_private_link_expired_with_type( $type, $retention_time ) {
			$query_string = "DELETE FROM $this->table_name WHERE type = '$type' AND ( ( expired_date IS NOT NULL AND expired_date <= UNIX_TIMESTAMP() )
OR ( limit_downloads IS NOT NULL AND limit_downloads <= hits_count )
OR ( UNIX_TIMESTAMP(time) + $retention_time < UNIX_TIMESTAMP()  )
) ";
			$this->wpdb->query( $query_string );
		}

		/**
		 * Get post id by meta value
		 *
		 * @param string $key Meta key
		 * @param string $value Meta value
		 *
		 * @return bool|int
		 */
		public function get_post_id_by_meta_value( $key, $value ) {
			global $wpdb;
			$sql = $wpdb->prepare( "
                SELECT post_id FROM {$wpdb->postmeta}
                WHERE meta_key = %s
                AND meta_value = %s
            ", $key, $value );

			$result = $wpdb->get_var( $sql );

			if ( is_null( $result ) ) {
				return false;
			}

			return (int) $result;
		}

		/**
		 * Get attached file.
		 *
		 * @param int $attachment_id Attachment ID.
		 *
		 * @return int
		 */
		public static function get_attached_file( $attachment_id ) {
			global $wpdb;
			$sql = $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND post_id = %d", (int) $attachment_id );

			return $wpdb->get_row( $sql );
		}

	}
}
