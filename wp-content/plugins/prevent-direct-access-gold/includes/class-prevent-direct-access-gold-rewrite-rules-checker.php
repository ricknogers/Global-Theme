<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/15/19
 * Time: 17:03
 */

if ( ! class_exists( 'PDA_v3_Rewrite_Rule_Checker' ) ) {

	class PDA_v3_Rewrite_Rule_Checker {
		/**
		 * Check server, mode rewrite and htaccess file
		 *
		 * @return array|bool
		 */
		public function check_htaccess_file_in_folder_pda() {
			global $is_apache;
			if ( ! $is_apache ) {
				// NGINX or IIS.
				if ( true === Prevent_Direct_Access_Gold_Htaccess::check_rewrite_rules_by_private_link() ) {
					return $this->enable_raw_url_ok();
				}
				$server_name = Pda_v3_Gold_Helper::get_instance()->get_server_name();
				$link_guide  = $this->get_rewrite_rules_guides( $server_name );

				return $this->get_guides_message_for_not_apache_server( $link_guide );
			}

			if ( ! $this->is_mod_rewrite_enabled() ) {
				return $this->get_content_message_mod_rewrite_disable();
			}

			return $this->check_pda_htaccess_file();
		}

		/**
		 * Get header and check status code
		 *
		 * @param string $url     PDA link.
		 * @param bool   $is_file Is file.
		 *
		 * @return bool
		 */
		public function is_status_not_success( $url, $is_file = false ) {
			$res = wp_remote_get(
				$url,
				array(
					'redirection' => 0, // Get first request.
				)
			);
			if ( is_wp_error( $res ) ) {
				return false;
			}
			$status_code = wp_remote_retrieve_response_code( $res );

			if ( $is_file ) {
				return 403 == $status_code;
			}

			return $status_code >= 300;
		}

		public function get_rewrite_rules_guides( $server_name ) {
			switch ( $server_name ) {
				case PDA_v3_Constants::APACHE_SERVER:
					return PDA_v3_Constants::LINK_GUIDE_APACHE_SERVER;
				case PDA_v3_Constants::NGINX_SERVER:
					return PDA_v3_Constants::LINK_GUIDE_NGINX_SERVER;
				case PDA_v3_Constants::IIS_SERVER:
					return PDA_v3_Constants::LINK_GUIDE_IIS_SERVER;
				default:
					return PDA_v3_Constants::LINK_GUIDE_UNDEFINED_SERVER;
			}
		}


		/**
		 * Check mod rewrite is enable
		 *
		 * @return bool
		 */
		public function is_mod_rewrite_enabled() {
			if ( ! function_exists( 'apache_get_modules' ) ) {
				return true;
			}

			return in_array( 'mod_rewrite', apache_get_modules() );
		}

		/**
		 * Check htaccess file with mode single site
		 *
		 * @return array|bool
		 */
		public function check_pda_htaccess_file() {
			$upload_dir = wp_upload_dir();
			$base_dir   = $upload_dir['basedir'];

			if ( ! $this->is_pda_folder_existed( $base_dir ) ) {
				return $this->enable_raw_url_ok();
			}

			$is_htaccess_file = $this->is_htaccess_file( $base_dir );
			if ( ! $is_htaccess_file ) {
				return $this->get_content_message_htaccess_file_not_exist_in_pda_folder();
			}

			$pda_folder = $upload_dir['baseurl'] . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER;
			if ( ! $this->is_status_not_success( $pda_folder ) ) {
				return $this->get_content_message_htaccess_in_pda_folder_not_work();
			}

			return $this->enable_raw_url_ok();
		}


		public function is_pda_folder_existed( $base_dir ) {
			return is_dir( $base_dir . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER );
		}

		/**
		 * Check htaccess file in folder _pda
		 *
		 * @param $base_dir
		 *
		 * @return array|bool
		 */
		public function is_htaccess_file( $base_dir ) {
			$file = $base_dir . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER . '.htaccess';

			return is_file( $file );
		}

		/**
		 * Message for raw url is ok
		 *
		 * @return array
		 */
		public function enable_raw_url_ok() {
			return array(
				PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR => false,
			);
		}

		/**
		 * Message error for content htaccess
		 *
		 * @return array
		 */
		public function get_content_message_htaccess_in_pda_folder_not_work() {
			return array(
				PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR => true,
				PDA_v3_Constants::PDA_HTACCESS_MESSAGE       => 'Error: Our htaccess rules under _pda folder aren\'t inserted correctly. Please click on "Save changes" to fix it.',
			);
		}

		/**
		 * Message error for htaccess not exist
		 *
		 * @return array
		 */
		public function get_content_message_htaccess_file_not_exist_in_pda_folder() {
			return array(
				PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR => true,
				PDA_v3_Constants::PDA_HTACCESS_MESSAGE       => 'Error: Our .htaccess file isn’t created properly. Please click on "Save changes" to fix it.',
			);
		}

		/**
		 * Message error for mod rewrite
		 *
		 * @return array
		 */
		public function get_content_message_mod_rewrite_disable() {
			return array(
				PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR => true,
				PDA_v3_Constants::PDA_HTACCESS_MESSAGE       => 'Error: Your server doesn\'t allow rewriting .htaccess file. Please enable <b>mod_rewrite</b> module.',
			);
		}

		/**
		 * Message error for apache
		 *
		 * @param $link_guide
		 *
		 * @return array
		 */
		public function get_guides_message_for_not_apache_server( $link_guide ) {
			return array(
				PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR => true,
				PDA_v3_Constants::PDA_HTACCESS_MESSAGE       => "Warning: Our plugin is working with <a href='$link_guide' target='_blank' rel='noreferrer nofollow'>some limitation</a>",
			);
		}

		/**
		 * Check _pda folder not existed or allow access _pda folder
		 *
		 * @return bool
		 */
		public function allow_access_pda_folder() {
			global $is_apache;
			if ( ! $is_apache ) {
				return true;
			}

			$upload_dir = wp_upload_dir();
			$pda_dir    = $upload_dir['basedir'] . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER;
			if ( ! $this->is_pda_folder_existed( $upload_dir['basedir'] ) ) {
				wp_mkdir_p( $pda_dir );
			}

			$pda_url   = $upload_dir['baseurl'] . PDA_v3_Constants::PDA_PREFIX_PROTECTED_FOLDER;
			$file_name = 'pda-testing-please-do-not-remove.txt';
			$file      = $pda_dir . $file_name;
			if ( ! file_exists( $file ) ) {
				file_put_contents( $file, 'Prevent Direct Access 3.0' );
			}

			return ! $this->is_status_not_success( $pda_url . $file_name, true );
		}

		/**
		 * Check apache rules
		 *
		 * @return array
		 */
		public function check_apache_rules() {
			$result   = array();
			$expected = $this->get_expected_rules();
			$actual   = $this->get_actual_rules();

			$expected_hotlinking = array_values(
				array_filter(
					Prevent_Direct_Access_Gold_Htaccess::generate_hot_linking_rules(),
					function ( $r ) {
						return ! empty( $r );
					}
				)
			);

			if ( ! empty( $expected_hotlinking ) ) {
				$expected_hotlinking = implode( '', $expected_hotlinking );
				$actual_hotlinking   = $this->get_rules(
					'# Prevent Direct Access Prevent Hotlinking Rules',
					'# Prevent Direct Access Prevent Hotlinking Rules End',
					$actual
				);
				$result['hl']        = $expected_hotlinking === $actual_hotlinking;
			}

			$setting                     = new Pda_Gold_Functions;
			$is_prevented_sensitive_file = $setting->get_site_settings( PDA_v3_Constants::PDA_PREVENT_ACCESS_LICENSE );

			$rules_need_to_check = array(
				array(
					'rule_name' => 'ip_black',
					'start'     => '# Prevent Direct Access IP Blacklist Rules',
					'end'       => '# Prevent Direct Access Rewrite Rules End',
					'func'      => 'generate_ip_black_list_rules',
					'params'    => '',
				),
				array(
					'rule_name' => 'ip_white',
					'start'     => '# Prevent Direct Access IP Whitelist Rules',
					'end'       => '# Prevent Direct Access Rewrite Rules End',
					'func'      => 'generate_ip_white_list_rules',
					'params'    => '',
				),
				array(
					'rule_name' => 'rm',
					'start'     => '<Files readme.html>',
					'end'       => '</Files>',
					'func'      => 'get_readme_license_rules',
					'params'    => $is_prevented_sensitive_file,
				)
			);

			foreach ( $rules_need_to_check as $rule ) {
				if ( ! empty( call_user_func( "Prevent_Direct_Access_Gold_Htaccess::" . $rule['func'], $rule['params'] ) ) ) {
					$result[ $rule['rule_name'] ] = $this->compare_rules( $expected, $actual, $rule['start'], $rule['end'] );
				}
			}

			return $result;
		}

		/**
		 * Get expected rules
		 *
		 * @return array
		 */
		private function get_expected_rules() {
			$expected_rules = Prevent_Direct_Access_Gold_Htaccess::get_the_rewrite_rules();
			$expected       = array_values( array_filter(
				array_map( function ( $rule ) {
					return trim( str_replace( PHP_EOL, '', $rule ) );
				}, $expected_rules ), function ( $e ) {
				return ! empty ( $e );
			} ) );

			return $expected;
		}

		/**
		 * Get actual rules
		 *
		 * @return array
		 */
		public function get_actual_rules() {
			$htaccess         = pda_get_htaccess_rule_path();
			$content_htaccess = file_get_contents( $htaccess, false, null );
			$actual           = array_values( array_filter( preg_split( '/\n|\r\n?/', $content_htaccess ), function ( $r ) {
				return ! empty( $r );
			} ) );

			return $actual;
		}

		private function get_rules( $start_string, $end_string, $content ) {
			$start = array_search( $start_string, $content );
			$end   = array_search( $end_string, $content );

			return implode( '', array_slice( $content, $start, $end - $start + 1 ) );
		}

		/**
		 * @param $expected
		 * @param $actual
		 * @param $start
		 * @param $end
		 * @param $rule_name
		 * @param $result
		 *
		 * @return bool
		 */
		public function compare_rules( $expected, $actual, $start, $end ) {
			$expected_white_ip = $this->get_rules( $start,
				$end, $expected );
			$actual_white_ip   = $this->get_rules( $start,
				$end, $actual );

			return $expected_white_ip === $actual_white_ip;
		}
	}
}
