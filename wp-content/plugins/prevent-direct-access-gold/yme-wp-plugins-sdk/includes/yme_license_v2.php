<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 4/2/18
 * Time: 11:27
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YME_LICENSE_V2' ) ) {
	/**
	 * Class YME_LICENSE_V2 that improve the check expired license function.
	 */
	class YME_LICENSE_V2 {

		/**
		 * Checking whether the license expired.
		 *
		 * @param string $license The license key.
		 * @param array  $opt     The additional options.
		 *
		 * @return bool
		 */
		public static function check_expired_license( $license, $opt ) {
			$expired = false;
			if ( ! empty( $license ) && ! is_null( $license ) ) {
				$configs     = require( 'class/class_yme_configs.php' );
				$service_url = $configs->elc_api;
				$body_input  = array(
					'key'     => $license,
					'siteUrl' => $opt['site_url'],
				);

				$args = array(
					'body'        => wp_json_encode( $body_input ),
					'timeout'     => '1000',
					'redirection' => '5',
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'x-api-key'    => $configs->lc_key,
						'Content-Type' => 'application/json',
					),
					'cookies'     => array(),
				);

				$response = wp_remote_post( $service_url, $args );

				if ( ! is_wp_error( $response ) ) {
					$body    = wp_remote_retrieve_body( $response );
					$expired = 'true' === $body;
				}
			}

			return $expired;
		}
	}
}
