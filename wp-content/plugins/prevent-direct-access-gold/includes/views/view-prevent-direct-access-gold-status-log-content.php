<?php
	/**
	 * Created by PhpStorm.
	 * User: gaupoit
	 * Date: 7/5/18
	 * Time: 13:57
	 */

	/**
	 * Render log view UI
	 *
	 * Class PDA_Status_Log_View
	 */
	class PDA_Status_Log_View {
		/**
		 * Render UI function
		 */
		public static function render() {
			$logs = self::scan_log_files();
			if ( ! empty( $_REQUEST['log_file'] ) && isset( $logs[ sanitize_title( wp_unslash( $_REQUEST['log_file'] ) ) ] ) ) { // WPCS: input var ok, CSRF ok.
				$viewed_log = $logs[ sanitize_title( wp_unslash( $_REQUEST['log_file'] ) ) ]; // WPCS: input var ok, CSRF ok.
			} elseif ( ! empty( $logs ) ) {
				$viewed_log = current( $logs );
			}
			$handle = ! empty( $viewed_log ) ? $viewed_log : '';

			include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-status-log-viewer.php';
		}
		/**
		 * Scan log files.
		 *
		 * @return array
		 */
		public static function scan_log_files() {
			return PDA_Log_Handler_file::get_log_files();
		}
		/**
         * Return the log file handle.
         *
		 * @param string $filename File name.
		 *
		 * @return bool|string
		 */
		public static function get_log_file_handle( $filename ) {
			return substr( $filename, 0, strlen( $filename ) > 37 ? strlen( $filename ) - 37 : strlen( $filename ) - 4 );
		}

		public static function massage_log_contents( $contents ) {
			return join(PHP_EOL, array_map( function( $entry ) {
			    $tmp = esc_html( $entry );
			    $color = PDA_Log_Levels::get_level_color( $tmp );
			    return "<span style=\"color: {$color}\">{$tmp}</span>";
            }, explode( PHP_EOL, $contents ) ) );
        }

		public static function remove_log() {
		    if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), 'remove_log' ) ) { // WPCS: input var ok, sanitization ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'prevent-direct-access-gold' ) );
			}
            $log_handler = new PDA_Log_Handler_file();
            $log_handler->remove( wp_unslash( $_REQUEST['handle'] ) ); // WPCS: input var ok, sanitization ok.
			wp_safe_redirect( esc_url_raw( admin_url( 'admin.php?page=pda-status&tab=logs' ) ) );
		    exit();
		}

	}
