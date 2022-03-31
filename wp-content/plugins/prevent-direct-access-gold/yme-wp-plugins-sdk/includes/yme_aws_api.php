<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/7/18
 * Time: 14:08
 */

if ( !class_exists('Yme_AWS_Api') ) {

    class Yme_AWS_Api {

        function checkAddonLicensed( $addonProductId, $pluginLicense ) {

            $congfigs = require('class/class_yme_configs.php');
            $endpoint = $congfigs->addon_endpoint;

            $body = array(
                'addonProductId' => $addonProductId,
                'pluginLicense' => $pluginLicense
            );

            $args = array(
                'body' => json_encode($body),
                'timeout' => 100,
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'x-api-key' => $congfigs->aok
                )
            );

            $response = wp_remote_post( $endpoint, $args );

            $result = array(
                'isValid' => false,
                'error' => ''
            );

            if ( is_wp_error ( $response ) ) {
                $error_message = $response->get_error_message();
                $result['error'] = $error_message;
            } else {
                $status_code = wp_remote_retrieve_response_code($response);
                if ($status_code == 200) {
                    $body = json_decode(wp_remote_retrieve_body($response));
                    $result['isValid'] = $body;
                }
            }

            return $result;

        }
    }

}
