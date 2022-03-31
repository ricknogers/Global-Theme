<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/7/18
 * Time: 10:22
 */

if ( !defined('ABSPATH') ) die('You do not have sufficient permissions to access this file.');

if ( !class_exists( 'YME_Addon' ) ) {

    class YME_Addon {

        public $name = '';

        function __construct( $name )
        {
            $this->name = $name;
        }


        function isValidPurchased( $addonProductId, $pluginLicense ) {
            $api = new Yme_AWS_Api();
            return $api->checkAddonLicensed( $addonProductId, $pluginLicense );
        }

    }
}
