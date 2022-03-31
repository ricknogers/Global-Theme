<?php
/**
 * User: linhlbh
 * Date: 04/18/19
 * Time: 10:22
 *
 * @package PDA
 */

if ( ! class_exists( 'PDA_Validators' ) ) {
	/**
	 * PDA services that containing the functions to interact with media files.
	 *
	 * Class PDA_Services
	 */
	class PDA_Validators {
		const MAX_PREFIX_URL_LENGTH = 255;
		const REGEX_PREFIX_URL = '/(^[A-Za-z0-9-_]+$)/';

		/**
		 * Instance
		 *
		 * @var PDA_Validators
		 */
		protected static $instance;

		/**
		 * Get instance of singleton
		 *
		 * @return PDA_Validators
		 */
		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * This function check prefix_url is valid before save to setting
		 *
		 * @param string $prefix_url
		 *
		 * @return bool
		 */
		public function is_validate_prefix_url( $prefix_url = '' ) {
			$prefix_url = trim( $prefix_url );
			if ( strlen( $prefix_url ) === 0 || strlen( $prefix_url ) > self::MAX_PREFIX_URL_LENGTH ) {
				return false;
			}

			return preg_match( self::REGEX_PREFIX_URL, $prefix_url ) === 1;
		}

		/**
		 * Validate input data before update general setting to database
		 *
		 * @param array $data
		 *
		 * @return bool
		 */
		public function is_validate_before_update_general_setting( $data ) {
			return $this->is_validate_prefix_url( $data['pda_prefix_url'] );
		}
	}
}
