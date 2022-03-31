<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda_gold
 * @since      1.0.0
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/includes
 * @author     BWPS <hello@preventdirectaccess.com>
 *
 * @codeCoverageIgnore
 */
class Prevent_Direct_Access_Gold_i18n
{

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */

	public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'prevent-direct-access-gold',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
