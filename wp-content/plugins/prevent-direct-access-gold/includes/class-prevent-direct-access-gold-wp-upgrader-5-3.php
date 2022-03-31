<?php

include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
if ( ! class_exists( 'PDA_WP_Upgrader_Skin_5_3' ) ) {
	/**
	 * Custom WP_Upgrader_Skin.
	 *
	 * Class PDA_WP_Upgrader_Skin
	 */
	class PDA_WP_Upgrader_Skin_5_3 extends WP_Upgrader_Skin {
		/**
		 * Return empty feedback after upgrading.
		 *
		 * @param string $string The feedback message.
		 * @param array  ...$args The arguments.
		 */
		function feedback( $string, ...$args ) {
			return;
		}
	}
}
