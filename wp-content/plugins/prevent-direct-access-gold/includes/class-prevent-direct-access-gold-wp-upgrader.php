<?php

include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
if ( ! class_exists( 'PDA_WP_Upgrader_Skin' ) ) {
	/**
	 * Custom WP_Upgrader_Skin.
	 *
	 * Class PDA_WP_Upgrader_Skin
	 */
	class PDA_WP_Upgrader_Skin extends WP_Upgrader_Skin {
		/**
		 * Return empty feedback after upgrading.
		 *
		 * @param string $string Feedback message.
		 */
		function feedback( $string ) {
			return;
		}
	}
}
