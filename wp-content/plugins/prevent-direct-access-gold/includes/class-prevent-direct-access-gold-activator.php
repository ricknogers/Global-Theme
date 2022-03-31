<?php

/**
 * Fired during plugin activation
 *
 * @link       https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda_gold
 * @since      1.0.0
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class Prevent_Direct_Access_Gold_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate( $plugin_basename ) {
		// Cannot activate the version 3.0 if the current WP site (or network) installed the version 2.0 before.
		// Commented by: thinhnp
		// Reason: no need to check v2 existed due to very rare case happening.
//		if ( Pda_Gold_Functions::is_v2_existed() ) {
//			deactivate_plugins( PDA_BASE_NAME );
//			wp_die( 'You are using the old version of Prevent Direct Access Gold 2.x.x. Please deactivate it first before using this plugin!' );
//		}
		$rewrite_ok = Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();

		$func = new Pda_Gold_Functions();
		if ( $func->pda_mod_rewrite_is_enable() && $rewrite_ok ) {
			Pda_Gold_Functions::fully_activated();
		}

		$status_files = $func->get_status_move_files();
		$total_files  = $status_files['total_files'];
		if ( ! empty( $total_files ) && intval( $total_files ) >= PDA_v3_Constants::PDA_MAX_VALUE_MOVE_FILES ) {
			if ( $func->is_move_files_after_deactivate_async() || ! empty( $status_files['num_of_protected_files'] ) ) {
				wp_die( '<pre>We’re handling ' . $status_files['num_of_protected_files'] . '/' . $total_files . ' protected files. Please come back in a while.<br><a href="' . get_admin_url() . '">Click here</a> to go back to your admin dashboard. </pre>' );
			}
			update_option( PDA_v3_Constants::PDA_IS_BACKUP_AFTER_ACTIVATE_OPTION, 1 );
			update_option( PDA_v3_Constants::PDA_NOTICE_CRONJOB_AFTER_ACTIVATE_OPTION, 1 );
		} else {
			$repo = new PDA_v3_Gold_Repository();
			$repo->backup_protection();
		}

		$cronjob_handler = new PDA_Cronjob_Handler();
		$cronjob_handler->schedule_delete_expired_private_links_cron_job();

		wp_clear_scheduled_hook( 'pda_cleanup_logs' );
		wp_schedule_event( time() + ( 5 * HOUR_IN_SECONDS ), 'daily', 'pda_cleanup_logs' );
		$helpers = new Pda_Gold_Functions();

		$helpers->handle_pda_free_version( $plugin_basename );

		$active = new PDA_v3_DB();
		$active->run();

		$helpers->set_default_settings();

		return true;
	}
}
