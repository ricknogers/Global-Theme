<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.buildwps.com/
 * @since      1.0.0
 *
 * @package    Wp_Pda_Stats
 * @subpackage Wp_Pda_Stats/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Pda_Stats
 * @subpackage Wp_Pda_Stats/includes
 * @author     BWPS <hello@ymese.com>
 */
class Wp_Pda_Stats_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate($plugin_basename) {

        if ( PDA_Stats_Helpers::get_instance()->is_deactive_pda_or_ppwp() ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( PDA_Stats_Constants::YMESE_MESSAGES['PDA_PPWP_NEVER_ACTIVATE'] );
        }

        if( ! PDA_Stats_Service::get_instance()->is_addons_valid() ) {
            deactivate_plugins( $plugin_basename );
            wp_die( PDA_Stats_Constants::YMESE_MESSAGES['PDA_ADDON_STOLEN'] );
        }
	}

}
