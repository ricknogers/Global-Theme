<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/26/18
 * Time: 10:41
 */

if ( !defined('ABSPATH') ) die('You do not have sufficient permissions to access this file.');

if ( !class_exists( 'YME_MESSAGES' ) ) {
    class YME_MESSAGES {

        public static $PDA = array(
            'PDA_ADDON_STOLEN' => "You didn't purchase this add-on with this Prevent Direct Access Gold license. Please <a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://preventdirectaccess.com/extensions/\">do it now</a></a> or drop us an email at <a href=\"mailto:hello@PreventDirectAccess.com\">hello@PreventDirectAccess.com</a> if you have any questions!",
            'PDA_NEVER_PURCHASED' => 'Please purchase <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/pricing/">Prevent Direct Access Gold plugin</a> or drop us an email at <a href="mailto:hello@PreventDirectAccess.com">hello@PreventDirectAccess.com</a> for more information!',
            'PDA_UPDATE_VERSION' => 'Please update new version of plugin <a target="_blank" rel="noopener noreferrer" href="">Prevent Direct Access Gold!</a>',
            'PDA_NEVER_ACTIVATE' => 'Please install and activate <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/pricing/">Prevent Direct Access Gold</a> plugin',
            'PDA_NOTIFICATION_ACTIVATE' => 'Please deactivate the free version of Prevent Direct Access first to avoid potential conflicts',
            'NEVER_ACTIVATE_PDA_PASSWORD' => "Please install and activate <a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://preventdirectaccess.com/pricing/\">Prevent Direct Access Gold</a> or <a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://preventdirectaccess.com/extensions/password-protect-wordpress-plugin/\">Password Protect WordPress</a> plugin",
        );

        public static $PAP = array(
            'PAP_ADDON_STOLEN' => "You didn't purchase this add-on with this Protect Pages & Posts Gold license. Please <a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://preventdirectaccess.com/extensions/\">do it now</a></a> or drop us an email at <a href=\"mailto:hello@PreventDirectAccess.com\">hello@PreventDirectAccess.com</a> if you have any questions!",
            'PAP_NEVER_PURCHASED' => 'Please purchase <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/protect-wordpress-pages-posts/">Protect Pages & Posts Gold plugin</a> or drop us an email at <a href="mailto:hello@PreventDirectAccess.com">hello@PreventDirectAccess.com</a> for more information!',
            'PAP_UPDATE_VERSION' => 'Please update new version of plugin <a target="_blank" rel="noopener noreferrer" href="">Protect Pages & Posts Gold!</a>',
            'PAP_NEVER_ACTIVATE' => 'Please activate the plugin <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/protect-wordpress-pages-posts/">Protect Pages & Posts Gold</a>'
        );

        public static $MAGIC_LINK = array(
            'ADDON_STOLEN' => "You didn't purchase this add-on with your Prevent Direct Access or Protect Pages & Posts <a href=\"https://preventdirectaccess.com/extensions/license/\">Gold license</a>. Drop us an email at <a href=\"mailto:hello@PreventDirectAccess.com\">hello@PreventDirectAccess.com</a> if you have any questions!",
            'NEVER_PURCHASED' => "Please install and activate <a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://preventdirectaccess.com/pricing/\">Prevent Direct Access Gold</a> or <a target=\"_blank\" rel=\"noopener noreferrer\" href=\"https://preventdirectaccess.com/protect-wordpress-pages-posts/\">Protect Pages & Posts Gold</a> plugin",
        );
    }
}
