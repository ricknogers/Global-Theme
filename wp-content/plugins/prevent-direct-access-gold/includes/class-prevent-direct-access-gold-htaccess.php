<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/18/18
 * Time: 09:39
 */

if ( ! class_exists( 'Prevent_Direct_Access_Gold_Htaccess' ) ) {

	class Prevent_Direct_Access_Gold_Htaccess {

		static function get_the_rewrite_rules() {
			$upload  = wp_upload_dir();
			$baseurl = $upload['baseurl'];
			if ( is_ssl() ) {
				$baseurl = str_replace( 'http://', 'https://', $baseurl );
			}
			$upload_path         = str_replace( Pda_v3_Gold_Helper::get_home_url_with_ssl(), '', $baseurl );
			$secret_query_string = PDA_v3_Constants::$secret_param;
			$multisite_cond      = '';
			$subdomain_cond      = '(?:[_0-9a-zA-Z-]+/)?';

			if ( is_multisite() ) {
				if ( is_main_site() ) {
					$upload_path .= '/sites/1';
				}
				$upload_path    = preg_replace( '/\/sites\/[0-9]+/', '(?:/sites/[0-9]+)?', $upload_path );
				$multisite_cond = '(?:[_0-9a-zA-Z-]+/)?';
			}


			$is_multisite_and_sub_directory_mode = is_multisite() && is_subdomain_install();
			if ( $is_multisite_and_sub_directory_mode ) {
				$upload_path = '(?:[_0-9a-zA-Z-]+/)?' . $upload_path;
			}

			$pattern = Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/.*\.\w+)$', true );

			$old_protected_path = "$upload_path($pattern";

			$whitelisted_crawlers = self::generate_whitelisted_crawler_rules();
			$private_link_rules   = self::generate_private_link_rule( $secret_query_string, $multisite_cond, $subdomain_cond );
			$original_rules       = apply_filters( 'pda_original_rules', array(
				'RewriteRule ^' . $old_protected_path . " index.php?$secret_query_string=$1 [QSA,L]",
				'# Prevent Direct Access Rewrite Rules End',
			) );

			$hotlinking_rules            = self::generate_hot_linking_rules();
			$ip_black_list_rules         = self::generate_ip_black_list_rules();
			$ip_white_list_rules         = self::generate_ip_white_list_rules();
			$ip_white_list_website_rules = self::generate_ip_white_list_website_rules();

			$setting                     = new Pda_Gold_Functions();
			$is_prevented_sensitive_file = $setting->get_site_settings( PDA_v3_Constants::PDA_PREVENT_ACCESS_LICENSE );
			$readme_licensed             = self::get_readme_license_rules( $is_prevented_sensitive_file );

			$rewrite_rules = array_merge(
				$hotlinking_rules,
				$ip_white_list_website_rules,
				$ip_black_list_rules,
				$ip_white_list_rules,
				array(
					'# Prevent Direct Access Rewrite Rules',
					$private_link_rules,
				),
				$whitelisted_crawlers,
				$original_rules,
				$readme_licensed

			);
			// user have to manually add the rules to .htaccess.
			$is_permalinks_not_enabled = ! is_multisite() && ! get_option( 'permalink_structure' );
			if ( $is_permalinks_not_enabled ) {
				$home_root = parse_url( home_url() );
				if ( isset( $home_root['path'] ) ) {
					$home_root = trailingslashit( $home_root['path'] );
				} else {
					$home_root = '/';
				}

				array_splice( $rewrite_rules, 1, 0, array(
					'<ifModule mod_rewrite.c>',
					'RewriteEngine On',
					'RewriteBase ' . $home_root,
				) );
				array_splice( $rewrite_rules, - 1, 0, array(
					'</ifModule>',
				) );
			}

			return apply_filters( PDA_v3_Constants::$hooks['HTACCESS'], $rewrite_rules );
		}

		static function register_rewrite_rules() {
			if ( Prevent_Direct_Access_Gold_Htaccess::is_htaccess_writeable() ) {
				add_filter( 'mod_rewrite_rules', 'Prevent_Direct_Access_Gold_Htaccess::pda_handle_htaccess_rewrite_rules', 9999, 2 );
				flush_rewrite_rules();

				return true;
			}

			return false;
		}

		static function pda_handle_htaccess_rewrite_rules( $rules ) {
			$pattern = "RewriteRule ^index\.php$ - [L]\n";

			$option_index = Prevent_Direct_Access_Gold_Htaccess::add_option_indexes_rule( $rules );
			$pda_rules    = Prevent_Direct_Access_Gold_Htaccess::get_the_rewrite_rules();

			return str_replace( $pattern, "$pattern\n" . implode( "\n", $pda_rules ) . "\n\n", $rules . $option_index );
		}

		static function add_option_indexes_rule( $rules ) {
			$directory                         = new Pda_Gold_Functions;
			$pda_gold_enable_directory_listing = $directory->get_site_settings( 'pda_gold_enable_directory_listing' ) === true;
			$option_index                      = strpos( $rules, "Options -Indexes" ) === false && $pda_gold_enable_directory_listing ? "Options -Indexes" : '';

			return $option_index;
		}

		static function is_htaccess_writeable() {
			global $is_apache;

			return $is_apache
			       && ! is_multisite()
			       && get_option( 'permalink_structure' )
			       && is_writable( get_home_path() . '.htaccess' );
		}

		/**
		 * Generate white-listed crawler rules.
		 *
		 * @return array
		 */
		private static function generate_whitelisted_crawler_rules() {
			$settings = new Pda_Gold_Functions();
			$enabled  = $settings->get_site_settings( PDA_v3_Constants::PDA_GOLD_ENABLE_WEB_CRAWLERS );
			if ( ! $enabled ) {
				return array();
			}

			$whitelisted_crawlers = $settings->get_site_setting_type_is_array( PDA_v3_Constants::PDA_GOLD_WEB_CRAWLERS );

			return array_map(
				function ( $crawler ) {
					return "RewriteCond %{HTTP_USER_AGENT} !$crawler/[0-9]";
				},
				$whitelisted_crawlers
			);
		}

		/**
		 * Generate private link rerwrite rules.
		 *
		 * @param string $secret_query_string Secret query string.
		 * @param string $multi_cond          Multi-site condition.
		 *
		 * @return string
		 */
		private static function generate_private_link_rule( $secret_query_string, $multi_cond = '' ) {
			$settings               = new Pda_Gold_Functions();
			$private_param          = PDA_v3_Constants::$secret_private_link;
			$download_file_redirect =
				'index.php' . "?$secret_query_string=$1&$private_param [L]";
			$prefix                 = $settings->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );

			return "RewriteRule ^$multi_cond" . "$prefix/([a-zA-Z0-9-_.]+)$ $download_file_redirect";
		}

		public static function generate_hot_linking_rules() {
			$setting       = new Pda_Gold_Functions;
			$site_settings = get_site_option( PDA_v3_Constants::SITE_OPTION_NAME );
			$rules         = array();
			if ( $site_settings ) {
				$site_options = unserialize( $site_settings );
				if ( array_key_exists( PDA_v3_Constants::PDA_GOLD_ENABLE_IMAGE_HOT_LINKING, $site_options ) ) {
					$is_enable_hot_linking = $site_options[ PDA_v3_Constants::PDA_GOLD_ENABLE_IMAGE_HOT_LINKING ];
					if ( $is_enable_hot_linking == "true" ) {
						$domain_info = $setting->get_hostname();
						$rules       = array(
							'# Prevent Direct Access Prevent Hotlinking Rules',
							'RewriteCond %{HTTP_REFERER} !^$',
							"RewriteCond %{HTTP_REFERER} !^http(s)?://(www\.)?$domain_info [NC]",
							'RewriteRule \.(gif|jpg|jpeg|bmp|zip|rar|mp3|flv|swf|xml|png|css|pdf)$ - [F]',
							'# Prevent Direct Access Prevent Hotlinking Rules End',
							'',
						);
					}
				}
			}

			return $rules;
		}

		public static function generate_ip_black_list_rules() {
			$str_ip_lock = get_option( 'pda_gold_ip_block' );
			$arr_ip_lock = explode( ";", $str_ip_lock );
			$rules       = [];
			if ( $arr_ip_lock[0] != null ) {
				array_push( $rules, '# Prevent Direct Access IP Blacklist Rules' );
				$ip = [ '*.*.*.*', '*.*.*', '*.*', '*' ];
				for ( $i = 0; $i < count( $arr_ip_lock ); $i ++ ) {
					array_push( $rules, "RewriteCond %{REMOTE_ADDR} !^" . str_replace( $ip, '', $arr_ip_lock[ $i ] ) );
				}
				array_push( $rules, '# Prevent Direct Access IP Blacklist Rules End' . PHP_EOL );
			}

			return $rules;
		}

		/**
		 * Function to generate the white listed rules.
		 *
		 * @return mixed
		 */
		public static function generate_ip_white_list_rules() {
			$default = [];

			return apply_filters( 'pdav3_ip_white_list', $default );
		}

		public static function generate_ip_white_list_website_rules() {
			$default = [];

			return apply_filters( 'pdav3_ip_white_list_website', $default );
		}

		public static function get_readme_license_rules( $is_prevented_sensitive_file ) {
			$readme_licensed = [];
			if ( $is_prevented_sensitive_file ) {
				$files = [
					'readme.html',
					'license.txt',
					'wp-config-sample.php',
				];

				$massage_files = array_map( function ( $f, $inx ) {
					$first_eol = 0 === $inx ? PHP_EOL : '';

					return array(
						$first_eol . "<Files $f>",
						'<IfModule !mod_authz_core.c>',
						'Order Allow,Deny',
						'Deny from all',
						'</IfModule>',
						'<IfModule mod_authz_core.c>',
						'Require all denied',
						'</IfModule>',
						'</Files>' . PHP_EOL,
					);
				}, $files, array_keys( $files ) );

				foreach ( $massage_files as $m ) {
					$readme_licensed = array_merge( $readme_licensed, $m );
				}

			}

			return $readme_licensed;
		}

		/**
		 * Check status code
		 *
		 * @param string $url Url.
		 *
		 * @return bool
		 */
		public static function check_status_code( $url ) {
			$res = wp_remote_get( $url );
			if ( is_wp_error( $res ) ) {
				return false;
			}
			$status_code = wp_remote_retrieve_response_code( $res );

			return 200 === $status_code;
		}

		/**
		 * Insert attachment file
		 *
		 * @return mixed
		 */
		public static function pda_insert_attachment() {
			$image_url  = PDA_V3_BASE_DIR . 'admin/images/pda-loading.gif';
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents( $image_url );
			$filename   = wp_basename( $image_url );
			if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}
			file_put_contents( $file, $image_data );
			$wp_file_type  = wp_check_filetype( $filename, null );
			$attachment    = array(
				'post_mime_type' => $wp_file_type['type'],
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
			$attachment_id = wp_insert_attachment( $attachment, $file );

			return $attachment_id;
		}

		/**
		 * Request private link to check htaccess file
		 *
		 * @return bool|number
		 */
		public static function check_rewrite_rules_by_private_link() {

			$attachment_id = self::pda_insert_attachment();
			if ( 0 === $attachment_id ) {
				return false;
			}

			$is_protect = PDA_Private_Link_Services::protect_file( $attachment_id );
			if ( true !== $is_protect ) {
				wp_delete_attachment( $attachment_id, true );

				return false;
			}

			$private_link = PDA_Private_Link_Services::create_private_link( $attachment_id, $data = array(), false );

			if ( empty( $private_link ) ) {
				wp_delete_attachment( $attachment_id, true );

				return false;
			}

			$result = self::check_status_code( $private_link );
			wp_delete_attachment( $attachment_id, true );

			if ( false === $result ) {
				return - 1;
			}

			return $result;
		}


		/**
		 * Return rewrite rule status message
		 *
		 * @param $status
		 *
		 * @return string
		 */
		public static function get_rr_status_message( $status ) {
			if ( true === $status ) {
				return PDA_v3_Constants::R_RULE_OK;
			}

			if ( false === $status ) {
				return PDA_v3_Constants::R_RULE_ERROR;
			}

			if ( - 1 === $status ) {
				return PDA_v3_Constants::R_RULE_FAIL;
			}

			if ( - 2 === $status ) {
				return PDA_v3_Constants::R_RULE_ERROR;
			}

			return '';
		}

		static function get_nginx_rules() {
			$upload              = wp_upload_dir();
			$upload_path         = str_replace( site_url( '/' ), '', $upload['baseurl'] );
			$secret_query_string = PDA_v3_Constants::$secret_param;

			$pattern            = Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/.*\.\w+)$', true );
			$old_protected_path = "$upload_path($pattern";

			$original_rules = array(
				"rewrite $old_protected_path \"/index.php?$secret_query_string=$1\" last;",
			);

			$settings             = new Pda_Gold_Functions();
			$private_param        = PDA_v3_Constants::$secret_private_link;
			$downloadFileRedirect = "/index.php?$secret_query_string=$1&$private_param";
			$prefix               = $settings->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );

			$private_link_rules = array(
				"rewrite $prefix/([a-zA-Z0-9-_.]+)$ \"$downloadFileRedirect\" last;",
			);

			$rewrite_rules = array_merge(
				$original_rules,
				$private_link_rules
			);

			return apply_filters( PDA_v3_Constants::$hooks['NGINX'], $rewrite_rules );

		}

		static function get_iis_rules() {
			$upload              = wp_upload_dir();
			$upload_path         = str_replace( site_url( '/' ), '', $upload['baseurl'] );
			$secret_query_string = PDA_v3_Constants::$secret_param;

			$pattern            = Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/.*\.\w+)$', true );
			$old_protected_path = "$upload_path($pattern";

			$settings             = new Pda_Gold_Functions();
			$private_param        = PDA_v3_Constants::$secret_private_link;
			$downloadFileRedirect = "/index.php?$secret_query_string=$1&$private_param";
			$prefix               = $settings->prefix_roles_name( PDA_v3_Constants::PDA_PREFIX_URL );

			return array_merge(
				self::render_iss_template_rule( 'pda-original-link', $old_protected_path, "/index.php?$secret_query_string={R:1}" ),
				self::render_iss_template_rule( 'pda-private-link', "$prefix/([a-zA-Z0-9-_]+)$", $downloadFileRedirect )
			);
		}

		private static function render_iss_template_rule( $rule_name, $pattern, $redirect_url ) {
			return array(
				"<rule name=\"$rule_name\" patternSyntax=\"ECMAScript\">",
				"\tmatch url=\"$pattern\" />",
				"\t\t<conditions logicalGrouping=\"MatchAll\" trackAllCaptures=\"false\">",
				"\t\t\t<add input=\"{REQUEST_FILENAME}\" matchType=\"IsDirectory\" negate=\"true\" />",
				"\t\t</conditions>",
				"\t<action type=\"Rewrite\" url=\"$redirect_url\" />",
				"</rule>",
			);
		}

		/**
		 * @param string $pda_dir
		 * Deny for all for PDA folder
		 */
		private static function write_deny_for_all_htaccess_pda_folder( $pda_dir ) {
			$htaccess_file_name = $pda_dir . '.htaccess';
			if ( is_dir( $pda_dir ) ) {
				$htaccess_file_handle = fopen( $htaccess_file_name, 'w' );
				$content              = "# Apache 2.2
<IfModule !mod_authz_core.c>
    Order Deny,Allow
    Deny from all
</IfModule>
 
# Apache 2.4
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
";
				@fwrite( $htaccess_file_handle, $content );
				@fclose( $htaccess_file_handle );
			} else {
				error_log( 'DEBUG: ' . wp_json_encode( $htaccess_file_name ) );
			}
		}


		/**
		 * Write htaccess to folder protection.
		 *
		 * @param string $pda_dir
		 * @param string $folder_name
		 * Deny for all for PDA folder
		 */
		private static function write_htaccess_to_folder( $pda_dir, $folder_name = '_pda' ) {
			$htaccess_file_name = $pda_dir . '.htaccess';
			if ( is_dir( $pda_dir ) ) {
				$htaccess_file_handle = fopen( $htaccess_file_name, 'w' );
				$home_url             = Pda_Gold_Functions::get_home_url();
				$home_root            = parse_url( $home_url );
				if ( isset( $home_root['path'] ) ) {
					$home_root = trailingslashit( $home_root['path'] );
				} else {
					$home_root = '/';
				}

				$rules = [
					"RewriteRule ^/?(.*\.\w+)$ index.php?pda_v3_pf=/_pda/$1 [QSA,L]",
				];
				$rules = apply_filters( 'pda_folder_htaccess_rules', $rules );

				// Grant Web Crawlers Access.
				$whitelisted_crawler_rules = self::generate_whitelisted_crawler_rules();
				// Prevent Image Hotlinking.
				$hot_linking_rules = self::generate_hot_linking_rules();

				$rules = array_merge(
					$hot_linking_rules,
					$whitelisted_crawler_rules,
					$rules
				);

				// Generate htaccess rules.
				$rules = array_map( function ( $rule ) {
					return "\t$rule";
				}, $rules );
				$rules = implode( "\n", $rules );

				$content = "<IfModule mod_rewrite.c>
	RewriteBase {$home_root}
{$rules}
</IfModule>
";
				$content = apply_filters( 'pda_after_folder_htaccess_rules', $content, $pda_dir );

				@fwrite( $htaccess_file_handle, $content );
				@fclose( $htaccess_file_handle );
			} else {
				error_log( 'DEBUG: ' . wp_json_encode( $htaccess_file_name ) );
			}
		}

		/**
		 * @param string $pda_dir
		 * Delete htaccess file in pda folder
		 */
		private static function delete_htaccess_file_in_pda_folder( $pda_dir ) {
			$htaccess_path = $pda_dir . '.htaccess';
			clearstatcache();
			if ( file_exists( $htaccess_path ) ) {
				if ( is_writable( $htaccess_path ) ) {
					unlink( $htaccess_path );
				} else {
					chmod( $htaccess_path, 0666 );
					unlink( $htaccess_path );
				}
			}
		}

		/**
		 * Handle htaccess file in pda folder
		 *
		 * @param string $use_redirect_urls
		 *
		 * @param bool   $is_main_site
		 *
		 */
		public static function handle_htaccess_file_in_folder( $use_redirect_urls, $is_main_site = false ) {
			$base_dir = wp_upload_dir()['basedir'];
			$pda_name = '/_pda/';
			if ( $use_redirect_urls === 'false' ) {
				self::delete_htaccess_file_in_pda_folder( $base_dir . $pda_name );
				$settings       = new Pda_Gold_Functions();
				$force_htaccess = $settings->getSettings( PDA_v3_Constants::FORCE_PDA_HTACCESS );
				if ( $force_htaccess ) {
					self::write_htaccess_to_folder( $base_dir . $pda_name );
				}
				if ( is_multisite() && $is_main_site ) {
					$sites = get_sites();
					foreach ( $sites as $site ) {
						if ( $site->blog_id !== '1' ) {
							$site_base_dir = $base_dir . '/sites/' . $site->blog_id;
							self::delete_htaccess_file_in_pda_folder( $site_base_dir . $pda_name );
						}
					}
				}
			} elseif ( $use_redirect_urls === 'true' ) {
				self::write_deny_for_all_htaccess_pda_folder( $base_dir . $pda_name );
				if ( is_multisite() && $is_main_site ) {
					$sites = get_sites();
					foreach ( $sites as $site ) {
						if ( $site->blog_id !== '1' ) {
							$site_base_dir = $base_dir . '/sites/' . $site->blog_id;
							self::write_deny_for_all_htaccess_pda_folder( $site_base_dir . $pda_name );
						}
					}
				}
			}
		}
	}

}
