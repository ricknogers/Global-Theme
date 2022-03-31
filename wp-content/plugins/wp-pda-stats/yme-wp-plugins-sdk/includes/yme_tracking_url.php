<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 4/3/18
 * Time: 10:18
 */

if ( class_exists('YmeTrackingUrl') ) {

    class YmeTrackingUrl {
        static function generateTrackingUrl($umtSource, $position, $plugin_name) {
            $rootUrl = 'https://preventdirectaccess.com/extensions/';
            $params = "?utm_source=${umtSource}&utm_medium=${position}&utm_campaign=${$plugin_name}";
            return $rootUrl . $params;
        }
    }

}