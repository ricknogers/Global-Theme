<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda-gold
 * @since             3.3.2
 * @package           Prevent_Direct_Access_Gold
 *
 * @wordpress-plugin
 * Plugin Name:       Prevent Direct Access Gold
 * Plugin URI:        https://preventdirectaccess.com/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda-gold
 * Description:       Prevent Direct Access blocks search indexing as well as protects unlimited WordPress files and all file types including MP4, PNG & PDF and much more.
 * Network: false
 * Version:           3.3.2
 * Author:            BWPS
 * Author URI:        https://preventdirectaccess.com/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda-gold
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       prevent-direct-access-gold
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
define( 'PDA_GOLD_V3_VERSION', '3.3.2' );
define( 'PDA_V3_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'PDA_V3_PLUGIN_BASE_FILE', __FILE__ );
define( 'PDA_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'PDA_BASE_NAME', wp_basename( __FILE__ ) );
define( 'PDA_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'PDA_LOG_DIR', wp_upload_dir( null, false )['basedir'] . '/pda-logs' );
define( 'PDA_SLUG', 'prevent-direct-access-gold' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-prevent-direct-access-gold-activator.php
 */
function activate_prevent_direct_access_gold() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prevent-direct-access-gold-activator.php';
	Prevent_Direct_Access_Gold_Activator::activate( plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-prevent-direct-access-gold-deactivator.php
 */
function deactivate_prevent_direct_access_gold() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prevent-direct-access-gold-deactivator.php';
	Prevent_Direct_Access_Gold_Deactivator::deactivate();
}

function plugin_uninstall() {
	$db = new PDA_v3_DB();
	$db->uninstall();
	Pda_Gold_Functions::plugin_clean_up();
}

register_activation_hook( __FILE__, 'activate_prevent_direct_access_gold' );
register_deactivation_hook( __FILE__, 'deactivate_prevent_direct_access_gold' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-prevent-direct-access-gold.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_prevent_direct_access_gold() {

	$plugin = new Prevent_Direct_Access_Gold();
	$plugin->run();

	register_uninstall_hook( __FILE__, 'plugin_uninstall' );
	$configs = require PDA_V3_BASE_DIR . '/includes/class-prevent-direct-access-gold-configs.php';
	// Make sure the class Puc_v4p8_Factory exits.
	if ( class_exists( 'Puc_v4p8_Factory' ) ) {
		Puc_v4p8_Factory::buildUpdateChecker(
			$configs->update_url,
			__FILE__,
			'prevent-direct-access-gold'
		);
	} else {
		Puc_v4_Factory::buildUpdateChecker(
			$configs->update_url,
			__FILE__,
			'prevent-direct-access-gold'
		);
	}

	new PDA_Move_Files_After_Activate();
	new PDA_Move_Files_After_Deactivate();
	new PDA_Activate_All_Sites();
}

run_prevent_direct_access_gold();
