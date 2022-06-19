<?php

namespace ForGravity\Entry_Automation;

use GFAddOn;
use GFForms;
use Plugin_Upgrader;
use WP_Error;

class_exists( 'GFForms' ) || die();

if ( ! class_exists( 'WP_Upgrader ' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
}
if ( ! class_exists( '\Plugin_Upgrader' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );
}
if ( ! class_exists( '\Plugin_Installer_Skin' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-plugin-installer-skin.php' );
}

// Include Add-On Framework.
GFForms::include_addon_framework();

/**
 * Class Extension
 * @package ForGravity\Entry_Automation
 */
abstract class Extension extends GFAddOn {

	/**
	 * Register needed hooks.
	 *
	 * @since  1.3
	 * @access public
	 */
	public function init() {

		parent::init();

		add_filter( 'auto_update_plugin', array( $this, 'maybe_auto_update' ), 10, 2 );

	}





	// # TASK SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Adds a "Connections" tab to the Task settings.
	 *
	 * @since 3.0
	 *
	 * @param array $fields Task settings fields.
	 *
	 * @return array
	 */
	public static function add_connections_tab( $fields ) {

		$existing_sections = wp_list_pluck( $fields, 'id' );

		if ( in_array( 'connections', $existing_sections ) ) {
			return $fields;
		}

		$fields[] = [
			'id'         => 'connections',
			'title'      => esc_html__( 'Connection Settings', 'forgravity_entryautomation' ),
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'action',
						'values' => [ 'export' ],
					],
				],
			],
			'sections'   => [],
		];

		return $fields;

	}





	// # EXTENSION REGISTRATION ----------------------------------------------------------------------------------------

	/**
	 * Gets all active, registered Extensions.
	 *
	 * @since  1.3.3
	 * @access public
	 *
	 * @uses   Extension::$_registered_extensions
	 *
	 * @return array Active, registered Extensions.
	 */
	public static function get_registered_extensions() {

		// Initialize extensions array.
		$extensions = array();

		// Get registered Add-Ons.
		$registered_addons = GFAddOn::get_registered_addons();

		// If no Add-Ons are registered, return.
		if ( empty( $registered_addons ) ) {
			return $extensions;
		}

		// Loop through Add-Ons.
		foreach ( $registered_addons as $registered_addon ) {

			// If cannot get instance, skip.
			if ( ! is_callable( array( $registered_addon, 'get_instance' ) ) ) {
				continue;
			}

			// Get Add-On instance.
			$addon = call_user_func( array( $registered_addon, 'get_instance' ) );

			// If Add-On does not implement Extension framework, skip.
			if ( ! is_subclass_of( $addon, '\ForGravity\Entry_Automation\Extension' ) ) {
				continue;
			}

			// Add Extension to return array.
			$extensions[] = $registered_addon;

		}

		return $extensions;

	}





	// # EXTENSION UPDATES ---------------------------------------------------------------------------------------------

	/**
	 * Determines if automatic updating should be processed.
	 *
	 * @since  Unknown
	 * @access 1.0
	 *
	 * @param bool   $update Whether or not to update.
	 * @param object $item   The update offer object.
	 *
	 * @uses   GFAddOn::log_debug()
	 * @uses   Entry_Automation::is_auto_update_disabled()
	 *
	 * @return bool
	 */
	public function maybe_auto_update( $update, $item ) {

		// If this is not the Entry Automation Add-On, exit.
		if ( ! isset( $item->slug ) || $this->_slug !== $item->slug ) {
			return $update;
		}

		// Log that we are starting auto update.
		$this->log_debug( __METHOD__ . '(): Starting auto-update for ' . $this->_short_title );

		// Check if automatic updates are disabled.
		$auto_update_disabled = fg_entryautomation()->is_auto_update_disabled();

		// Log automatic update disabled state.
		$this->log_debug( __METHOD__ . '(): Automatic update disabled: ' . var_export( $auto_update_disabled, true ) );

		// If automatic updates are disabled or if the installed version is the newest version or earlier, exit.
		if ( $auto_update_disabled || version_compare( $this->_version, $item->new_version, '=>' ) ) {
			$this->log_debug( __METHOD__ . '(): Aborting update.' );
			return false;
		}

		$current_major = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 1 ) );
		$new_major     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 1 ) );

		$current_branch = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 2 ) );
		$new_branch     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 2 ) );

		if ( $current_major == $new_major && $current_branch == $new_branch ) {
			$this->log_debug( __METHOD__ . '(): OK to update.' );
			return true;
		}

		$this->log_debug( __METHOD__ . '(): Skipping - not current branch.' );

		return $update;

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Install extension.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param string $extension_file Extension file name.
	 *
	 * @uses   Entry_Automation::check_license()
	 * @uses   Entry_Automation::get_license_key()
	 * @uses   Plugin_Upgrader::install()
	 *
	 * @return bool|WP_Error
	 */
	public static function install_extension( $extension_file = '' ) {

		// If extension file is not provided, exit.
		if ( rgblank( $extension_file ) ) {
			return new WP_Error( 'missing_file', esc_html__( 'Path to extension file was not provided.', 'forgravity_entryautomation' ) );
		}

		// Get license key.
		$license_key = fg_entryautomation()->get_license_key();

		// If no license key is available, exit.
		if ( rgblank( $license_key ) ) {
			return new WP_Error( 'missing_license_key', esc_html__( 'Unable to get extensions data because of missing license key.', 'forgravity_entryautomation' ) );
		}

		// Get license data.
		$license_data = fg_entryautomation()->check_license( $license_key );

		// If no extensions are available, exit.
		if ( ! rgobj( $license_data, 'extensions' ) ) {
			return new WP_Error( 'no_extensions', esc_html__( 'Unable to get extensions data.', 'forgravity_entryautomation' ) );
		}

		// Initialize extension to install variable.
		$to_install = false;

		// Loop through extensions.
		foreach ( $license_data->extensions as $extension ) {

			// If this is not the extension we are installing, skip.
			if ( $extension->plugin_file !== $extension_file ) {
				continue;
			}

			$to_install = $extension;

		}

		// If extension was not found, exit.
		if ( ! $to_install ) {
			return new WP_Error( 'not_found', esc_html__( 'Extension data was not found.', 'forgravity_entryautomation' ) );
		}

		// If download URL is not provided, exit.
		if ( ! $to_install->download_url ) {
			return new WP_Error( 'no_download_url', esc_html__( 'Download URL for extension was not found.', 'forgravity_entryautomation' ) );
		}

		// Install extension.
		ob_start();
		require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		$upgrader = new Plugin_Upgrader( new Extension_Installer_Skin() );
		$installed = $upgrader->install( $to_install->download_url );
		ob_end_flush();

		// If extension could not be installed, return error.
		if ( is_wp_error( $installed ) ) {
			return $installed;
		}

		// If extension is not installed, return error.
		if ( ! self::is_installed( $extension_file ) ) {
			return new WP_Error( 'unknown', esc_html__( 'Unable to download extension data.', 'forgravity_entryautomation' ) );
		}

		return true;

	}

	/**
	 * Determine if Extension is activated.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param string $extension_file Extension file name.
	 *
	 * @return bool
	 */
	public static function is_activated( $extension_file = '' ) {

		// If extension file is not provided, inherit from class.
		if ( empty( $extension_file ) ) {
			$extension_file = static::$_path;
		}

		// Get active plugins.
		$plugins = get_option( 'active_plugins' );

		return in_array( $extension_file, $plugins );

	}

	/**
	 * Determine if Extension is installed.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param string $extension_file Extension file name.
	 *
	 * @return bool
	 */
	public static function is_installed( $extension_file ) {

		// If extension file is not provided, inherit from class.
		if ( empty( $extension_file ) ) {
			$extension_file = static::$_path;
		}

		// Get installed plugins.
		$plugins = get_plugins();

		// Get extension.
		$extension = rgar( $plugins, $extension_file );

		return ! empty( $extension );

	}

}

global $wp_version;

if ( version_compare( $wp_version, '5.3', '>=' ) ) {
	/**
	 * Class Extension_Installer_Skin
	 * @package ForGravity\Entry_Automation
	 */
	class Extension_Installer_Skin extends \Plugin_Installer_Skin {
		public function feedback( $string, ...$args ) {}
		public function header() {}
		public function footer() {}
	}
} else {
	/**
	 * Class Extension_Installer_Skin
	 * @package ForGravity\Entry_Automation
	 */
	class Extension_Installer_Skin extends \Plugin_Installer_Skin {
		public function feedback( $string ) {}
		public function header() {}
		public function footer() {}
	}
}