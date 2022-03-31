<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 1/5/18
 * Time: 15:30
 */

if (!class_exists('Yme_Plugin_Utils')) {
    class Yme_Plugin_Utils
    {
        /*
         *
         * */
        static function version_dir_plugin($plugin_tag)
        {
            require_once plugin_dir_path(__FILE__) . 'class/class_yme_plugin_info.php';
            switch ($plugin_tag) {
                case 'pda_gold':
                    return new Yme_Plugin_Info('Prevent Direct Access Gold', 'prevent-direct-access-gold/prevent-direct-access.php', 253);
                    break;
                case 'ip_block':
                    return new Yme_Plugin_Info( 'PDA Access Restriction', 'wp-pda-ip-block/wp-pda-ip-block.php', 100 );
                    break;
                case 'statistics':
                    return new Yme_Plugin_Info( 'PDA Download Link Statistics', 'wp-pda-stats/wp-pda-stats.php', 100 );
                    break;
                case 'pap_gold':
                    return new Yme_Plugin_Info( 'Protect Pages & Posts Gold', 'protect_page_post_gold/prevent_ur_pages_gold.php', 100 );
                    break;
                case 'pap_gold_old':
                    return new Yme_Plugin_Info( 'Protect Pages & Posts Gold', 'protect_page_post/prevent_ur_pages_gold.php', 100 );
                    break;
                case 'membership':
                    return new Yme_Plugin_Info( 'PDA Membership Integration', 'pda-membership-integration/pda-membership-integration.php', 100 );
                    break;
                case 'pda-s3':
                    return new Yme_Plugin_Info('Prevent Direct Access S3', 'wp-pda-s3/prevent-direct-access-by-s3.php', 100 );
                    break;
                case 'magic_link':
                    return new Yme_Plugin_Info( 'PDA Private Magic Links', 'pda-magic-link/pda-wp-magic-link.php', 100 );
                    break;
                case 'pda_woocommerce':
                    return new Yme_Plugin_Info( 'PDA Woocommerce Integration', 'pda-woocommerce/pda-woocommerce.php', 100 )    ;
                    break;
                case 'pda_contact_forms':
                    return new Yme_Plugin_Info( 'PDA Contact Forms', 'pda-contact-forms/pda-contact-forms.php', 100 )    ;
                    break;
                case 'pda_v3':
                    return new Yme_Plugin_Info('Prevent Direct Access Gold', 'prevent-direct-access-gold-3.0/prevent-direct-access.php', 100);
                    break;
	            case 'pda_video':
	            	return new Yme_Plugin_Info('PDA Protect WordPress Videos', 'protect-wp-videos/protect-ur-videos.php', 100);
	            	break;
                case 'pdas3':
                    return new Yme_Plugin_Info('PDA S3 Integration', 'pda-s3/pda-s3.php', 100);
                    break;
                case 'wp_protect_password':
                    return new Yme_Plugin_Info('Password Protect WordPress', 'wp_protect_password/wp-protect-password.php', 100);
                    break;
                default:
                    return false;
                    break;
            }
        }

        static function is_plugin_activated($plugin_tag)
        {
            $plugin_info = Yme_Plugin_Utils::version_dir_plugin($plugin_tag);
            if ( ! $plugin_info ) {
            	return 0;
            }
            //We need plugin.php!
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            $plugins = get_plugins();
            $is_installed = false;

            foreach ($plugins as $plugin_path => $plugin) {
                if ($plugin['Name'] === $plugin_info->plugin_name) {
                    $found_plugin = $plugin;
                    $is_installed = true;
                }
            }
            //!have installed return 0;
            if (!$is_installed) {
                return 0;
            }
            //!active return 1;
            if (!is_plugin_active($plugin_info->plugin_dir)) {
                return 1;
            }
            //check version
            $version = (int)str_replace(".", "", $found_plugin['Version']);
            if ($version < $plugin_info->plugin_version) {
                return 2;
            }
            return -1;
        }
    }
}