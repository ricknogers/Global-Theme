<?php
/**
 * Plugin Name: Entry Automation for Gravity Forms
 * Plugin URI: https://forgravity.com/plugins/entry-automation/
 * Description: Automate your most common Gravity Forms entry maintenance tasks in a snap. Schedule automatic deletion, exporting, and more.
 * Version: 5.0.5
 * Requires PHP: 5.6
 * Author: ForGravity
 * Author URI: http://forgravity.com
 * Text Domain: forgravity_entryautomation
 * Domain Path: /languages
 **/

if ( ! defined( 'FG_EDD_STORE_URL' ) ) {
	define( 'FG_EDD_STORE_URL', 'https://forgravity.com' );
}

define( 'FG_ENTRYAUTOMATION_VERSION', '5.0.5' );
define( 'FG_ENTRYAUTOMATION_EVENT', 'fg_entryautomation_automate' );
define( 'FG_ENTRYAUTOMATION_EDD_ITEM_ID', 112 );
define( 'FG_ENTRYAUTOMATION_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Initialize the autoloader.
require_once( 'includes/autoload.php' );

// Initialize plugin updater.
add_action( 'init', array( 'EntryAutomation_Bootstrap', 'updater' ), 0 );

// If Gravity Forms is loaded, bootstrap the Entry Automation Add-On.
add_action( 'gform_loaded', array( 'EntryAutomation_Bootstrap', 'load' ), 5 );

// Include Gravity Flow step.
add_action( 'gravityflow_loaded', array( 'EntryAutomation_Bootstrap', 'load_gravityflow' ), 5 );

/**
 * Class EntryAutomation_Bootstrap
 *
 * Handles the loading of the Entry Automation Add-On and registers with the Add-On framework.
 */
class EntryAutomation_Bootstrap {

	/**
	 * If the Feed Add-On Framework exists, Entry Automation Add-On is loaded.
	 *
	 * @access public
	 * @static
	 */
	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
			return;
		}

		ForGravity\Entry_Automation\Action::register( 'ForGravity\Entry_Automation\Action\Delete' );
		ForGravity\Entry_Automation\Action::register( 'ForGravity\Entry_Automation\Action\Export' );

		if ( ! version_compare( GFCommon::$version, '2.5-dev-1', '>=' ) ) {
			GFAddOn::register( '\ForGravity\Entry_Automation\Legacy\Entry_Automation' );
		} else {
			ForGravity\Entry_Automation\Action::register( 'ForGravity\Entry_Automation\Action\Notification' );
			GFAddOn::register( '\ForGravity\Entry_Automation\Entry_Automation' );
		}

		// Backwards compatibility for extensions after namespace changed.
		if ( ! class_exists( '\ForGravity\EntryAutomation\EDD_SL_Plugin_Updater' ) ) {
			class_alias( '\ForGravity\Entry_Automation\EDD_SL_Plugin_Updater', '\ForGravity\EntryAutomation\EDD_SL_Plugin_Updater' );
		}

		if ( ! class_exists( '\ForGravity\EntryAutomation\Extensions\FTP\EDD_SL_Plugin_Updater' ) ) {
			class_alias( '\ForGravity\Entry_Automation\EDD_SL_Plugin_Updater', '\ForGravity\EntryAutomation\Extensions\FTP\EDD_SL_Plugin_Updater' );
		}

		if ( ! class_exists( '\ForGravity\EntryAutomation\Extension' ) ) {
			class_alias( '\ForGravity\Entry_Automation\Extension', '\ForGravity\EntryAutomation\Extension' );
		}

	}

	/**
	 * If the Gravity Flow exists, Entry Automation Step is loaded.
	 *
	 * @since 3.0
	 */
	public static function load_gravityflow() {

		Gravity_Flow_Steps::register( new \ForGravity\Entry_Automation\Integrations\Gravity_Flow\Step() );

	}

	/**
	 * Initialize plugin updater.
	 *
	 * @access public
	 * @static
	 */
	public static function updater() {

		if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
			return;
		}

		// Get Entry Automation instance.
		$entry_automation = fg_entryautomation();

		// If Entry Automation could not be retrieved, exit.
		if ( ! $entry_automation ) {
			return;
		}

		// Get license key.
		$license_key = fg_entryautomation()->get_license_key();

		new ForGravity\Entry_Automation\EDD_SL_Plugin_Updater(
			FG_EDD_STORE_URL,
			__FILE__,
			array(
				'version' => FG_ENTRYAUTOMATION_VERSION,
				'license' => $license_key,
				'item_id' => FG_ENTRYAUTOMATION_EDD_ITEM_ID,
				'author'  => 'ForGravity',
			)
		);

	}
}

/**
 * Returns an instance of the Entry_Automation class
 *
 * @see    Entry_Automation::get_instance()
 *
 * @return ForGravity\Entry_Automation\Entry_Automation
 */
function fg_entryautomation() {

	// If running on Gravity Forms 2.4.x, run legacy version.
	if ( ! version_compare( GFCommon::$version, '2.5-dev-1', '>=' ) ) {
		return ForGravity\Entry_Automation\Legacy\Entry_Automation::get_instance();
	}

	return ForGravity\Entry_Automation\Entry_Automation::get_instance();

}
