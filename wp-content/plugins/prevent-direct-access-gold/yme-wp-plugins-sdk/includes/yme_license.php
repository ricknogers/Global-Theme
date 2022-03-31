<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 4/2/18
 * Time: 11:27
 */

if ( ! defined( 'ABSPATH') ) exit;

if( !class_exists('YME_LICENSE') ) {

    class YME_LICENSE
    {
        public static function checkLicense($license, $plugin_name, $app_id = "")
        {
            $result = array(
                'isError' => true,
                'data' => ''
            );
            if (!empty($license) && !is_null($license)) {
                $configs    = require('class/class_yme_configs.php');
                $serviceUrl = $configs->lc_api;
                $app_id     = empty( $app_id ) ? Yme_Plugin::getAppId($plugin_name) : $app_id;
                $bodyInput  = array(
                    "key" => $license,
                    "productKey" => $app_id
                );
                $args       = array(
                    'body' => json_encode($bodyInput),
                    'timeout' => '100',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'x-api-key' => $configs->lc_key,
                        'Content-Type' => 'application/json'
                    ),
                    'cookies' => array()
                );
                $response = wp_remote_post($serviceUrl, $args);

                if (is_wp_error($response)) {
                    $result['message'] = $response->get_error_message();
                } else {
                    $status_code = wp_remote_retrieve_response_code($response);
                    if ($status_code != 200) {
                        $result['data'] = wp_remote_retrieve_body($response);
                        $result['isError'] = false;
                    } else {
                        $result['data'] = json_decode(wp_remote_retrieve_body($response));
                        $result['isError'] = isset($body->errorMessage) ? true : false;
                        if (isset($body->errorMessage)) {
                        }
                    }
                }
                return $result;
            }
        }

        public static function checkExpiredLicense( $license ) {
            $expired = false;
            if(!empty($license) && !is_null($license)) {
                $configs = require('class/class_yme_configs.php');
                $serviceUrl = $configs->elc_api;
                $bodyInput = array(
                    "key" => $license,
                );

                $args = array(
                    'body' => json_encode($bodyInput),
                    'timeout' => '1000',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'x-api-key' => $configs->lc_key,
                        'Content-Type' => 'application/json'
                    ),
                    'cookies' => array()
                );

                $response = wp_remote_post( $serviceUrl, $args );
                if ( !is_wp_error( $response ) ) {
                    $body = wp_remote_retrieve_body( $response );
                    $expired = $body === 'true';
                }
            }
            return $expired;
        }

        public static function getLicenseInfo($option_license_key) {
            $license_key = get_option($option_license_key);
            $result = null;
            if($license_key !== false && !empty($license_key)) {
                $configs = require('class/class_yme_configs.php');
                $serviceUrl = $configs->elc_api;
                $bodyInput = array(
                    "license" => $license_key,
                );

                $args = array(
                    'method' => 'PUT',
                    'body' => json_encode($bodyInput),
                    'timeout' => '1000',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'x-api-key' => $configs->lc_key,
                        'Content-Type' => 'application/json'
                    ),
                    'cookies' => array()
                );
                $response = wp_remote_request($serviceUrl, $args);
                if( !is_wp_error( $response ) ) {
                    $body = json_decode(wp_remote_retrieve_body( $response ));
                    $result = $body;
//                    update_option($option_product_id, $result->product_id);
                }
            }
            return $result;
        }

    }
}
