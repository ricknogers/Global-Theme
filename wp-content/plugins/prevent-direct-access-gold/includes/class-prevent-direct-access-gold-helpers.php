<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/18/18
 * Time: 15:08
 */

if ( ! class_exists( 'Pda_v3_Gold_Helper' ) ) {

	class Pda_v3_Gold_Helper {

		private $pda_gold_func;

		/**
		 * Instance
		 *
		 * @var Pda_v3_Gold_Helper
		 */
		protected static $instance;

		/**
		 * Get instance of singleton
		 *
		 * @return Pda_v3_Gold_Helper
		 */
		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Pda_v3_Gold_Helper constructor.
		 */
		public function __construct() {
			$this->pda_gold_func = new Pda_Gold_Functions();
		}

		/**
		 * @param $gold_func
		 */
		public function with_gold_function( $gold_func ) {
			$this->pda_gold_func = $gold_func;
		}

		public static function generate_unique_string( $post_fix = '' ) {
			return uniqid() . $post_fix;
		}

		public static function get_plugin_configs() {
			return array( 'custom' => 'custom_v3_181191' );
		}

		/**
		 * @deprecated
		 * @codeCoverageIgnore
		 */
		public static function get_guid( $file_name, $request_url, $file_type ) {
			$guid = preg_replace( "/-\d+x\d+.$file_type$/", ".$file_type", $request_url );
		}

		public static function get_private_url( $uri, $used_raw_rule = true ) {
			$prefix      = new Pda_Gold_Functions();
			$prefix_name = $prefix->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );
			$setting     = new Pda_Gold_Functions;
			if ( $used_raw_rule && $setting->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
				return self::get_home_url_with_ssl() . "index.php?" . PDA_v3_Constants::$secret_param . "=" . $uri . "&pdav3_rexypo=ymerexy";
			} else {
				return self::get_home_url_with_ssl() . $prefix_name . "/$uri";
			}
		}

		public static function is_pdf( $mime_type ) {
			return $mime_type == "application/pdf";
		}

		public static function is_video( $mime_type ) {
			return strstr( $mime_type, "video/" );
		}

		public static function is_audio( $mime_type ) {
			return strstr( $mime_type, "audio/" );
		}

		public static function is_image( $file, $mime_type ) {
			return strstr( $mime_type, "image/" ); // @codeCoverageIgnore
		}

		public static function is_html( $mime_type ) {
			return $mime_type == "text/html";
		}

		/**
		 * @codeCoverageIgnore
		 */
		public static function is_migrated_data_from_v2() {
			return Pda_Gold_Functions::is_data_migrated();
		}

		public static function only_track_http_method( $request_method ) {
			return "get" === strtolower( $request_method );
		}

		public static function get_expired_time_stamp( $days_to_expired ) {
			$curr_date    = new DateTime();
			$expired_date = $curr_date->modify( $days_to_expired . ' day' );

			return $expired_date->getTimestamp();
		}

		public static function timestamp_to_local_date( $timestamp ) {
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );

			return get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), "$date_format $time_format" );
		}

		public static function map_addons_id( $addons ) {
			$massaged_addons = array_filter( array_map( function ( $addon ) {
				return self::addons( $addon );
			}, explode( ';', $addons ) ), function ( $item ) {
				return ! empty( $item );
			} );

			return implode( ', ', $massaged_addons );
		}

		/**
		 * Get add-on's Name.
		 *
		 * @param int $id The add-on ID.
		 *
		 * @return string The add-on name
		 */
		public static function addons( $id ) {
			switch ( $id ) {
				case '77803382':
					return 'PDA Protect WordPress Videos';
				case '77806417':
					return 'PDA Private Magic Links';
				case '77805157':
					return 'PDA Download Link Statistics';
				case '77806451':
					return 'PDA Access Restriction';
				case '77856881':
					return 'PDA Access Restriction';
				case '77829318':
					return 'PDA WooCommerce Integration';
				case '77836903':
					return 'PDA Membership Integration';
				case '77953329':
					return 'PDA ACF Integration';
				case '77835198':
					return 'PDA Contact Forms Integration';
				case '77838452':
					return 'PDA Multisite ';
				case '77847335':
					return 'PDA S3 Integration';
				case '77847381':
					return 'PDA Robots.txt Integration';
				case '77861101':
					return 'PDA ActiveCampaign Integration';
				case '77943938':
					return 'PDA Dropbox Integration';
				case '77964004':
					return 'PDA Campaign Monitor Integration';
				case '77998653':
					return 'PDA MailChimp Integration';
				case '78000954':
					return 'PDA PayPal Integration';
				case '78069621':
					return 'PDA Community Integration';
				default:
					return '';
			}
		}

		/**
		 * @deprecated
		 * @codeCoverageIgnore
		 */
		public static function get_plugin_version() {
			$plugin_data = get_plugin_data( PDA_V3_PLUGIN_BASE_FILE );

			return $plugin_data['Version'];
		}

		/**
		 * @return array
		 */
		public static function get_current_role() {
			if ( is_multisite() && is_super_admin( wp_get_current_user()->ID ) ) {
				$current_role = array( 'administrator' );
			} else {
				$current_role = wp_get_current_user()->roles;
			}

			return $current_role;
		}

		public static function get_home_url_with_ssl() {
			$home_url = is_ssl() ? home_url( '/', 'https' ) : home_url( '/' );
			return apply_filters( 'pda_get_home_url', $home_url );
		}

		/**
		 * Get mime type
		 *
		 * Copy from https://secure.php.net/manual/en/function.mime-content-type.php
		 */
		public static function pda_mime_content_type( $file_name ) {
			$mime_types = array(

				'txt'  => 'text/plain',
				'htm'  => 'text/html',
				'html' => 'text/html',
				'php'  => 'text/html',
				'css'  => 'text/css',
				'js'   => 'application/javascript',
				'json' => 'application/json',
				'xml'  => 'application/xml',
				'swf'  => 'application/x-shockwave-flash',
				'flv'  => 'video/x-flv',

				// images
				'png'  => 'image/png',
				'jpe'  => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg'  => 'image/jpeg',
				'gif'  => 'image/gif',
				'bmp'  => 'image/bmp',
				'ico'  => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif'  => 'image/tiff',
				'svg'  => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				// archives
				'zip'  => 'application/zip',
				'rar'  => 'application/x-rar-compressed',
				'exe'  => 'application/x-msdownload',
				'msi'  => 'application/x-msdownload',
				'cab'  => 'application/vnd.ms-cab-compressed',

				// audio/video
				'mp3'  => 'audio/mpeg',
				'qt'   => 'video/quicktime',
				'mov'  => 'video/quicktime',
				'mp4'  => 'video/mp4',
				'm4v'  => 'video/mp4',

				// adobe
				'pdf'  => 'application/pdf',
				'psd'  => 'image/vnd.adobe.photoshop',
				'ai'   => 'application/postscript',
				'eps'  => 'application/postscript',
				'ps'   => 'application/postscript',

				// ms office
				'doc'  => 'application/msword',
				'rtf'  => 'application/rtf',
				'xls'  => 'application/vnd.ms-excel',
				'ppt'  => 'application/vnd.ms-powerpoint',

				// open office
				'odt'  => 'application/vnd.oasis.opendocument.text',
				'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
			);

			$file_array = explode( '.', $file_name );
			$ext        = strtolower( array_pop( $file_array ) );
			if ( array_key_exists( $ext, $mime_types ) ) {
				return $mime_types[ $ext ];
			} elseif ( function_exists( 'finfo_open' ) ) {
				$file_info = finfo_open( FILEINFO_MIME );
				if ( ! file_exists( $file_name ) ) {
					return "";
				}
				$mime_type = finfo_file( $file_info, $file_name );
				finfo_close( $file_info );

				return $mime_type;
			} elseif ( function_exists( 'wp_check_filetype' ) ) {
				$extension = wp_check_filetype( $file_name );
				if ( isset( $extension['type'] ) && false !== $extension['type'] ) {

					return $extension['type'];
				}
			}

			return 'application/octet-stream';
		}

		/**
		 * Return extensions has updates
		 *
		 * @return array
		 */
		public static function extensions_has_updates() {
			$extensions  = array(
				array(
					'plugin'  => 'wp-pda-ip-block/wp-pda-ip-block.php',
					'version' => '1.0.4.5',
				),
				array(
					'plugin'  => 'pda-membership-integration/pda-membership-integration.php',
					'version' => '1.1.6',
				),
			);
			$updates     = get_plugin_updates();
			$plugin_keys = array_keys( $updates );
			$tmp         = array_map(
				function ( $extension ) use ( $plugin_keys, $updates ) {
					if ( false === array_search( $extension['plugin'], $plugin_keys ) ) {
						return null;
					}

					$plugin = $updates[ $extension['plugin'] ];
					$name   = $plugin->Name;
					if ( $extension['version'] !== $plugin->update->new_version ) {
						return null;
					}

					return "<b>$name" . ' (' . $plugin->update->new_version . ')</b>';
				},
				$extensions
			);

			return array_filter( $tmp, function ( $item ) {
				return ! is_null( $item );
			} );
		}

		/**
		 * Default setting for multisite
		 *
		 * @param integer $blog_id Blog ID
		 *
		 * @return boolean
		 */
		public static function set_default_settings_for_multisite( $blog_id = null ) {
			if ( ! is_multisite() ) {
				return false;
			}

			$option = array( PDA_v3_Constants::FILE_ACCESS_PERMISSION => 'admin_users' );

			if ( $blog_id === null ) {
				return update_option( PDA_v3_Constants::OPTION_NAME, serialize( $option ) );
			}

			return update_blog_option( $blog_id, PDA_v3_Constants::OPTION_NAME, serialize( $option ) );
		}

		/**
		 * @param $content
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_urls_of_image( $content ) {
			$extracted_image_links = array();
			$is_valid              = preg_match_all( '/<img\s[^>]*?src\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>/iU', $content, $extracted_image_links );

			return $is_valid ? array_unique( $extracted_image_links[1] ) : [];
		}

		/**
		 * @param $content
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_urls_of_video_tag( $content ) {
			// classic editor media source.
			$src               = array();
			$video_src_pattern = '<source\s[^>]*?src\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>';
			$is_src_valids     = preg_match_all( "/$video_src_pattern/iU", $content, $src );
			$video_src         = $is_src_valids ? array_unique( $src[1] ) : [];

			// gutenberg editor.
			$video          = array();
			$video_pattern  = '<(video|audio)\s[^>]*?src\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>';
			$is_video_valid = preg_match_all( "/$video_pattern/iU", $content, $video );
			$video_links    = $is_video_valid ? array_unique( $video[2] ) : [];

			return array_unique( array_merge( $video_src, $video_links ) );
		}

		/**
		 * @param $content
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_urls_of_a_tag( $content ) {
			$elements = array();
			$is_valid = preg_match_all( '/<a(.*)href=\"([^\"]*)\"(.|\n)*>(.|\n)*<\/a>/iU', $content, $elements );

			return $is_valid ? array_unique( $elements[2] ) : [];
		}

		/**
		 * @param array  $urls
		 * @param string $content
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function filter_internal_url( $urls, $content ) {
			$wp_upload_path = wp_parse_url( wp_upload_dir()['baseurl'], PHP_URL_PATH );
			$home_url       = rtrim( Pda_v3_Gold_Helper::get_home_url_with_ssl(), '/' );
			$urls           = array_map( function ( $url ) use ( $home_url, &$content ) {
				//TODO: should use it ?
				if ( $this->is_root_domain_relative_link( $url ) ) {
					$content = str_replace( 'href="' . $url . '"', 'href="' . $home_url . $url . '"', $content );

					return $home_url . $url;
				}

				return $url;
			}, $urls );

			$urls = apply_filters( 'pda_after_filter_internal_url',
				array_filter(
					$urls,
					function ( $url ) use ( $wp_upload_path ) {
						return false !== strpos( $url, $wp_upload_path );
					}
				),
				$urls
			);

			return array( $urls, $content );
		}


		/**
		 * @param $url
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 * Our definition for root domain relative link is start with /
		 *
		 */
		public function is_root_domain_relative_link( $url ) {
			if ( empty( $url ) ) {
				return false;
			}

			return '/' === $url[0];
		}

		/**
		 * @param $file_name
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 * Check file is image type
		 *
		 */
		public function is_image_type( $file_name ) {
			preg_match( '/\.(gif|jpg|jpe?g|tiff|png|bmp|webp)$/i', $file_name, $matches );

			return ! empty( $matches );
		}

		/**
		 * @param $file
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_image_size_of_link( $file ) {
			$default_results = array( '', $file );
			if ( ! $this->is_image_type( $file ) ) {
				return $default_results;
			}
			preg_match_all( '(-\d+x\d+\.\w+$)', $file, $matches, PREG_PATTERN_ORDER );

			$found = end( $matches[0] );

			if ( empty( $found ) ) {
				return $default_results;
			}

			$arr      = explode( '.', $found );
			$size     = $arr[0];
			$ext      = $arr[1];
			$url_file = str_replace( $found, ".$ext", $file );

			return array( $size, $url_file );
		}

		/**
		 * @param $baseurl
		 * @param $url_file
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_attachment_by_url( $baseurl, $url_file ) {
			$meta_value = str_replace( $baseurl . '/', "", $url_file );
			$attachment = get_posts( array(
				'post_type'  => 'attachment',
				'meta_key'   => '_wp_attached_file',
				'meta_value' => $meta_value,
			) );
			if ( empty( $attachment ) ) {
				$meta_value = '_pda/' . $meta_value;
				$attachment = get_posts( array(
					'post_type'  => 'attachment',
					'meta_key'   => '_wp_attached_file',
					'meta_value' => $meta_value,
				) );
			}

			return array( $meta_value, $attachment );
		}

		/**
		 * This function generate unprotected to protected attached file,
		 * after find attachment object.
		 *
		 * @param string $baseurl                   Upload base url.
		 * @param string $unprotected_attached_file Unprotected URL.
		 *
		 * @return array Meta value and attachment object from post meta.
		 */
		public function get_attachment_by_unprotected_attached_file( $baseurl, $unprotected_attached_file ) {
			$protected_attached_file = str_replace( $baseurl . '/', "", $unprotected_attached_file );
			$meta_value              = '_pda/' . $protected_attached_file;

			$attachment = get_posts( array(
				'post_type'  => 'attachment',
				'meta_key'   => '_wp_attached_file',
				'meta_value' => $meta_value,
			) );

			return array( $meta_value, $attachment );
		}

		/**
		 * Get protected/redirected URL with query parameters.
		 *
		 * @param string  $size             Size of image.
		 * @param integer $attachment_id    Attachment ID.
		 * @param string  $baseurl          Upload base url.
		 * @param string  $meta_value       Meta value from Post.
		 * @param string  $query_parameters Query parameters from URL.
		 *
		 * @return string Protected URL with query parameters.
		 */
		public function generate_protected_url_with_query_string( $size, $attachment_id, $baseurl, $meta_value, $query_parameters = '' ) {
			/**
			 * Concat protect url with query parameters:
			 *   - Raw URL:
			 *         Input: protect url ( http://preventdirectacccess.com?pf=df.jpg ), query parameters ( t=123123 )
			 *         Output: http://preventdirectacccess.com?pf=df.jpg&t=123123
			 *   - Protected URL:
			 *         Input: protect url ( http://preventdirectacccess.com/wp-content/upload/_pda/2019/19/test.jpg ), query parameters ( t=123123 )
			 *         Output: http://preventdirectacccess.com/wp-content/upload/_pda/2019/19/test.jpg?t=123123
			 *
			 */
			$is_use_redirect_urls = $this->pda_gold_func->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS );
			$protected_url        = $this->get_protected_url( $size, $attachment_id, $baseurl, $meta_value, $is_use_redirect_urls );

			if ( '' === $query_parameters ) {
				return $protected_url;
			}

			$query_code       = $is_use_redirect_urls ? '&' : '?';
			$query_parameters = $query_code . $query_parameters;

			return $protected_url . $query_parameters;
		}

		/**
		 * @param $data
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_use_search_and_replace( $data ) {
			if ( ! $this->pda_gold_func->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ) {
				return false;
			}

			$selected_posts = $this->pda_gold_func->pda_get_setting_type_is_array( PDA_v3_Constants::PDA_REPLACED_PAGES_POSTS );

			return in_array( $data['post_id'], $selected_posts );
		}


		/**
		 * @param $post_id
		 *
		 * @return mixed
		 * @codeCoverageIgnore
		 */
		public function get_ip_block_by_post_id( $post_id ) {
			return Wp_Pda_Ip_Block_Admin::get_ip_block_by_post_id( $post_id );
		}

		/**
		 * @return bool
		 * @codeCoverageIgnore
		 */
		public function is_valid_before_handle_block_ip() {
			return Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 && class_exists( 'Wp_Pda_Ip_Block_Admin' );
		}

		/**
		 * @param $server
		 *
		 * @return mixed
		 *
		 * ref: https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php#comment50230065_3003233
		 */
		function get_ip( $server ) {
			if ( ! empty( $server['HTTP_CF_CONNECTING_IP'] ) ) {
				return $server['HTTP_CF_CONNECTING_IP'];
			}

			if ( ! empty( $server['HTTP_CLIENT_IP'] ) ) {
				return $server['HTTP_CLIENT_IP'];
			}

			if ( ! empty( $server['HTTP_X_FORWARDED_FOR'] ) ) {
				return $server['HTTP_X_FORWARDED_FOR'];
			}

			return $server['REMOTE_ADDR'];
		}

		/**
		 * Valid ip
		 *
		 * @param string $ip_block   Ip pattern or ip address
		 * @param string $ip_referer Ip from client
		 *
		 * @return bool
		 */
		function valid_ip( $ip_block, $ip_referer ) {
			$ips = explode( '.', $ip_block );
			if ( count( $ips ) !== 4 ) {
				return false;
			}
			$every      = '(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])';
			$ips        = array_map( function ( $element ) use ( $every ) {
				if ( $element === '*' ) {
					return $every;
				}

				return $element;
			}, $ips );
			$ip_pattern = '/\b(?:' . $ips[0] . '\.' . $ips[1] . '\.' . $ips[2] . '\.' . $ips[3] . ')\b/';

			return preg_match( $ip_pattern, $ip_referer ) === 1;
		}

		public function get_server_name() {
			global $is_apache;

			if ( $is_apache ) {
				return PDA_v3_Constants::APACHE_SERVER;
			}

			$server_info = isset( $_SERVER['SERVER_SOFTWARE'] ) ? wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) : '';

			$servers = [
				PDA_v3_Constants::NGINX_SERVER,
				PDA_v3_Constants::IIS_SERVER,
			];

			foreach ( $servers as $server ) {
				if ( strpos( strtolower( $server_info ), $server ) !== false ) {
					return $server;
				}
			}

			return PDA_v3_Constants::UNDEFINED_SERVER;
		}

		/**
		 * @param $content
		 *
		 * @return mixed
		 */
		public function get_private_url_for_type_pdf_dflip_plugin( $content ) {
			$elements = array();
			$search   = '\"source\":\s*\"([^\"]+)\"';
			preg_match_all( "/$search/iU", $content, $elements, PREG_PATTERN_ORDER );
			$url_file = array_unique( $elements[1] );

			return $url_file;
		}

		/**
		 * @param $content
		 *
		 * @return mixed
		 */
		public function get_private_url_for_thumbnail_dflip_plugin( $content ) {
			$thumb        = array();
			$search_thumb = '<div\s[^>]*?thumb\s*=\s*[\'\"]([^\'\"]+?)[\'\"][^>]*?>';
			preg_match_all( "/$search_thumb/iU", $content, $thumb, PREG_PATTERN_ORDER );
			$thumb_link = array_unique( $thumb[1] );

			return $thumb_link;
		}

		/**
		 * @param $content
		 *
		 * @return array
		 */
		public function extract_url_from_raw_data( $content ) {
			$urls_of_image_tag = $this->get_urls_of_image( $content );
			$urls_of_a_tag     = $this->get_urls_of_a_tag( $content );
			$urls_of_video_tag = $this->get_urls_of_video_tag( $content );

			$urls = array_unique( array_merge( $urls_of_image_tag, $urls_of_a_tag, $urls_of_video_tag ) );

			if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'dflip/dflip.php' ) ) {
				$urls_of_dflib           = $this->get_private_url_for_type_pdf_dflip_plugin( $content );
				$urls_of_thumbnail_dflib = $this->get_private_url_for_thumbnail_dflip_plugin( $content );

				return array_unique( array_merge( $urls, $urls_of_dflib, $urls_of_thumbnail_dflib ) );
			}

			return $urls;
		}

		/**
		 * Prevent qTranslate X from redirecting REST calls.
		 *
		 * @param string $url_lang Language URL to redirect to.
		 * @param string $url_orig Original URL.
		 * @param array  $url_info Pieces of original URL.
		 *
		 * @return bool
		 * @since 5.3
		 *
		 */
		public function jetpack_no_qtranslate_rest_url_redirect( $url_lang, $url_orig, $url_info ) {
			$wp_paths = array(
				'wp-json/pda',
				'wp-json/wps3',
				'wp-json/puv',
				'wp-json/pda-membership-integration',
				'wp-json/pda-stats',
				'wp-json/pda-get-ip-block',
			);
			$is_exist = array_reduce( $wp_paths, function ( $carry, $item ) use ( $url_info ) {
				$carry = $carry || ( false !== strpos( $url_info['wp-path'], $item ) );

				return $carry;
			}, false );
			if ( $is_exist ) {
				return false;
			}

			return $url_lang;
		}


		/**
		 * @param $raw_url
		 *
		 * @return string
		 */
		public function generate_private_link_from_url( $raw_url ) {
			$prefix_url = $this->pda_gold_func->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );
			$home_url   = self::get_home_url_with_ssl();
			if ( $this->pda_gold_func->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS ) ) {
				return $home_url . "index.php?" . PDA_v3_Constants::$secret_param . "=" . $raw_url . "&pdav3_rexypo=ymerexy";
			}

			return $home_url . "{$prefix_url}/" . $raw_url;
		}

		/**
		 * Note: All character of string is not contain digit number will convert to 0
		 * This case will
		 *
		 * @param $val
		 *
		 * @return bool|float
		 */
		public function parse_string_to_positive_integer( $val ) {
			if ( ! is_numeric( $val ) ) {
				return false;
			}

			$int_val = round( $val );

			if ( $int_val <= 0 || $int_val > PHP_INT_MAX ) {
				return false;
			}

			return $int_val;
		}

		/**
		 * Get protected/redirected URL.
		 * TODO: Review this function.
		 *
		 * @param string  $size                 Size of image.
		 * @param integer $attachment_id        Attachment ID.
		 * @param string  $baseurl              Upload base url.
		 * @param string  $meta_value           Meta value from Post, not contains query parameter.
		 * @param boolean $is_use_redirect_urls Redirect URL turn on/of in setting.
		 *
		 * @return string Protected/Redirected URL.
		 */
		public function get_protected_url( $size, $attachment_id, $baseurl, $meta_value, $is_use_redirect_urls ) {
			if ( ! $is_use_redirect_urls ) {
				return $baseurl . '/' . $meta_value;
			}

			/**
			 * Handle raw URL, concat meta value with PDA base URL
			 */
			$attachment_url        = wp_get_attachment_url( $attachment_id );
			$files_path_components = explode( '/', $attachment_url );
			array_pop( $files_path_components );
			array_push( $files_path_components, wp_basename( $meta_value ) );

			return implode( '/', $files_path_components );
		}

		/**
		 * Method tests whether all elements in the array pass the test implemented by the provided function
		 *
		 * @param array $values Array.
		 *
		 * @return bool It returns a Boolean value
		 */
		public function array_some( $values, $where ) {
			if ( ! is_array( $values ) ) {
				return false;
			}
			foreach ( $values as $value ) {
				if ( $where === $value ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check condition to show notices for PPWP plugin.
		 * PPWP Free >= 1.2.3.1
		 * PPWP Pro < 1.1.3
		 *
		 * @return bool
		 */
		public function is_show_notice_for_ppwp_plugin() {
			if ( ! defined( 'PPW_VERSION' ) || ! defined( 'PPW_PRO_VERSION' ) ) {
				return false;
			}

			return version_compare( PPW_VERSION, '1.2.3.1', '>=' ) && version_compare( PPW_PRO_VERSION, '1.1.3', '<' );
		}

		/**
		 * WP introduced is_wp_version_compatible function from version 5.2.0 only.
		 * (https://developer.wordpress.org/reference/functions/is_wp_version_compatible/)
		 * Need to write the helper by our-self.
		 *
		 * @param string $required Version to check.
		 *
		 * @return bool
		 */
		public static function is_wp_version_compatible( $required ) {
			return empty( $required ) || version_compare( get_bloginfo( 'version' ), $required, '>=' );
		}

		/**
		 * Check whether plugin installed.
		 *
		 * @param string $text_domain Plugin text domain.
		 *
		 * @return bool
		 */
		public static function is_plugin_installed( $text_domain ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$plugins = get_plugins();
			foreach ( $plugins as $plugin_path => $plugin ) {
				if ( $plugin['TextDomain'] === $text_domain ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Massage attachment URL before find.
		 * Reference form attachment_url_to_postid function so I won't improve code for this.
		 *
		 * @param string $url Attachment URL.
		 *
		 * @return false|string Path massaged.
		 * @since 3.1.3 Init function
		 * @link  https://developer.wordpress.org/reference/functions/attachment_url_to_postid/
		 */
		public function massage_file_url( $url ) {
			$dir  = wp_get_upload_dir();
			$path = $url;

			$site_url   = parse_url( $dir['url'] );
			$image_path = parse_url( $path );

			//force the protocols to match if needed
			if ( isset( $image_path['scheme'] ) && ( $image_path['scheme'] !== $site_url['scheme'] ) ) {
				$path = str_replace( $image_path['scheme'], $site_url['scheme'], $path );
			}

			if ( 0 === strpos( $path, $dir['baseurl'] . '/' ) ) {
				$path = substr( $path, strlen( $dir['baseurl'] . '/' ) );
			}

			return $path;
		}

		/**
		 * Tries to convert an attachment URL into a post ID include image size.
		 * Ref from attachment_url_to_postid.
		 *
		 * @param string $base_url  Base URL.
		 * @param string $file_path File Path.
		 *
		 * @return false|object
		 * @since 3.1.4 Support for scaled image
		 * @since 3.1.3 Init function
		 * @link  https://developer.wordpress.org/reference/functions/attachment_url_to_postid/
		 */
		public function attachment_image_url_to_post( $base_url, $file_path ) {
			global $wpdb;
			list( $size, $file_no_size ) = $this->get_image_size_of_link( $file_path );

			// Massage attachment URL before handle.
			$url_has_size = $this->massage_file_url( $base_url . $file_path );

			/**
			 * Only return post_id if attachment have not file size.
			 */
			if ( empty( $size ) ) {
				$sql = $wpdb->prepare(
					"SELECT * FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
					$url_has_size
				);

				$post = $wpdb->get_row( $sql );

				return $post;
			}

			// Massage attachment URL before handle.
			$url_no_size = $this->massage_file_url( $base_url . $file_no_size );

			/**
			 * Input image: test.jpg
			 * Output image: test-scaled.jpg
			 */
			$url_no_size_scaled = $this->get_scaled_url( $url_no_size );

			/**
			 * Get all file which has size and no size.
			 */
			if ( $url_no_size_scaled ) {
				$sql = $wpdb->prepare(
					"SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value IN (%s, %s, %s)",
					$url_has_size,
					$url_no_size,
					$url_no_size_scaled
				);
			} else {
				$sql = $wpdb->prepare(
					"SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value IN (%s, %s)",
					$url_has_size,
					$url_no_size
				);
			}

			$posts = $wpdb->get_results( $sql );

			if ( count( $posts ) === 1 ) {
				return $posts[0];
			}

			/**
			 * Priority:
			 *    1. Get file which has size first
			 *    2. Get file which has no size.
			 */
			foreach ( $posts as $post ) {
				if ( $url_has_size === $post->meta_value ) {
					return $post;
				}
			}
			foreach ( $posts as $post ) {
				if ( $url_no_size === $post->meta_value ) {
					return $post;
				}
			}
			if ( $url_no_size_scaled ) {
				foreach ( $posts as $post ) {
					if ( $url_no_size_scaled === $post->meta_value ) {
						return $post;
					}
				}
			}

			return $this->might_get_post_id_from_backup_sizes( $url_no_size, $url_no_size_scaled );
		}

		/**
		 * Try to guess the post ID from backup sizes data.
		 *
		 * @param string $url_no_size       The request URL.
		 * @param string $url_no_size_scaled The scaled file URL.
		 *
		 * @return object|bool
		 *  object having the post_id key.
		 *  bool (false) cannot find any attachment file.
		 */
		public function might_get_post_id_from_backup_sizes( $url_no_size, $url_no_size_scaled ) {
			$file        = wp_basename( $url_no_size );
			$scaled_file = wp_basename( $url_no_size_scaled );
			$query_args  = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					'relation' => 'OR',
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_backup_sizes', // Case when rotate the images
					),
					array(
						'value'   => $scaled_file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_backup_sizes', // Case when crop scaled images with small size
					),
				),
			);
			$query       = new WP_Query( $query_args );
			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					// Need to query the backup sizes and double check with the input file.
					$backup_sizes       = get_post_meta( $post_id, '_wp_attachment_backup_sizes', true );
					$backup_image_files = wp_list_pluck( $backup_sizes, 'file' );
					if ( in_array( $file, $backup_image_files, true ) || in_array( $scaled_file, $backup_image_files, true ) ) {
						return (object) array(
							'post_id' => $post_id,
						);
					}
				}
			}

			return false;
		}

		/**
		 * Get scaled image.
		 *
		 * @param string $url           URL.
		 * @param string $optimize_name Optimize name for image.
		 *
		 * @return bool|string
		 */
		public function get_scaled_url( $url, $optimize_name = '-scaled' ) {
			$url_no_size_scaled  = false;
			$url_no_size_pattern = explode( '.', $url );
			$len_url_no_size     = count( $url_no_size_pattern );

			/**
			 * Check file have extension and concat '-scaled' to image URL.
			 * -scaled WP release 5.3 version.
			 */
			if ( $len_url_no_size > 1 ) {
				$url_no_size_pattern[ $len_url_no_size - 2 ] = $url_no_size_pattern[ $len_url_no_size - 2 ] . $optimize_name;
				$url_no_size_scaled                          = implode( '.', $url_no_size_pattern );
			}

			return $url_no_size_scaled;
		}

		/**
		 * Validates whether the passed variable is an integer.
		 *
		 * @param mixed $variable The variable to validate.
		 *
		 * @return bool Whether or not the passed variable is an integer.
		 */
		public static function is_integer( $variable ) {
			$value = filter_var( $variable, FILTER_VALIDATE_INT );

			return $value || $value === 0;
		}

		/**
		 * Validates whether the passed variable is an non negative integer.
		 *
		 * @param mixed $variable The variable to validate.
		 *
		 * @return bool Whether or not the passed variable is an integer.
		 */
		public static function is_non_negative_integer( $variable ) {
			if ( ! self::is_integer( $variable ) ) {
				return false;
			}

			return intval( $variable ) >= 0;
		}

		public static function get( $data, $key, $default = false ) {
			if ( ! isset( $data[ $key ] ) ) {
				return $default;
			}

			return $data[ $key ];
		}

		public static function gen_random_str( $length = 16 ) {
			$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen( $characters );
			$randomString     = '';
			for ( $i = 0; $i < $length; $i ++ ) {
				$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
			}

			return $randomString;
		}

		public static function blocked_download() {
			$message_option = get_option( 'pda_gold_update_info' );

			if ( empty( $message_option ) ) {
				return false;
			}
			$message_option = (array) json_decode( $message_option );

			return ! empty( $message_option['message'] ) && ! empty( $message_option['blocked_download'] );
		}

	}
}
