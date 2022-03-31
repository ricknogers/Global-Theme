<?php
/**
 * User: gaupoit
 * Date: 6/26/18
 * Time: 16:24
 *
 * @package pda_services
 */

if ( ! class_exists( 'PDA_Services' ) ) {
	/**
	 * PDA services that containing the functions to interact with media files.
	 *
	 * Class PDA_Services
	 */
	class PDA_Services {

		/**
		 * @var Pda_v3_Gold_Helper
		 */
		private $gold_helpers;

		/**
		 * @var PDA_v3_Gold_Repository
		 */
		private $gold_repo;

		/**
		 * PDA_Services constructor.
		 */
		public function __construct() {
			$this->gold_helpers = Pda_v3_Gold_Helper::get_instance();
			$this->gold_repo    = new PDA_v3_Gold_Repository();
		}

		/**
		 * Instance
		 *
		 * @var PDA_Services
		 */
		protected static $instance;

		/**
		 * Get instance of singleton
		 *
		 * @return PDA_Services
		 */
		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * @param $gold_helpers
		 *
		 * @return $this
		 */
		public function load_gold_helpers( $gold_helpers ) {
			$this->gold_helpers = $gold_helpers;

			return self::$instance;
		}

		/**
		 * @param $gold_repo
		 *
		 * @return $this
		 */
		public function load_gold_repo( $gold_repo ) {
			$this->gold_repo = $gold_repo;

			return self::$instance;
		}

		/**
		 * Get post by it's permanent url.
		 *
		 * @param string $url Permanent url.
		 *
		 * @return array Posts result.
		 */
		public static function get_post_by_url( $url ) {
			$wp_upload_dir  = wp_upload_dir();
			$baseurl        = $wp_upload_dir['baseurl'];
			$meta_value     = str_replace( $baseurl . '/', '', $url );
			$arr_meta_value = explode( '/', $meta_value );
			if ( ! in_array( '_pda', $arr_meta_value, true ) ) {
				array_unshift( $arr_meta_value, '_pda' );
			}
			$meta_value = implode( '/', $arr_meta_value );
			$attachment = get_posts( array(
				'post_type'  => 'attachment',
				'meta_key'   => '_wp_attached_file',
				'meta_value' => $meta_value,
			) );

			return $attachment;
		}

		/**
		 * Generate a new private link for the protected medial file with its existed private link.
		 *
		 * @param string $url      The private url.
		 * @param string $post_fix (optional) Post fix string for the unique private uri.
		 *                         For example, if post_fix is -pda then the private uri should be <random_unique_string>-pda.
		 *
		 * @return string Return the private url if the media file is protected, otherwise returning the original link.
		 */
		public static function generate_private_link( $url, $post_fix ) {
			$repo        = new PDA_v3_Gold_Repository();
			$prefix      = new Pda_Gold_Functions();
			$prefix_name = $prefix->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );
			$private_uri = str_replace( home_url() . '/' . $prefix_name . '/', '', $url );
			$post        = $repo->get_post_id_by_private_uri( $private_uri );
			$data        = array(
				'id'       => $post->post_id,
				'post_fix' => $post_fix,
			);

			return $repo->generateExpired( $data );
		}

		/**
		 * Checking the success of creating a new private link
		 *
		 * @param array $data Input data including post id, is_prevented, limit downloads and expired date.
		 *
		 * @return bool Creation's result.
		 */
		public function check_before_create_private_link( $data ) {
			$is_valid_data = $this->is_private_link_data_valid( $data );
			if ( ! $is_valid_data ) {
				return new WP_Error(
					'malformed_data',
					sprintf(
						__( 'Malformed data error', 'prevent-direct-access-gold' )
					),
					array(
						'status' => 400,
					)
				);
			}

			$repo = new PDA_v3_Gold_Repository();
			if ( ! $repo->is_protected_file( $data['id'] ) ) {
				return new WP_Error(
					'post_not_found',
					sprintf(
					/* translators: %d Attachment ID */
						__( 'Cannot found the post : %d', 'prevent-direct-access-gold' ),
						$data['id']
					),
					array(
						'status' => 404,
					)
				);
			}
			$url = isset( $data['url'] ) ? $data['url'] : Pda_v3_Gold_Helper::generate_unique_string();
			if ( ! is_null( $repo->get_advance_file_by_url( $url ) ) ) {
				return new WP_Error(
					'duplicate_url',
					sprintf(
					/* translators: %s the private URL*/
						__( 'This url : %s already existed!', 'prevent-direct-access-gold' ),
						$url
					),
					array(
						'status' => 400,
					)
				);
			}

			$result = $repo->create_private_link(
				array(
					'post_id'         => $data['id'],
					'is_prevented'    => isset( $data['is_prevented'] ) ? $data['is_prevented'] : true,
					'limit_downloads' => isset( $data['limit_downloads'] ) ? $data['limit_downloads'] : null,
					'expired_date'    => isset( $data['expired_days'] ) ? $this->get_expired_time_stamp( $data['expired_days'] ) : null,
					'url'             => $url,
				)
			);

			return $result > 0;
		}

		/**
		 * Check whether the request DATA of private link creation is valid
		 *
		 * @param array $data The POST request data.
		 *                    required positive integer|string integer id: Attachment ID.
		 *                    optional string url: The private link URL.
		 *                    optional positive integer: limit_downloads: The number of downloads.
		 *                    optional positive integer: expired_date: The expiry date of private link.
		 *
		 * @return bool
		 *  False: The malformed data.
		 *  True: The clean data.
		 */
		private function is_private_link_data_valid( $data ) {
			if ( ! isset( $data['id'] ) || ! $this->gold_helpers->parse_string_to_positive_integer( $data['id'] ) ) {
				return false;
			}

			// TODO: need to define the rules and validation function.
			$reg_ex_special_chars = '/[~`!#$%\^@&*+=\(\)\[\]\\\';,\/{}|\\":<>\?]/';
			if ( isset( $data['url'] ) && preg_match( $reg_ex_special_chars, $data['url'] ) ) {
				return false;
			}

			if ( isset( $data['is_prevented'] ) && ! is_bool( $data['is_prevented'] ) ) {
				return false;
			}

			if ( isset( $data['limit_downloads'] ) && ! $this->gold_helpers->parse_string_to_positive_integer( $data['limit_downloads'] ) ) {
				return false;
			}

			if ( isset( $data['expired_date'] ) && ! $this->gold_helpers->parse_string_to_positive_integer( $data['expired_date'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * @param        $days_to_expired
		 * @param string $unit
		 *
		 * @return int
		 * @throws Exception
		 */
		function get_expired_time_stamp( $days_to_expired, $unit = 'day' ) {
			$curr_date    = new DateTime();
			$expired_date = $curr_date->modify( $days_to_expired . " $unit" );

			return $expired_date->getTimestamp();
		}


		/**
		 * Auto create the private link for protected attachment.
		 *
		 * @param array $data Including the post_id.
		 *
		 * @return bool
		 */
		public function auto_create_new_private_link( $data ) {

			$setting = new Pda_Gold_Functions();
			if ( ! $setting->getSettings( 'pda_auto_create_new_private_link' ) ) {
				return false;
			}

			$repo             = new PDA_v3_Gold_Repository();
			$all_private_link = $repo->get_all_private_link_by_post_id( $data['id'] );
			if ( empty( $all_private_link ) ) {
				return $this->check_before_create_private_link( $data );
			} else {
				$is_prevented = false;
				foreach ( $all_private_link as $private_link ) {
					$limit_downloads = ( is_null( $private_link['limit_downloads'] ) || $private_link['hits_count'] < $private_link['limit_downloads'] ) ? true : false;
					$curr_date       = new DateTime();
					$expired_date    = ( is_null( $private_link['expired_date'] ) || $private_link['expired_date'] > $curr_date->getTimestamp() ) ? true : false;
					if ( '1' === $private_link['is_prevented'] && $limit_downloads && $expired_date ) {
						$is_prevented = true;
						break;
					}
				}
				if ( ! $is_prevented ) {
					return $this->check_before_create_private_link( $data );
				}
			}
		}

		public function check_before_create_private_link_for_magic_link( $data ) {
			$repo = new PDA_v3_Gold_Repository();
			if ( ! $repo->is_protected_file( $data['post_id'] ) ) {
				return new WP_Error( 'post_not_found', sprintf(
					__( 'Cannot found the post : %s', 'prevent-direct-access-gold' ),
					$data['post_id']
				), array( 'status' => 404 ) );
			}
			$url = Pda_v3_Gold_Helper::generate_unique_string();
			if ( ! is_null( $repo->get_advance_file_by_url( $url ) ) ) {
				return new WP_Error( 'duplicate_url', sprintf(
					__( 'This url : %s already existed!', 'prevent-direct-access-gold' ),
					$url
				), array( 'status' => 400 ) );
			}

			$func      = new Pda_Gold_Functions;
			$is_synced = $func->check_file_synced_s3( $data['post_id'] );

			$private_link_user = $repo->get_private_link_for_user_by_post_id( $data['post_id'] );

			if ( $private_link_user === null ) {
				$repo->create_private_link( array(
					'post_id'         => $data['post_id'],
					'is_prevented'    => true,
					'limit_downloads' => isset( $data['private_link_user_limit_downloads'] ) ? $data['private_link_user_limit_downloads'] : null,
					'expired_date'    => isset( $data['private_link_user_expired_days'] ) ? $this->get_expired_time_stamp( $data['private_link_user_expired_days'] ) : null,
					'url'             => $url,
					'roles'           => isset( $data['selectedRoles'] ) ? $data['selectedRoles'] : "",
					'type'            => $is_synced ? PDA_v3_Constants::PDA_PRIVATE_LINK_S3_USER : PDA_v3_Constants::PDA_PRIVATE_LINK_USER,
				) );
			} else {
				$time         = current_time( $private_link_user->time );
				$date         = new DateTime( $time );
				$expired_date = isset( $data['private_link_user_expired_days'] ) ? $date->modify( $data['private_link_user_expired_days'] . ' day' ) : null;
				$repo->update_private_link( $private_link_user->ID, array(
					'limit_downloads' => isset( $data['private_link_user_limit_downloads'] ) ? $data['private_link_user_limit_downloads'] : null,
					'expired_date'    => $expired_date !== null ? $expired_date->getTimestamp() : $expired_date,
					'roles'           => isset( $data['selectedRoles'] ) ? $data['selectedRoles'] : "",
					'type'            => $is_synced ? 'p_user_s3' : 'p_user',
				) );
			}
		}

		public function handle_data_for_get_private_link_magic_link( $data ) {
			$repo              = new PDA_v3_Gold_Repository();
			$private_link_user = $repo->get_private_link_for_user_by_post_id( $data['post_id'] );
			if ( $private_link_user !== null ) {
				$days = "";
				if ( $private_link_user->expired_date !== null ) {
					$expire_day = date( 'Y-m-d', $private_link_user->expired_date );
					$create_day = date_format( date_create( $private_link_user->time ), 'Y-m-d' );
					$date1      = new DateTime( $expire_day );
					$date2      = new DateTime( $create_day );
					$result     = $date1->diff( $date2 );
					$days       = $result->days;
				}
				$full_url = Pda_v3_Gold_Helper::get_private_url( $private_link_user->url );

				return array(
					'ID'              => $private_link_user->ID,
					'post_id'         => $private_link_user->post_id,
					'limit_downloads' => $private_link_user->limit_downloads,
					'roles'           => $private_link_user->roles,
					'expired_date'    => $days,
					'full_url'        => $full_url,
					'is_prevented'    => $private_link_user->is_prevented,
					'time'            => strtotime( $private_link_user->time ),
				);
			}
		}

		/**
		 * Find and replace the protected file.
		 *
		 * @param string $content The content of post.
		 *
		 * @return string The content after handle.
		 */
		public function find_and_replace_protected_file( $content ) {

			$pre_condition = apply_filters( 'pda_the_content_sr_pre_condition', in_the_loop() );

			if ( ! $pre_condition ) {
				return $content;
			}

			$post_id = get_the_ID();

			// Share hook to check condition.
			$conditions_handle_search_and_replace = apply_filters( PDA_v3_Constants::PDA_BEFORE_HANDLE_SR_HOOK, array(), array( 'post_id' => $post_id ) );
			$is_handle                            = $this->gold_helpers->array_some( $conditions_handle_search_and_replace, true );
			if ( ! $is_handle ) {
				return $content;
			}

			$urls = $this->gold_helpers->extract_url_from_raw_data( $content );

			list ( $urls, $content ) = $this->gold_helpers->filter_internal_url( $urls, $content );

			$list_urls = array_map( function ( $value ) {
				return array(
					'url_to_replace' => $value,
					'url'            => esc_url( $value ),
					'new_url'        => $value,
					'is_replaced'    => false,
				);
			}, $urls );

			$data = array(
				'post_id' => $post_id,
				'urls'    => $list_urls,
			);

			$data = apply_filters( PDA_v3_Constants::PDA_THE_CONTENT_HOOK, $data, $conditions_handle_search_and_replace );

			foreach ( $data['urls'] as $value ) {
				if ( $value['url_to_replace'] !== $value['new_url'] ) {
					$content = str_replace( $value['url_to_replace'], $value['new_url'], $content );
				}
			}

			return $content;
		}

		/**
		 * Convert unprotected to protected URL.
		 * TODO: Implement UT
		 *
		 * @param string $file    URL extract from content.
		 * @param string $baseurl Upload base url.
		 *
		 * @return string PDA Protected URL.
		 */
		public function get_new_url( $file, $baseurl ) {
			// Split url path and query parameters.
			$url_pattern = explode( '?', $file );

			// Get URL Path with not query parameters.
			$url         = $url_pattern[0];
			$pda_baseurl = rtrim( $baseurl, '/' ) . '/_pda';

			// Check pda_folder have in url.
			if ( false !== strpos( $file, $pda_baseurl ) ) {
				return $file;
			}

			// Get attachment object by original url.
			$protected_attached_file = str_replace( $baseurl . '/', '', $url );
			$file_path               = '_pda/' . $protected_attached_file;

			$attachment = $this->gold_helpers->attachment_image_url_to_post( $baseurl . '/', $file_path );
			list( $size, $url_file ) = $this->gold_helpers->get_image_size_of_link( $url );

			if ( empty( $attachment ) ) {
				return $file;
			}

			$attachment_id = $attachment->post_id;
			// Check post is protected.
			if ( ! $this->gold_repo->is_protected_file( $attachment_id ) ) {
				return $file;
			}

			// Get query parameters and concat with protected url.
			$query_params = isset( $url_pattern[1] ) ? $url_pattern[1] : '';

			return $this->gold_helpers->generate_protected_url_with_query_string( $size, $attachment_id, $baseurl, $file_path, $query_params );
		}

		/**
		 * Replace old file by new file in content
		 *
		 * @param $attachment_id
		 * @param $pda_func
		 * @param $size
		 * @param $file
		 * @param $content
		 *
		 * @return mixed
		 */
		public function pda_replace_file_in_content( $attachment_id, $pda_func, $size, $file, $content ) {
			$new_file = wp_get_attachment_url( $attachment_id );
			$new_file = $pda_func->handle_file_size( $new_file, $size );

			return str_replace( $file, $new_file, $content );
		}

		/**
		 * Handle license info by get license information from API and save it to database.
		 */
		public function handle_license_info() {
			$license_info = self::get_server_license_info();
			if ( ! isset( $license_info->expired_date ) ) {
				$license = get_option( PDA_v3_Constants::LICENSE_KEY );
				$this->is_license_expired( $license );
				$license_info = self::get_server_license_info();
			}
			update_option( PDA_v3_Constants::LICENSE_INFO, base64_encode( wp_json_encode( $license_info ) ), 'no' );

			return $license_info;
		}

		/**
		 * Debug purpose using by Ymese Helper plugins. It will help to force running license checker cron job.
		 *
		 * @param string $license The plugin license.
		 *
		 * @return array The license info result from server including
		 *  + expired_date string The license expiry date in UNIX timestamp.
		 *  + addons array The list of addons purchased along with the license.
		 */
		public function debug_handle_license_info( $license ) {
			update_option( PDA_v3_Constants::LICENSE_KEY, $license, 'no' );
			$license_info = self::get_server_license_info();
			if ( ! isset( $license_info->expired_date ) ) {
				$license = get_option( PDA_v3_Constants::LICENSE_KEY );
				$this->is_license_expired( $license );
				$license_info = self::get_server_license_info();
			}
			update_option( PDA_v3_Constants::LICENSE_INFO, base64_encode( wp_json_encode( $license_info ) ), 'no' );

			return $license_info;
		}

		/**
		 * Get license information.
		 *
		 * @return bool|mixed
		 */
		public function get_license_info() {
			$license_info = get_option( PDA_v3_Constants::LICENSE_INFO );
			if ( ! $license_info ) {
				return false;
			}

			return json_decode(
				base64_decode( $license_info )
			);
		}

		/**
		 * Set default setting for File Access Permission
		 */
		public function pda_gold_set_default_setting_for_fap() {
			$settings = get_option( PDA_v3_Constants::OPTION_NAME );
			if ( ! $settings ) {
				$pda_default_fap = array(
					PDA_v3_Constants::FILE_ACCESS_PERMISSION => "admin_users",
				);
				update_option( PDA_v3_Constants::OPTION_NAME, serialize( $pda_default_fap ) );
			}
		}

		public function pda_gold_set_default_setting_for_role_protection() {
			$settings = get_option( PDA_v3_Constants::OPTION_NAME, false );
			if ( false === $settings ) {
				$pda_default_role_protection = array(
					PDA_v3_Constants::PDA_GOLD_ROLE_PROTECTION => array( 'author', 'editor' ),
				);
				update_option( PDA_v3_Constants::OPTION_NAME, serialize( $pda_default_role_protection ) );
			} else {
				$options = unserialize( $settings );
				if ( ! array_key_exists( PDA_v3_Constants::PDA_GOLD_ROLE_PROTECTION, $options ) ) {
					$options[ PDA_v3_Constants::PDA_GOLD_ROLE_PROTECTION ] = array( 'author', 'editor' );
					update_option( PDA_v3_Constants::OPTION_NAME, serialize( $options ) );
				}
			}
		}

		/**
		 * Parse X-Cookies authorized value
		 */
		public function parse_x_cookies_value() {
			if ( ! function_exists( 'getallheaders' ) ) {
				$headers = $this->pda_getallheaders();
			} else {
				$headers = getallheaders();
			}

			if ( ! isset ( $headers['Cookie'] ) && isset( $headers['X-Cookies'] ) ) {
				$x_cookies          = $headers['X-Cookies'];
				$tmp                = explode( ';', $x_cookies );
				$wp_cookie_string   = $tmp[0];
				$tmp                = explode( '=', $wp_cookie_string );
				$_COOKIE[ $tmp[0] ] = urldecode( $tmp[1] );
				$user_id            = wp_validate_auth_cookie( $_COOKIE[ LOGGED_IN_COOKIE ], 'logged_in' );
				wp_set_current_user( $user_id );
			}
		}

		/**
		 * Get all headers if user is using the NGINX server.
		 *
		 * @return array
		 */
		public function pda_getallheaders() {
			$headers = [];
			foreach ( $_SERVER as $name => $value ) {
				if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
					$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
				}
			}

			return $headers;
		}

		/**
		 * TODO: will use in the function find_and_replace_protected_file in next version due to the hotfix deadline.
		 * Find the WordPress media source in the img tag
		 * @return array
		 */
		private function find_wp_media_src_in_img( $content ) {
			$img = array();
			preg_match_all( '/<img\s[^>]*?src\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>/iU', $content, $img );
			$src_path    = 1;
			$img_sources = array_unique( $img[ $src_path ] );

			return $this->massage_links_before_replace( $img_sources );
		}

		/**
		 * Massage the links from image or link before replacing
		 *
		 * @param string $img_sources
		 *
		 * @return array
		 */
		private function massage_links_before_replace( $img_sources ) {
			return array_filter(
				$img_sources, function ( $src ) {
				$wp_upload_dir = wp_upload_dir();
				$baseurl       = $wp_upload_dir['baseurl'];

				if ( $this->gold_helpers->is_root_domain_relative_link( $src ) ) {
					$home_url = rtrim( Pda_v3_Gold_Helper::get_home_url_with_ssl(), '/' );
					$src      = $home_url . $src;
				}

				if ( false !== strpos( $src, "$baseurl" . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER ) ) {
					return false;
				}

				return $this->try_is_pda_local_attachment( $src );
			} );
		}


		/**
		 * Our definition for root domain relative link is start with /
		 *
		 * @param string $link
		 *
		 * @return bool
		 *
		 * @deprecated
		 */
		private function is_root_domain_relative_link( $link ) {
			if ( empty( $link ) ) {
				return false;
			}

			return '/' === $link[0];
		}

		private function try_is_pda_local_attachment( $src ) {
			$protected_src = $this->massage_before_protected_link( $src );

			return is_local_attachment( $protected_src );
		}

		private function massage_before_protected_link( $src ) {
			$wp_upload_dir     = wp_upload_dir();
			$baseurl           = $wp_upload_dir['baseurl'];
			$protected_baseurl = $baseurl . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER;

			if ( false !== strpos( $src, $protected_baseurl ) ) {
				return $src;
			}

			return str_replace( $baseurl . '/', $protected_baseurl, $src );
		}

		/**
		 * @param $link
		 *
		 * @return bool
		 * @deprecated
		 */
		private function is_internal_link( $link ) {
			$wp_upload_dir  = wp_upload_dir();
			$baseurl        = $wp_upload_dir['baseurl'];
			$wp_upload_path = wp_parse_url( $baseurl, PHP_URL_PATH );

			return false !== strpos( $link, $wp_upload_path );
		}

		/**
		 * Replace unprotected URL with protected URL.
		 *
		 * @param array $data       Array contains list url & post_id
		 * @param array $conditions Check settings in plugin.
		 *
		 * @return array List URL after handle if setting is turn on.
		 */
		public function massage_url_search_and_replace( $data, $conditions ) {
			// Check post id is exist
			if ( ! array_key_exists( 'post_id', $data ) || ! array_key_exists( 'urls', $data ) ) {
				return $data;
			}

			// Check search & replace is turn on and post id is selected.
			if ( ! isset( $conditions[ PDA_v3_Constants::PDA_IS_USING_SEARCH_REPLACE ] ) || ! $conditions[ PDA_v3_Constants::PDA_IS_USING_SEARCH_REPLACE ] ) {
				return $data;
			}

			$wp_upload_dir = wp_upload_dir();
			$baseurl       = $wp_upload_dir['baseurl'];

			// Replace unprotected url.
			$data['urls'] = array_map( function ( $url ) use ( $baseurl ) {
				if ( $url['is_replaced'] ) {
					return $url;
				}
				$new_url = $this->get_new_url( $url['url'], $baseurl );
				if ( empty( $new_url ) ) {
					return $url;
				}
				$url['new_url']     = $new_url;
				$url['is_replaced'] = true;

				return $url;
			}, $data['urls'] );

			return $data;
		}

		/**
		 * @param $advance_file
		 *
		 * @return bool
		 */
		public function is_block_ip_private_link( $advance_file ) {
			if ( ! $this->gold_helpers->is_valid_before_handle_block_ip() ) {
				return false;
			}
			$post_id = $advance_file->post_id;
			$data    = $this->gold_helpers->get_ip_block_by_post_id( $post_id );
			if ( ! isset( $data->ip_block ) ) {
				return false;
			}
			$ip_referer = $this->gold_helpers->get_ip( $_SERVER );
			$ip_blocks  = explode( ';', $data->ip_block );
			foreach ( $ip_blocks as $ip_block ) {
				if ( strpos( $ip_block, '*' ) !== false && $this->gold_helpers->valid_ip( $ip_block, $ip_referer ) ) {
					return true;
				}
				if ( $ip_block == $ip_referer ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * @param $attrs
		 *
		 * @return bool|string
		 * @throws Exception
		 */
		public function generate_pda_private_link_shortcode( $attrs ) {
			$attrs           = shortcode_atts( array(
				'file_id'         => 0,
				'download_limit'  => null,
				'download_expiry' => null,
			), $attrs );
			$file_id         = $attrs['file_id'];
			$download_limit  = $attrs['download_limit'];
			$download_expiry = $attrs['download_expiry'];

			return $this->generate_custom_private_link( $file_id, $download_limit, $download_expiry );
		}

		/**
		 * @param        $file_id
		 * @param        $download_limit
		 * @param        $download_expiry
		 * @param string $type
		 *
		 * @return bool|string
		 * @throws Exception
		 */
		public function generate_custom_private_link( $file_id, $download_limit, $download_expiry, $type = PDA_v3_Constants::PDA_PRIVATE_LINK_LONG_LIFE ) {

			if ( ! $file_id ) {
				throw new InvalidArgumentException( PDA_v3_Constants::PDA_PRIVATE_LINK_SHORT_CODE_ERROR_MESSAGE );
			}

			$file_id = $this->gold_helpers->parse_string_to_positive_integer( $file_id );
			if ( ! is_null( $download_limit ) ) {
				$download_limit = $this->gold_helpers->parse_string_to_positive_integer( $download_limit );
			}
			if ( ! is_null( $download_expiry ) ) {
				$download_expiry = $this->gold_helpers->parse_string_to_positive_integer( $download_expiry );
			}

			if ( ! $file_id || false === $download_limit || false === $download_expiry || $download_expiry > PDA_v3_Constants::TEN_YEAR_IN_MINUTES || $download_limit > PDA_v3_Constants::TEN_YEAR_IN_MINUTES || ! $this->gold_repo->is_protected_file( $file_id ) ) {
				throw new InvalidArgumentException( PDA_v3_Constants::PDA_PRIVATE_LINK_SHORT_CODE_ERROR_MESSAGE );
			}

			$file_info = array(
				'post_id'         => $file_id,
				'is_prevented'    => true,
				'limit_downloads' => $download_limit,
				'expired_date'    => is_null( $download_expiry ) ? null : $this->get_expired_time_stamp( $download_expiry, 'minute' ),
				'url'             => Pda_v3_Gold_Helper::generate_unique_string(),
				'type'            => $type,
			);

			$result = $this->gold_repo->create_private_link( $file_info );
			if ( ! $result ) {
				throw new InvalidArgumentException( PDA_v3_Constants::PDA_PRIVATE_LINK_SHORT_CODE_ERROR_MESSAGE );
			}

			return $this->gold_helpers->generate_private_link_from_url( $file_info['url'] );
		}

		/**
		 * Get user roles in post meta by post id and meta key
		 *
		 * @param string|int $post_id The attachment id.
		 *
		 * @return array
		 */
		public function get_fap( $post_id ) {
			$post_meta = get_post_meta( $post_id, PDA_v3_Constants::$pda_meta_key_user_roles, true );
			$type      = empty( $post_meta ) ? PDA_v3_Constants::PDA_DEFAULT_FAP : $post_meta;

			return array(
				'type'  => $type,
				'roles' => '',
			);
		}


		/**
		 * Check license expired and upgrade product id.
		 */
		public function recheck_license() {
			$license          = get_option( PDA_v3_Constants::LICENSE_KEY );
			$is_expired       = $this->is_license_expired( $license );
			$default_response = array(
				'success' => true,
				'message' => 'Your settings have been updated successfully!',
			);
			if ( true === $is_expired ) {
				update_option( PDA_v3_Constants::LICENSE_EXPIRED, '1', false );

				return $default_response;
			}
			delete_option( PDA_v3_Constants::LICENSE_EXPIRED );
			$license_info = $this->update_license_information();
			if ( ! empty( $license_info->upgraded_products ) ) {
				$product_ids             = explode( ';', $license_info->upgraded_products );
				$last_product_id         = end( $product_ids );
				$need_upgrade_product_id = ! empty( $last_product_id );
				if ( $need_upgrade_product_id ) {
					update_site_option( PDA_v3_Constants::APP_ID, $last_product_id );
				} else {
					return array(
						'success' => false,
						'message' => 'Opps! Please contact our support.',
					);
				}
			}

			return $default_response;
		}

		/**
		 * Update license information.
		 *
		 * @return object|null License information from server.
		 */
		public function update_license_information() {
			$license_info = self::get_server_license_info();
			if ( isset( $license_info->expired_date ) && isset( $license_info->addons ) ) {
				update_option( PDA_v3_Constants::LICENSE_INFO, base64_encode( wp_json_encode( $license_info ) ), 'no' );
			}

			return $license_info;
		}

		/**
		 * Check condition and get license info from server
		 *
		 * @return mixed|null
		 * return null if don't exist method getLicenseInfo in class YME_LICENSE.
		 * else return mixed(license info).
		 */
		public static function get_server_license_info() {
			if ( ! method_exists( 'YME_LICENSE', 'getLicenseInfo' ) ) {
				return null;
			}

			return YME_LICENSE::getLicenseInfo( PDA_v3_Constants::LICENSE_KEY );
		}

		/**
		 * Checking whether the license expired.
		 *
		 * @param string $license The license key
		 *
		 * @return bool
		 * true: License has been expired.
		 * false: License is still valid.
		 */
		public function is_license_expired( $license ) {
			if ( method_exists( 'YME_LICENSE_V2', 'check_expired_license' ) ) {
				return YME_LICENSE_V2::check_expired_license( $license, array(
					'site_url' => get_bloginfo( 'url' ),
					// WP also uses this in user-agent (class class-http.php line 191)
				) );
			}

			return YME_LICENSE::checkExpiredLicense( $license );
		}
	}
}
