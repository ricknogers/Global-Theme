<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda_gold
 * @since      1.0.0
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class Prevent_Direct_Access_Gold_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
    	Pda_Gold_Functions::move_files_after_deactivate();
        Pda_Gold_Functions::plugin_clean_up(true);
        return true;
    }
}
