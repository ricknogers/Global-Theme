<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://preventdirectaccess.com/extensions/
 * @since            1.3.1
 * @package           Wp_Pda_Stats
 *
 * @wordpress-plugin
 * Plugin Name:       PDA Download Link Statistics
 * Plugin URI:        https://preventdirectaccess.com/extensions/
 * Description:       Display relevant statistics for all your private download links or passwords.
 * Version:          1.3.1
 * Author:            BWPS
 * Author URI:        https://preventdirectaccess.com/extensions/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pda-stats
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_PDA_STATS_VERSION', '1.3.1' );

if ( ! defined( 'PDA_STATS_PLUGIN_FOLDER' ) ) {
	define( 'PDA_STATS_PLUGIN_FOLDER', 'wp-pda-stats/wp-pda-stats.php' );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-pda-stats-activator.php
 */
function activate_wp_pda_stats() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-pda-stats-activator.php';
	Wp_Pda_Stats_Activator::activate( plugin_basename( __FILE__ ) );

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-pda-stats-deactivator.php
 */
function deactivate_wp_pda_stats() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-pda-stats-deactivator.php';
	Wp_Pda_Stats_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_pda_stats' );
register_deactivation_hook( __FILE__, 'deactivate_wp_pda_stats' );

/**
 * Handle plugin uninstall
 */
function wps_plugin_uninstall() {
	Wp_Pda_Stats_Db::drop_table_and_version();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-pda-stats.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

/**
 * Remove PDA Access Restriction plugin in list plugins update of WP.
 *
 * @param object $value Data update cache.
 *
 * @return object $value Data update cache.
 */
function pda_stats_filter_plugin_updates( $value ) {
	if ( ! isset( $value->response[ PDA_STATS_PLUGIN_FOLDER ] ) ) {
		return $value;
	}
	unset( $value->response[ PDA_STATS_PLUGIN_FOLDER ] );

	return $value;
}

function run_wp_pda_stats() {
	$plugin = new Wp_Pda_Stats();
	$plugin->run();
	register_uninstall_hook( __FILE__, 'wps_plugin_uninstall' );

	if ( ! PDA_Stats_Helpers::get_instance()->main_plugins_is_deactivated() ) {
		if ( method_exists( 'Puc_v4p8_Factory', 'buildUpdateChecker' ) ) {
			Puc_v4p8_Factory::buildUpdateChecker(
				'https://s3-ap-southeast-1.amazonaws.com/bwps.gold.plugins/wp-pda-stats/metadata.json',
				__FILE__,
				'wp-pda-stats'
			);
		} elseif ( method_exists( 'Puc_v4_Factory', 'buildUpdateChecker' ) ) {
			Puc_v4_Factory::buildUpdateChecker(
				'https://s3-ap-southeast-1.amazonaws.com/bwps.gold.plugins/wp-pda-stats/metadata.json',
				__FILE__,
				'wp-pda-stats'
			);
		} else {
			add_filter( 'site_transient_update_plugins', 'pda_stats_filter_plugin_updates' );
		}
	} else {
		add_filter( 'site_transient_update_plugins', 'pda_stats_filter_plugin_updates' );
	}
}

run_wp_pda_stats();
