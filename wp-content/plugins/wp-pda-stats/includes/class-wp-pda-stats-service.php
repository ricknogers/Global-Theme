<?php
/**
 * Created by PhpStorm.
 * User: linhlbh
 * Date: 6/3/19
 * Time: 09:23
 */


/**
 * For password services
 */
if ( ! class_exists( 'PDA_Stats_Service' ) ) {

	/**
	 * Class PDA_Stats_Service
	 */
	class PDA_Stats_Service {
		protected static $instance;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get all post with password
		 *
		 * TODO: Integration-test
		 * @return array
		 * @uses WP_Protect_Password_Service
		 */
		public function get_all_post_with_password() {
			if ( ! PDA_Stats_Helpers::get_instance()->is_ppw_list_all_password_exist() ) {
				return array();
			}
			$wp_protect_password_service = new WP_Protect_Password_Service();
			if ( ! PDA_Stats_Helpers::get_instance()->should_skip_password() ) {
				$list_password_in_ppw = $wp_protect_password_service->ppw_get_all_password();
			} else {
				$list_password_in_ppw = array();
			}
			$list_password_in_stats = PDA_Stats_PPW_Repository::get_instance()->get_password_stat();

			return PDA_Stats_Helpers::get_instance()->merge_two_password_array( $list_password_in_stats, $list_password_in_ppw );
		}

		/**
		 * Get all post using password tracked
		 * TODO: Integration-test
		 * @return array|mixed
		 * @uses WP_Protect_Password_Service
		 */
		public function get_all_post_using_password_tracked() {
			$post_using_password = PDA_Stats_PPW_Repository::get_instance()->get_post_stat();
			$list_protected_post = array();
			if ( PDA_Stats_Helpers::get_instance()->is_ppw_get_all_post_protected_exist() ) {
				$wp_protect_password_service = new WP_Protect_Password_Service();
				$list_protected_post         = $wp_protect_password_service->ppw_get_all_protected_posts();
			}

			return PDA_Stats_Helpers::get_instance()->handle_data_after_tracked( $post_using_password, $list_protected_post );
		}

		/**
		 * Check addon is valid
		 * TODO: Integration-test
		 * @return bool
		 */
		public function is_addons_valid() {
			$configs   = require( 'class-wp-pda-configs.php' );
			$yme_addon = new YME_Addon( 'pda-stats' );

			$addOnIsValidPDA   = $yme_addon->isValidPurchased( $configs->addonProductId, Yme_Plugin::getLicenseKey( 'pda' ) );
			$addOnIsValidPDAv3 = $yme_addon->isValidPurchased( $configs->addonProductId, Yme_Plugin::getLicenseKey( 'pdav3' ) );
			$addOnIsValidPPWP  = $yme_addon->isValidPurchased( $configs->addonProductId, PDA_Stats_Helpers::get_instance()->get_ppwp_license_key() );

			return PDA_Stats_Helpers::get_instance()->is_addons_valid( array(
				$addOnIsValidPDA,
				$addOnIsValidPDAv3,
				$addOnIsValidPPWP
			) );
		}

		/**
		 * Get entire site password need to track for Stats
		 *
		 * @return array
		 */
		public function list_entire_site_passwords_tracked() {
			$list_passwords_tracked = PDA_Stats_PPW_Repository::get_instance()->get_entire_site_passwords_stats();
			$list_using_password    = $this->get_entire_site_passwords_using();

			return PDA_Stats_Helpers::get_instance()->entire_site_handle_data_before_send_to_client( $list_passwords_tracked, $list_using_password );
		}

		/**
		 * Get all entire site passwords info
		 *
		 * @return mixed
		 */
		public function list_entire_site_passwords_data() {
			return PDA_Stats_PPW_Repository::get_instance()->entire_site_get_passwords_info();
		}

		/**
		 * Get entire site password from Pro version
		 *
		 * @return array
		 */
		public function get_entire_site_passwords_using() {
			if ( ! function_exists( 'ppw_pro_get_setting_entire_site_type_array' ) || ! defined( 'PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE' ) ) {
				return array();
			}

			// Try to get sitewide password from PPWP Suite if user has been migrated sitewide password.
			$ps_sitewide_passwords = PDA_Stats_Helpers::get_instance()->get_migrated_sitewide_passwords();
			if ( is_array( $ps_sitewide_passwords ) ) {
				return $ps_sitewide_passwords;
			}

			return ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
		}

		/**
		 * Get PCP password from Pro version
		 *
		 * @return array
		 */
		public function get_pcp_passwords_using() {
			if ( ! method_exists( 'PPW_Pro_Password_Services', 'get_pcp_passwords' ) ) {
				return array();
			}

			$ppwp_password_service = new PPW_Pro_Password_Services();

			return $ppwp_password_service->get_pcp_passwords();
		}

		/**
		 * Get AL password from Pro version
		 *
		 * @return array
		 * @since 1.2.1
		 */
		public function get_al_passwords() {
			if ( ! method_exists( 'PPWP_AL_Repo_Level_Passwords', 'get_passwords_with_base' ) ) {
				return array();
			}

			$repo_level_passwords = new PPWP_AL_Repo_Level_Passwords();

			return $repo_level_passwords->get_passwords_with_base();
		}


		/**
		 * Get all PCP passwords info
		 *
		 * @return mixed
		 */
		public function list_pcp_passwords_data() {
			$passwords = PDA_Stats_PPW_Repository::get_instance()->get_pcp_passwords_info();

			$passwords = array_map(
				function ( $password_obj ) {
					$detail_post              = get_post( $password_obj->post_id );
					$password_obj->post_title = isset( $detail_post->post_title ) ? $detail_post->post_title : null;
					$password_obj->link       = isset( $detail_post->guid ) ? $detail_post->guid : '';

					return $password_obj;
				},
				$passwords
			);

			return array_reverse( $passwords );
		}

		/**
		 * Get all AL passwords info
		 *
		 * @return mixed
		 * @since 1.2.1
		 */
		public function list_al_passwords_data() {
			$passwords = PDA_Stats_PPW_Repository::get_instance()->get_al_passwords_info();

			$passwords = array_map(
				function ( $password_obj ) {
					$detail_post              = get_post( $password_obj->post_id );
					$password_obj->post_title = isset( $detail_post->post_title ) ? $detail_post->post_title : null;
					$password_obj->link       = isset( $detail_post->guid ) ? $detail_post->guid : '';

					return $password_obj;
				},
				$passwords
			);

			return array_reverse( $passwords );
		}

		/**
		 * Get entire site password need to track for Stats
		 *
		 * @return array
		 */
		public function list_pcp_passwords_tracked() {
			$list_passwords_tracked = PDA_Stats_PPW_Repository::get_instance()->get_pcp_passwords_stats();
			$list_using_password    = $this->get_pcp_passwords_using();

			return PDA_Stats_Helpers::get_instance()->handle_password_data_before_send_to_client( $list_passwords_tracked, $list_using_password );
		}

		/**
		 * Get al passwords need to track for Stats
		 *
		 * @return array
		 * @since 1.2.1
		 */
		public function list_al_passwords_tracked() {
			$list_passwords_tracked = PDA_Stats_PPW_Repository::get_instance()->get_al_passwords_stats();
			$list_using_password    = $this->get_al_passwords();

			return PDA_Stats_Helpers::get_instance()->handle_password_data_before_send_to_client( $list_passwords_tracked, $list_using_password, true );
		}

		/**
		 * Get password of individual site tracked
		 *
		 * @return array
		 */
		public function get_all_ppw() {
			return PDA_Stats_PPW_Repository::get_instance()->get_all_ppw();
		}

		/**
		 * Display addition field columns.
		 *
		 * @return array
		 */
		public function get_additional_field_columns() {
			return apply_filters( 'pda_stats_additional_field_columns', array() );
		}
	}

}
