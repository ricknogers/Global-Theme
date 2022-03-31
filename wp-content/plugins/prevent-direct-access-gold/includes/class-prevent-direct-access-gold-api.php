<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/17/18
 * Time: 15:18
 */

if ( ! class_exists( 'PDA_Api_Gold' ) ) {
	/**
	 * Class PDA_Api_Gold
	 */
	class PDA_Api_Gold {
		/**
		 * Database repository
		 *
		 * @var PDA_v3_Gold_Repository.
		 */
		private $repo;

		/**
		 * PDA Services Class
		 *
		 * @var stdClass PDA_Services PDA Services Class.
		 */
		private $services;

		/**
		 * PDA_Api_Gold constructor.
		 */
		public function __construct() {
			$this->repo     = new PDA_v3_Gold_Repository();
			$this->services = new PDA_Services();
		}

		/**
		 * Register rest routes
		 */
		public function register_rest_routes() {
			register_rest_route(
				'pda/v3',
				'/files/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'protect_files' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/files/(?P<id>\d+)',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'is_protected' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/un-protect-files/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'un_protect_files' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/encrypt-file/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'encrypt_file' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/decrypt-file/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'decrypt_file' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/private-urls/(?P<id>\d+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'list_private_links' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v3',
				'/private-urls/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create_private_urls' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/delete-private-urls/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'delete_private_urls' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/update-private-urls/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'update_private_urls' ),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v3',
				'/set-default/(?P<id>\d+)',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'set_default' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v3',
				'/remote-log',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'remoteLogHandle' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v3',
				'/generate-expired-link-for-video/(?P<id>\d+)',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'generate_expired_for_video' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v3',
				'/init_data',
				array(
					'methods'             => 'GET',
					'callback'            => array(
						$this,
						'load_init_data',
					),
					'permission_callback' => function () {
						return current_user_can( 'upload_files' );
					},
				)
			);

			register_rest_route(
				'pda/v1',
				'/generate-expired-link/(?P<id>\d+)',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'generateExpired' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v1',
				'/delete-private-link',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'delete_private_link' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v1',
				'/generate-expired-from-private',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'generateExpiredFromPrivateLink' ),
					'permission_callback' => '__return_true',
				)
			);

			// API for ip block.
			register_rest_route(
				'pda-ip-block/v1',
				'/add-user-roles/(?P<post_id>[0-9-]+)',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'add_user_roles_to_meta_post' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda-ip-block/v1',
				'/get-file-access-permissions/(?P<post_id>[0-9-]+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'get_file_access_permissions' ),
					'permission_callback' => '__return_true',
				)
			);

			/*
			 * Remove later
			register_rest_route(
				'pda-ip-block/v1',
				'/get-user-roles-settings',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'get_user_roles_in_settings' ),
				)
			);
			*/

			// API for membership integration.
			register_rest_route(
				'pda-memberships-integration/v1',
				'/get-members-select/(?P<post_id>[0-9-]+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'get_members_in_meta_post' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v1',
				'pdf/(?P<id>[0-9]+)',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'get_pdf_content' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda/v1',
				'/debug/(?<id>[0-9]+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'debug' ),
					'permission_callback' => '__return_true',
					'show_in_index' => false,
				)
			);

			register_rest_route(
				'pda/v1',
				'/fix-wpml/(?<id>[0-9]+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'fix_wpml' ),
					'permission_callback' => '__return_true',
					'show_in_index' => false,
				)
			);

			register_rest_route(
				'pda-magic-link/v1',
				'/add-private-link-for-magic-link/(?P<post_id>[0-9-]+)',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'add_private_link_for_magic_link' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'pda-magic-link/v1',
				'/get-private-link-for-magic-link/(?P<post_id>[0-9-]+)',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'get_private_link_for_magic_link' ),
					'permission_callback' => '__return_true',
				)
			);
		}

		/**
		 * API get private link for magic link
		 *
		 * @param array $data The post data from client including:
		 *                    post_id: The attachment id.
		 *
		 * @return array
		 */
		public function get_private_link_for_magic_link( $data ) {
			return $this->services->handle_data_for_get_private_link_magic_link( $data );
		}

		/**
		 * API create new private link for magic link
		 *
		 * @param array $data The post data from client including:
		 *                    post_id: The attachment id
		 *                    private_link_user_limit_downloads: Limit downloads for private link
		 *                    private_link_user_expired_days: Expired time for private link
		 *                    selectedRoles: User roles client select on UI.
		 */
		public function add_private_link_for_magic_link( $data ) {
			$this->services->check_before_create_private_link_for_magic_link( $data );
		}

		/**
		 * API debug
		 *
		 * @param array $data data on client.
		 *
		 * @return array
		 */
		public function debug( $data ) {
			$checker = new PDA_v3_Rewrite_Rule_Checker();

			return $checker->check_apache_rules();
		}

		/**
		 * Internal API to fix wpml.
		 *
		 * @param array $data data on client.
		 *
		 * @return array
		 */
		public function fix_wpml( $data ) {
			if ( '1' === $data['id'] ) {
				return $this->pda_fix_wpml_protection();
			} else if ( '2' === $data['id'] ) {
				return $this->pda_fix_wpml_srcset();
			} else {
				return 'Do nothing';
			}
		}

		public function pda_fix_wpml_srcset() {
			if ( ! $this->can_process_fix_wpml() ) {
				return;
			}

			global $wpdb;
			$limit = 50;

			$response = array();

			// Select attachments has _pda pattern in _wp_attachment_metadata but have _pda.
			$attachments_prepared = $wpdb->prepare( "
            SELECT SQL_CALC_FOUND_ROWS post_id, meta_value 
            FROM {$wpdb->postmeta} m
            WHERE m.meta_key = '_wp_attached_file' AND m.meta_value LIKE '_pda%'
            AND m.post_id NOT IN
            	(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value LIKE %s)
            ORDER BY m.post_id DESC LIMIT %d", array( '_wp_attachment_metadata', '%_pda%', $limit ) );

			$attachments = $wpdb->get_results( $attachments_prepared );
			foreach ( $attachments as $a ) {
				// Select the main language attachment.
				$main_attachment_query = $wpdb->prepare( "
				SELECT post_id 
				FROM {$wpdb->postmeta} m
				WHERE m.meta_key = '_wp_attached_file' AND m.meta_value = %s
				AND m.post_id NOT IN 
					(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s)
				", array( $a->meta_value, 'wpml_media_processed' ) );

				$main_attachments = $wpdb->get_results( $main_attachment_query );
				if ( ! empty( $main_attachments ) ) {
					$wpml_integration = new PDA_WPML();
					$wpml_integration->after_protect_file( $main_attachments[0]->post_id );
				}
			}

			$found       = $wpdb->get_var( "SELECT FOUND_ROWS()" );

			$response['left'] = max( $found - $limit, 0 );
			if ( $response['left'] ) {
				$response['message'] = sprintf( __( 'Fixing srcset. %d left', 'sitepress' ), $response['left'] );
			} else {
				$response['message'] = sprintf( __( 'Fixing srcset: done!', 'sitepress' ), $response['left'] );
			}

			return $response;
		}

		public function pda_fix_wpml_protection() {
			if ( ! $this->can_process_fix_wpml() ) {
				return;
			}

			global $wpdb;
			$limit = 50;

			$response = array();

			// Select attachments has _pda pattern in _wp_attached_file but have never updated the protection status.
			$attachments_prepared = $wpdb->prepare( "
            SELECT SQL_CALC_FOUND_ROWS post_id, meta_value 
            FROM {$wpdb->postmeta} m
            WHERE m.meta_key = '_wp_attached_file' AND m.meta_value LIKE '_pda/%'
            AND m.post_id NOT IN
            	(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s)
            ORDER BY m.post_id DESC LIMIT %d", array( PDA_v3_Constants::PROTECTION_META_DATA, 1, $limit ) );

			$attachments = $wpdb->get_results( $attachments_prepared );
			$found       = $wpdb->get_var( "SELECT FOUND_ROWS()" );

			foreach ( $attachments as $a ) {
				// Select the main language attachment.
				$main_attachment_query = $wpdb->prepare( "
				SELECT post_id 
				FROM {$wpdb->postmeta} m
				WHERE m.meta_key = '_wp_attached_file' AND m.meta_value = %s
				AND m.post_id NOT IN 
					(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s)
				", array( $a->meta_value, 'wpml_media_processed' ) );

				$main_attachments = $wpdb->get_results( $main_attachment_query );
				if ( ! empty( $main_attachments ) ) {
					$wpml_integration = new PDA_WPML();
					$wpml_integration->after_protect_file( $main_attachments[0]->post_id );
				}
			}

			$response['left'] = max( $found - $limit, 0 );
			if ( $response['left'] ) {
				$response['message'] = sprintf( __( 'Fixing media. %d left', 'sitepress' ), $response['left'] );
			} else {
				$response['message'] = sprintf( __( 'Fixing media: done!', 'sitepress' ), $response['left'] );
			}

			return $response;
		}

		private function can_process_fix_wpml() {
			if ( ! class_exists( 'PDA_Original_Link_Services' ) || ! class_exists( 'PDA_WPML' ) ) {
				return false;
			}

			$wpml_integration = new PDA_WPML();

			return $wpml_integration->is_installed();
		}


		/**
		 * Get user roles in setting options
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return array
		 */
		public function get_user_roles_in_settings( $data ) {
			$settings = get_option( PDA_v3_Constants::OPTION_NAME );
			$results  = array(
				'file_access_permission' => '',
				'whitelist_roles'        => '',
			);
			if ( $settings ) {
				$options = unserialize( $settings );
				if ( array_key_exists( PDA_v3_Constants::FILE_ACCESS_PERMISSION, $options ) ) {
					$whitelist = '';
					if ( array_key_exists( PDA_v3_Constants::WHITElIST_ROLES, $options ) && '' != $options[ PDA_v3_Constants::WHITElIST_ROLES ] ) {
						$whitelist = implode( ' - ', $options[ PDA_v3_Constants::WHITElIST_ROLES ] );
					}
					$results = array(
						'file_access_permission' => $options[ PDA_v3_Constants::FILE_ACCESS_PERMISSION ],
						'whitelist_roles'        => $whitelist,
					);
				}
			}

			return $results;
		}

		/**
		 * API get memberships in meta post
		 *
		 * @param array $data The post data from client including:
		 *                    post_id: The attachment id.
		 *
		 * @return mixed
		 */
		public function get_members_in_meta_post( $data ) {
			$data = get_post_meta( $data['post_id'], PDA_v3_Constants::$pda_meta_key_memberships_integration );

			return $data;
		}

		/**
		 * Get user roles in post meta by post id.
		 *
		 * @param array $data The post data from client including:
		 *                    post_id: The attachment id.
		 *
		 * @return array|bool
		 */
		public function get_file_access_permissions( $data ) {
			if ( ! isset( $data['post_id'] ) ) {
				return false;
			}

			return $this->services->get_fap( $data['post_id'] );
		}

		/**
		 * Add user roles to meta post by post id
		 *
		 * @param array $data The post data from client including:
		 *                    file_access_permision: Type user role: Default setting, Admin, Author, Logger-in User, Anyone, No one, Custom Roles
		 *                    user_roles: "roles" user selected
		 *                    type: Type role: Default setting, No users, Custom user
		 *                    users: "user name" user selected.
		 */
		public function add_user_roles_to_meta_post( $data ) {
			if ( isset( $data['file_access_permision'] ) && ! empty( $data['file_access_permision'] ) ) {
				if ( - 1 !== Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) ) {
					$type = $data['file_access_permision'];
					update_post_meta( $data['post_id'], PDA_v3_Constants::$pda_meta_key_user_roles, $type );
				}

				do_action( 'pda_gold_handle_after_set_fap', $data );
			}
		}

		/**
		 * API generate private link expired
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id
		 *                    post_fix: Prefix for private link
		 *                    clear_expired_link: Check whether to removed the expired links
		 *                    expired_date: The private link's expiration date.
		 *
		 * TODO: consider to rename to function by following the snake case format.
		 *
		 * @return bool|false|int|string
		 */
		public function generateExpired( $data ) {
			$repo = new PDA_v3_Gold_Repository();

			return $repo->generateExpired( $data );
		}

		/**
		 * API protect file by post id
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return array|bool|WP_Error|WP_REST_Response
		 */
		public function protect_files( $data ) {
			$result = PDA_Private_Link_Services::protect_file( $data['id'] );
			if ( is_wp_error( $result ) ) {
				return new WP_REST_Response(
					array(
						'message' => $result->get_error_message(),
					),
					400
				);
			}

			return array(
				'url' => wp_get_attachment_url( $data['id'] ),
			);
		}

		/**
		 * API get all private link by post id
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return array
		 */
		public function list_private_links( $data ) {
			$links = $this->repo->get_private_links_by_post_id_and_type_is_null( $data['id'] );

			return array_map(
				function ( $link ) {
					$link['full_url'] = Pda_v3_Gold_Helper::get_private_url( $link['url'] );
					$link['time']     = strtotime( $link['time'] );

					return $link;
				},
				$links
			);
		}

		/**
		 * API create private url
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id
		 *                    url: Private URL
		 *                    is_prevented: Status URL: active or de-active
		 *                    limit_downloads: Limit downloads for private link
		 *                    expired_days: Expired time for private link.
		 *
		 * @return bool
		 */
		public function create_private_urls( $data ) {
			return $this->services->check_before_create_private_link( $data );
		}

		/**
		 * API update private url by post id
		 *
		 * @param array $data The post data from client including:
		 *                    id: ID of private link
		 *                    info: All info private link: status, download limit, expire time.
		 *
		 * @return bool
		 */
		public function update_private_urls( $data ) {
			return $this->repo->update_private_link( $data['id'], $data['info'] );
		}

		/**
		 * API update private url by post id
		 *
		 * @param array $data The post data from client including:
		 *                    id: ID of private link.
		 *
		 * @return bool
		 */
		public function delete_private_urls( $data ) {
			return $this->repo->delete_private_link( $data['id'] ) > 0;
		}

		/**
		 * API delete private link by URI
		 *
		 * @param array $data The post data from client including:
		 *                    url: Private link.
		 *
		 * @return bool
		 */
		public function delete_private_link( $data ) {
			return $this->repo->delete_private_link_by_uri( $data ) > 0;
		}

		/**
		 * API get file info by post id
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return array
		 */
		public function is_protected( $data ) {
			$pda_functions = new Pda_Gold_Functions();
			if ( $pda_functions->getSettings( PDA_v3_Constants::REMOTE_LOG ) ) {
				$edit_url = wp_get_attachment_url( $data['id'] ) . '?t=' . uniqid();
			} else {
				$edit_url = wp_get_attachment_url( $data['id'] );
			}
			$is_synced = $pda_functions->check_file_synced_s3( $data['id'] ) && Yme_Plugin_Utils::is_plugin_activated( 'pdas3' ) === - 1;
			$title     = get_the_title( $data['id'] ) === '' ? '(no title)' : get_the_title( $data['id'] );

			return array(
				'is_protected' => $this->repo->is_protected_file( $data['id'] ),
				'is_encrypted' => \PDAGOLD\modules\Files\Util::is_file_encrypted( $data['id'] ),
				'post'         => array(
					'id'       => $data['id'],
					'title'    => $title,
					'edit_url' => $edit_url,
					's3_link'  => $is_synced,
				),
				'role_setting' => $this->get_user_roles_in_settings( $data ),
			);
		}

		/**
		 * API unprotect file by post id
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return array|WP_REST_Response
		 */
		public function un_protect_files( $data ) {
			$result = $this->repo->un_protect_file( $data['id'] );

			if ( is_wp_error( $result ) ) {
				return new WP_REST_Response(
					array(
						'message' => $result->get_error_message(),
					),
					400
				);
			}

			return array(
				'url' => wp_get_attachment_url( $data['id'] ),
			);
		}

		public function remoteLogHandle( $data ) {
			$message = 'Site: ' . site_url() . ', License\'s result: ' . $data['license_res'];
			error_log( $message );
			$remote = new PDA_Gold_Logger_V3();
			$remote->remote_log( $message, true );
		}

		/**
		 * API set default private link
		 *
		 * @param array $data The post data from client including:
		 *                    id: ID of private link
		 *                    post_id: The attachment id.
		 */
		public function set_default( $data ) {
			return $this->repo->set_default_private_link( $data['id'], $data['post_id'] );
		}

		function generateExpiredFromPrivateLink( $data ) {
			$url = $data['url'];

			return $this->services->generate_private_link( $url, '.mp4' );
		}

		/**
		 * API get pdf content
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return string
		 */
		public function get_pdf_content( $data ) {
			$file_path = get_post_meta( $data['id'], '_wp_attached_file', true );
			if ( ! empty( $file_path ) ) {
				$upload_dir = wp_upload_dir( '' );
				$file       = rtrim( $upload_dir['basedir'], '/' ) . '/' . $file_path;
				if ( file_exists( $file ) ) {
					$handle  = fopen( $file, 'r' );
					$data    = fread( $handle, filesize( $file ) );
					$encoded = base64_encode( $data );

					return $encoded;
				}
			}
		}

		/**
		 * API generate private link expired for video
		 *
		 * @param array $data The post data from client including:
		 *                    id: The attachment id.
		 *
		 * @return array
		 */
		public function generate_expired_for_video( $data ) {
			$repo      = new PDA_v3_Gold_Repository();
			$meta      = get_post_meta( $data['id'] );
			$meta_name = explode( '/', $meta['_wp_attached_file'][0] );
			$meta_name = $meta_name[ count( $meta_name ) - 1 ];

			return array(
				'name'       => $meta_name,
				'meta_value' => unserialize( $meta['_wp_attachment_metadata'][0] ),
				'link'       => $repo->generateExpired( $data ),
			);
		}

		/**
		 * API load init data
		 */
		public function load_init_data() {
			$extensions = array(
				'wp-pda-ip-block/wp-pda-ip-block.php',
//				'pda-membership-integration/pda-membership-integration.php',
			);

			$active_exts = array_map(
				function ( $extension ) {
					if ( ! is_plugin_active( $extension ) ) {
						return array();
					}
					$no_ext           = str_replace( '.php', '', strtok( $extension, '/' ) );
					$func_name        = str_replace( '-', '_', $no_ext );
					$component_folder = call_user_func( $func_name . '_component_folder' );

					return array(
						'name'   => $extension,
						'folder' => $component_folder,

					);
				},
				$extensions
			);

			return array(
				'extensions' => $active_exts,
			);
		}

		public function encrypt_file( $data ) {
			try {
				$attachment_id = $data['id'];
				$service       = new \PDAGOLD\modules\Files\Service();
				$service->encrypt_file( $attachment_id );
				wp_send_json(
					array(
						'success' => true,
						'message' => 'Encrypted file successfully',
					)
				);
				wp_die();
			} catch ( \Exception $exception ) {
				wp_send_json(
					array(
						'success' => false,
						'message' => $exception->getMessage(),
					),
					400
				);
				wp_die();
			}
		}

		public function decrypt_file( $data ) {
			try {
				$attachment_id = $data['id'];
				$service       = new \PDAGOLD\modules\Files\Service();
				$service->decrypt_file( $attachment_id );
				wp_send_json(
					array(
						'success' => true,
						'message' => 'Encrypted file successfully',
					)
				);
				wp_die();
			} catch ( \Exception $exception ) {
				wp_send_json(
					array(
						'success' => false,
						'message' => $exception->getMessage(),
					),
					400
				);
				wp_die();
			}
		}


	}
}
